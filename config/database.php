<?php

class Database
{
    // укажите свои учетные данные базы данных
    private $host = "localhost";
    private $db_name = "test";
    private $username = "root";
    private $password = "root";
    public $conn;

    /**
     * @return null|PDO
     */
    public function getConnection(): ?PDO
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}