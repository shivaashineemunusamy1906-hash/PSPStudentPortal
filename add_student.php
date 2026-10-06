<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . "/controllers/StudentController.php";

$studentController = new StudentController();

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $ic = trim($_POST["ic"]);
    $marks = trim($_POST["marks"]);

    // Server-side validation
    if (empty($name) || empty($ic) || empty($marks)) {

        $error = "Please fill in all fields.";

    } elseif (!is_numeric($marks) || $marks < 0 || $marks > 100) {

        $error = "Marks must be between 0 and 100.";

    } else {

        $result = $studentController->addStudent(
            $name,
            $ic,
            $marks
        );

        if ($result) {

            $message = "Student added successfully.";

        } else {

            $error = "Unable to add student. IC may already exist.";

        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Add Student</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="container mt-5">

    <div class="card shadow p-4">

        <h2 class="mb-4">Add Student</h2>

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

                <label class="form-label">
                    Student Name
                </label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    required
                >

            </div>

            <div class="mb-3">

                <label class="form-label">
                    IC Number
                </label>

                <input
                    type="text"
                    name="ic"
                    class="form-control"
                    required
                >

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Marks
                </label>

                <input
                    type="number"
                    name="marks"
                    class="form-control"
                    min="0"
                    max="100"
                    step="0.01"
                    required
                >

            </div>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Add Student
            </button>

            <a
                href="students.php"
                class="btn btn-secondary"
            >
                Back
            </a>

        </form>

    </div>

</div>

</body>

</html>