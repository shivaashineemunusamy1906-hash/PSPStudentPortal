<?php

session_start();

require_once "config/database.php";
require_once "models/Student.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: students.php");
    exit();
}

$database = new Database();
$db = $database->connect();

$studentModel = new Student($db);

$id = $_GET["id"];

$studentModel->deleteStudent($id);

header("Location: students.php");
exit();

?>