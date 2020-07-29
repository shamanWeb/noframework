<?php

namespace App\core;

use mysqli;

class Database
{
    protected mysqli $connection;

    /**
     * Init DB
     * @param array $config
     * @return mysqli
     * @throws AppException
     */
    public function init(array $config): mysqli
    {
        $this->connection = new mysqli($config['host'], $config['username'], $config['password'], $config['dbname'], $config['port']);

        if ($this->connection->connect_errno) {
            throw new AppException(
                'Database connection Error | Code: ' . $this->connection->connect_errno . ' | Message: ' . $this->connection->connect_error
            );
        }

        register_shutdown_function([$this, 'close']);

        return $this->connection;
    }

    /**
     * Close connection
     */
    public function close(): void
    {
        $this->connection->close();
    }
}