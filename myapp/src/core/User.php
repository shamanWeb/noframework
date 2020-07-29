<?php

namespace App\core;

use \App\models\UserModel;

class User
{
    /**
     * @var bool
     */
    protected $isGuest = true;

    /**
     * @var UserModel
     */
    public $model;

    /**
     * @return bool
     */
    public function isGuest(): bool
    {
        return $this->isGuest;
    }

    public function init(): void
    {
        $userSession = App::$session->get('user', null);

        if (!$userSession || empty($userSession['id'])) {
            return;
        }

        /** @var UserModel $user */
        $user = UserModel::findById($userSession['id']);
        if (!$user) {
            return;
        }

        if (UserModel::generateSessionHash($userSession['hash']) !== $user->hash) {
            return;
        }

        $this->isGuest = false;
        $this->model = $user;
    }

    public function auth($username, $password): bool
    {
        $user = UserModel::findByUsername($username);
        if (!$user) {
            return false;
        }

        if (UserModel::passwordHash($password) !== $user->password) {
            return false;
        }

        $hash = md5(time() . $user->id);

        $user->updateSessionHash(UserModel::generateSessionHash($hash));

        App::$session->set('user', [
            'id'   => $user->id,
            'hash' => $hash,
        ]);

        return true;
    }
}