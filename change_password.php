<?php

session_start();

require_once "config/database.php";
require_once "models/User.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->connect();

$userModel = new User($db);

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $oldPassword = $_POST["old_password"];
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    $user = $userModel->getUserById($_SESSION["user_id"]);

    if (!password_verify($oldPassword, $user["password"])) {

        $error = "Old password is incorrect.";

    } elseif ($newPassword != $confirmPassword) {

        $error = "New passwords do not match.";

    } elseif (strlen($newPassword) < 6) {

        $error = "Password must contain at least 6 characters.";

    } else {

        if ($userModel->updatePassword(
            $_SESSION["user_id"],
            $newPassword
        )) {

            $message = "Password updated successfully.";

        } else {

            $error = "Password update failed.";
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Change Password</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-body">

                    <h3 class="mb-4">
                        Change Password
                    </h3>

                    <?php if ($message != "") { ?>

                        <div class="alert alert-success">
                            <?php echo $message; ?>
                        </div>

                    <?php } ?>

                    <?php if ($error != "") { ?>

                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>

                    <?php } ?>

                    <form method="POST">

                        <div class="mb-3">

                            <label>Old Password</label>

                            <input
                                type="password"
                                name="old_password"
                                class="form-control"
                                required>

                        </div>

                        <div class="mb-3">

                            <label>New Password</label>

                            <input
                                type="password"
                                name="new_password"
                                class="form-control"
                                minlength="6"
                                required>

                        </div>

                        <div class="mb-3">

                            <label>Confirm Password</label>

                            <input
                                type="password"
                                name="confirm_password"
                                class="form-control"
                                minlength="6"
                                required>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-success">

                            Update Password

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>