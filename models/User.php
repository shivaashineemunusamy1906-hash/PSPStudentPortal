<?php

class User
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function login($nric, $password)
    {
        $sql = "SELECT * FROM users WHERE nric = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $nric);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }

        return false;
    }

    public function getUserById($id)
    {
        $sql = "SELECT * FROM users WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function updatePassword($id, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE users SET password = ? WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $hashedPassword, $id);

        return $stmt->execute();
    }

    // UPDATE PROFILE PICTURE
    public function updateProfilePicture($id, $fileName)
    {
        $sql = "UPDATE users
                SET profile_picture = ?
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("si", $fileName, $id);

        return $stmt->execute();
    }
}

?>