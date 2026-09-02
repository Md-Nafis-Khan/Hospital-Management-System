<?php

require_once __DIR__ . "/../config/database.php";

class Database
{
    public $connection;

    public function connect()
    {
        try {
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USER,
                DB_PASS
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $error) {
            die("Database connection failed");
        }

        return $this->connection;
    }
}