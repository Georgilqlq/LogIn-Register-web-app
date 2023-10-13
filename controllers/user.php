<?php
include_once("../db/db.php");

class User
{
    private $connection;
    private $response;

    public function __construct()
    {
        $this->connection = new DataBaseConnection();
        $this->response = array();
    }

    public function register_user(string $username, string $password, string $email, bool $is_verified = false): bool
    {
        return $this->connection->addUser($username, $password, $email, $is_verified);
    }
}