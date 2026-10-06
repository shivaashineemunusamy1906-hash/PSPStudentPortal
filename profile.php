<?php

session_start();

require_once "config/database.php";
require_once "models/User.php";
require_once "controllers/ProfileController.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Create Profile Controller
$profileController = new ProfileController();

// Handle profile picture upload
$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_FILES["profile_picture"])) {

        $result = $profileController->uploadProfilePicture(
            $_SESSION["user_id"],
            $_FILES["profile_picture"]
        );

        $message = $result["message"];

        if ($result["success"]) {
            $messageType = "success";
        } else {
            $messageType = "danger";
        }
    }
}

// Get current user information
$user = $profileController->getProfile(
    $_SESSION["user_id"]
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Student Profile</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <h3>Student Profile</h3>

        </div>

        <div class="card-body">

            <!-- SUCCESS / ERROR MESSAGE -->

            <?php if ($message != ""): ?>

                <div class="alert alert-<?php echo $messageType; ?>">

                    <?php echo htmlspecialchars($message); ?>

                </div>

            <?php endif; ?>


            <!-- PROFILE PICTURE -->

            <div class="text-center mb-4">

                <?php if (!empty($user["profile_picture"])): ?>

                    <img
                        src="uploads/<?php echo htmlspecialchars($user["profile_picture"]); ?>"
                        alt="Profile Picture"
                        width="150"
                        height="150"
                        class="rounded-circle border"
                        style="object-fit: cover;"
                    >

                <?php else: ?>

                    <div
                        class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto"
                        style="width:150px; height:150px;"
                    >

                        No Photo

                    </div>

                <?php endif; ?>

            </div>


            <!-- UPLOAD PROFILE PICTURE -->

            <div class="card mb-4">

                <div class="card-header">

                    <strong>Upload Profile Picture</strong>

                </div>

                <div class="card-body">

                    <form
                        method="POST"
                        enctype="multipart/form-data"
                    >

                        <div class="mb-3">

                            <label
                                for="profile_picture"
                                class="form-label"
                            >

                                Select Profile Picture

                            </label>

                            <input
                                type="file"
                                name="profile_picture"
                                id="profile_picture"
                                class="form-control"
                                accept=".jpg,.jpeg,.png"
                                required
                            >

                            <div class="form-text">

                                Allowed: JPG, JPEG and PNG.
                                Maximum size: 2MB.

                            </div>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-success"
                        >

                            Upload Picture

                        </button>

                    </form>

                </div>

            </div>


            <!-- STUDENT INFORMATION -->

            <p>

                <strong>Name:</strong>

                <?php echo htmlspecialchars($user["name"]); ?>

            </p>


            <p>

                <strong>NRIC:</strong>

                <?php echo htmlspecialchars($user["nric"]); ?>

            </p>


            <p>

                <strong>Program:</strong>

                <?php echo htmlspecialchars($user["program"]); ?>

            </p>


            <!-- BUTTONS -->

            <a
                href="change_password.php"
                class="btn btn-warning">

                Change Password

            </a>


            <a
                href="students.php"
                class="btn btn-primary">

                Student Grades

            </a>


            <a
                href="logout.php"
                class="btn btn-danger">

                Logout

            </a>

        </div>

    </div>

</div>

</body>

</html>