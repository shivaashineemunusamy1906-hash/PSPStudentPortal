<?php

class Student
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // CREATE
    public function addStudent($name, $ic, $marks)
    {
        $sql = "INSERT INTO students (name, ic, marks)
                VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("ssd", $name, $ic, $marks);

        return $stmt->execute();
    }

    // READ
    public function getStudents()
    {
        $sql = "SELECT * FROM students ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        return $stmt->get_result();
    }

    // READ ONE
    public function getStudent($id)
    {
        $sql = "SELECT * FROM students WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $id);

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // UPDATE
    public function updateStudent($id, $name, $ic, $marks)
    {
        $sql = "UPDATE students
                SET name = ?, ic = ?, marks = ?
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("ssdi", $name, $ic, $marks, $id);

        return $stmt->execute();
    }

    // DELETE
    public function deleteStudent($id)
    {
        $sql = "DELETE FROM students WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}

?>