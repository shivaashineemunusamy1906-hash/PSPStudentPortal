<?php

session_start();

require_once "config/database.php";
require_once "models/Student.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->connect();

$studentModel = new Student($db);

$id = $_GET["id"];

$student = $studentModel->getStudent($id);

if (!$student) {
    die("Student not found.");
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $ic = trim($_POST["ic"]);
    $marks = $_POST["marks"];

    if (empty($name) || empty($ic) || $marks === "") {

        $error = "All fields are required.";

    } elseif ($marks < 0 || $marks > 100) {

        $error = "Marks must be between 0 and 100.";

    } else {

        if ($studentModel->updateStudent(
            $id,
            $name,
            $ic,
            $marks
        )) {

            header("Location: students.php");
            exit();

        } else {

            $error = "Update failed.";
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Student</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-body">

            <h3>Edit Student</h3>

            <?php if ($error != "") { ?>

                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>

            <?php } ?>

            <form method="POST">

                <div class="mb-3">

                    <label>Name</label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="<?php echo htmlspecialchars($student["name"]); ?>"
                        required>

                </div>

                <div class="mb-3">

                    <label>IC</label>

                    <input
                        type="text"
                        name="ic"
                        class="form-control"
                        value="<?php echo htmlspecialchars($student["ic"]); ?>"
                        required>

                </div>

                <div class="mb-3">

                    <label>Marks</label>

                    <input
                        type="number"
                        name="marks"
                        class="form-control"
                        min="0"
                        max="100"
                        value="<?php echo $student["marks"]; ?>"
                        required>

                </div>

                <button
                    type="submit"
                    class="btn btn-warning">

                    Update Student

                </button>

                <a
                    href="students.php"
                    class="btn btn-secondary">

                    Cancel

                </a>

            </form>

        </div>

    </div>

</div>

</body>

</html>