<?php

namespace App\models;


use App\core\App;
use App\core\AppException;
use App\core\Model;

class UserModel extends Model
{
    public static $tableName = 'user';
    public static $accountTableName = 'account';
    public $balance;

    /**
     * @param string $username
     * @return UserModel|null
     */
    public static function findByUsername($username): ?UserModel
    {
        $sql = 'SELECT * FROM ' . static::$tableName . ' WHERE `username` = ?';
        $stmt = App::$db->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows ? static::createModel($result->fetch_array()) : null;
    }

    /**
     * @param string $password
     * @return string
     */
    public static function passwordHash($password): string
    {
        return md5(md5($password));
    }

    /**
     * @param string $sessionHash
     * @return string
     */
    public static function generateSessionHash($sessionHash): string
    {
        $ip = md5(md5($_SERVER['SERVER_ADDR']));

        return md5($ip . $sessionHash);
    }

    /**
     * @param string $hash
     * @return bool
     */
    public function updateSessionHash($hash): bool
    {
        if (!$this->isLoaded()) {
            return false;
        }

        $sql = 'UPDATE ' . static::$tableName . ' SET `hash` = ? WHERE `id` = ?';
        $stmt = App::$db->prepare($sql);
        $stmt->bind_param('si', $hash, $this->id);

        return $stmt->execute();
    }

    /**
     * @return int
     */
    public function getBalance(): int
    {
        return $this->balance;
    }

    /**
     * @return array
     */
    public function getAccountHistory(): array
    {
        $status = 'new';
        $sql = 'SELECT * FROM ' . static::$accountTableName . ' WHERE `user_id` = ? AND `status` != ? ORDER BY `id` DESC';
        $stmt = App::$db->prepare($sql);
        $stmt->bind_param('is', $this->id, $status);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * @param float $value
     * @return bool|string
     */
    public function createPay($value)
    {
        $value = -1 * abs($value);
        $hash = md5(md5(time() + $this->getBalance()));
        $stmt = App::$db->prepare('INSERT INTO `' . static::$accountTableName . '` (`user_id`, `value`, `hash`) VALUES (?, ?, ?)');
        $stmt->bind_param('ids', $this->id, $value, $hash);
        $result = $stmt->execute();

        return $result ? $hash : false;
    }

    /**
     * @param string $hash
     * @return bool
     */
    public function pay($hash): bool
    {
        try {
            if (!$this->isLoaded()) {
                throw new AppException('Model not loaded');
            }

            $db = App::$db;
            $db->begin_transaction();

            // get user to lock
            $sql = 'SELECT * FROM ' . static::$tableName . ' WHERE `id` = ? FOR UPDATE';
            $stmt = $db->prepare($sql);
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();

            // get "new" pay
            $status = 'new';
            $sql = 'SELECT * FROM ' . static::$accountTableName . ' WHERE `hash` = ? AND `status` = ? FOR UPDATE';
            $stmt = $db->prepare($sql);
            $stmt->bind_param('ss', $hash, $status);
            $stmt->execute();
            $result = $stmt->get_result();

            if (!$result->num_rows) {
                $db->rollback();

                return false;
            }

            $pay = $result->fetch_assoc();
            $this->setPayStatus($pay['id'], 'process');

            $value = (float)$pay['value'];
            $balance = (float)$user['balance'];

            if ($balance + $value < 0) {
                $db->rollback();
                $this->setPayStatus($pay['id'], 'failed');

                return false;
            }

            $result = $this->setPayStatus($pay['id'], 'success');

            $balance += $value;

            $sql = 'UPDATE ' . static::$tableName . ' SET `balance` = ? WHERE `id` = ?';
            $stmt = App::$db->prepare($sql);
            $stmt->bind_param('di', $balance, $this->id);
            $result = $stmt->execute() && $result;

            if (!$result) {
                $db->rollback();
                $this->setPayStatus($pay['id'], 'failed');

                return false;
            }

            $db->commit();

            return true;

        } catch (AppException $e) {
            $db->rollback();
        }
        return false;
    }

    /**
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function setPayStatus($id, $status): bool
    {
        $id = (int)$id;
        $sql = 'UPDATE ' . static::$accountTableName . ' SET `status` = ? WHERE `id` = ?';
        $stmt = App::$db->prepare($sql);
        $stmt->bind_param('si', $status, $id);
        return $stmt->execute();
    }
}