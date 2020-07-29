<?php

namespace App\core;

class Session
{
    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * Open session
     * @param bool $write
     */
    public function open($write = false): void
    {
        session_start();

        if (!$write) {
            $this->writeClose();
        }
    }

    /**
     * Close session
     */
    public function writeClose(): void
    {
        session_write_close();
    }

    /**
     * @param string $key
     * @param null|mixed $defaultValue
     * @return null|mixed
     */
    public function get($key, $defaultValue = null)
    {
        return $_SESSION[$key] ?? $defaultValue;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Session destroy
     */
    public function destroy(): void
    {
        if ($this->isActive()) {
            session_unset();
            session_destroy();
        }
    }
}