<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/User.php";

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $database = new Database();
        $db = $database->connect();

        $this->userModel = new User($db);
    }

    public function login($nric, $password)
    {
        return $this->userModel->login($nric, $password);
    }

    public function logout()
    {
        session_start();

        session_unset();
        session_destroy();

        header("Location: ../login.php");
        exit();
    }
}

?>