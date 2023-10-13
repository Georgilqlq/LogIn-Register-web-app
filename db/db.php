<?php

class DataBaseConnection
{
    private $connection;
    private $insertUser;

    public function __construct()
    {
        try {
            $db_credentials = parse_ini_file("config.ini", true);
            $this->connection = new PDO('mysql:host=localhost; dbname=users', $db_credentials["user"], $db_credentials["password"]);
        } catch (PDOException $error) {
            echo "Connection to db failed: " . $error->getMessage();
        }
    }

    public function addUser(string $username, string $password, string $email, bool $is_verified)
    {
        $sql = 'INSERT INTO accounts(username,email,password,verified)
                VALUES(:username, :email, :password, :verified)';
        $statement = $this->connection->prepare($sql);

        $statement->bindValue(':username', $username, PDO::PARAM_STR);
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->bindValue(':password', password_hash($password, PASSWORD_BCRYPT), PDO::PARAM_STR);
        $statement->bindValue(':verified', (int) $is_verified, PDO::PARAM_INT);

        return $statement->execute();
    }



}