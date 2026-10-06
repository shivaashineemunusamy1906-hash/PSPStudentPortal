<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Student.php";

class StudentController
{
    private $studentModel;

    public function __construct()
    {
        $database = new Database();
        $db = $database->connect();

        $this->studentModel = new Student($db);
    }

    public function getStudents()
    {
        return $this->studentModel->getStudents();
    }

    public function getStudent($id)
    {
        return $this->studentModel->getStudent($id);
    }

    public function addStudent($name, $ic, $marks)
    {
        return $this->studentModel->addStudent(
            $name,
            $ic,
            $marks
        );
    }

    public function updateStudent($id, $name, $ic, $marks)
    {
        return $this->studentModel->updateStudent(
            $id,
            $name,
            $ic,
            $marks
        );
    }

    public function deleteStudent($id)
    {
        return $this->studentModel->deleteStudent($id);
    }
}

?>