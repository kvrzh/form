<?php

namespace app\classes;

use PDO;

class MyPDO extends PDO
{
    public function __construct($dsn, $username, $password, array $options = [])
    {
        $default_options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $options = array_merge($default_options, $options);
        parent::__construct($dsn, $username, $password, $options);
    }

    public function run($sql, array $args = [])
    {
        $stmt = $this->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }
}