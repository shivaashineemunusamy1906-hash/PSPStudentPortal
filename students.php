<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . "/controllers/StudentController.php";

$studentController = new StudentController();

$students = $studentController->getStudents();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Grade Management</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>Student Grade Management</h2>

        <a href="add_student.php" class="btn btn-primary">
            Add Student
        </a>

    </div>

    <table class="table table-bordered table-striped">

        <thead class="table-dark">

            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>IC</th>
                <th>Marks</th>
                <th>Action</th>
            </tr>

        </thead>

        <tbody>

        <?php while ($student = $students->fetch_assoc()) { ?>

            <tr>

                <td>
                    <?php echo $student["id"]; ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($student["name"]); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($student["ic"]); ?>
                </td>

                <td>
                    <?php echo $student["marks"]; ?>
                </td>

                <td>

                    <a
                        href="edit_student.php?id=<?php echo $student["id"]; ?>"
                        class="btn btn-warning btn-sm"
                    >
                        Edit
                    </a>

                    <a
                        href="delete_student.php?id=<?php echo $student["id"]; ?>"
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Are you sure you want to delete this student?');"
                    >
                        Delete
                    </a>

                </td>

            </tr>

        <?php } ?>

        </tbody>

    </table>

    <a href="profile.php" class="btn btn-secondary">
        Back to Profile
    </a>

</div>

</body>
</html>