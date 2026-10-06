<?php

session_start();

require_once __DIR__ . "/config/database.php";
require_once __DIR__ . "/models/User.php";

$database = new Database();
$db = $database->connect();

$userModel = new User($db);

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nric = trim($_POST["nric"]);
    $password = $_POST["password"];

    if (empty($nric) || empty($password)) {

        $error = "Please enter NRIC and password.";

    } else {

        $user = $userModel->login($nric, $password);

        if ($user) {

            $_SESSION["user_id"] = $user["id"];

            header("Location: profile.php");
            exit();

        } else {

            $error = "Invalid NRIC or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Student Portal Login</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card shadow">

                <div class="card-body">

                    <h2 class="text-center mb-4">
                        PSP Student Portal
                    </h2>

                    <?php if ($error != "") { ?>

                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>

                    <?php } ?>

                    <form method="POST">

                        <div class="mb-3">

                            <label>NRIC</label>

                            <input
                                type="text"
                                name="nric"
                                class="form-control"
                                required>

                        </div>

                        <div class="mb-3">

                            <label>Password</label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            Login

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>