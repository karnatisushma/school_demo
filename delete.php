<?php
$conn = new mysqli('localhost', 'root', '', 'school_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
if (isset($_GET['id'])) {
    $student_id = intval($_GET['id']);
} else {
    echo "No student ID provided.";
    exit;
}
?>


<?php
$sql = "SELECT * FROM student WHERE id = $student_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    echo "No student found with ID $student_id.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Student</title>
    <link rel="stylesheet" href="css/delete.css">
</head>

<body>
    <div class="card">
        <h1>Delete Student</h1>
        <p>Are you sure you want to delete the student <strong><?php echo htmlspecialchars($student['name']); ?></strong>?</p>
        <form method="POST" action="delete.php?id=<?php echo urlencode($student_id); ?>">
            <div class="form-buttons">
                <button type="submit" name="confirm_delete">Yes, Delete</button>
                <a href="display.php">Cancel</a>
            </div>
        </form>
    </div>
</body>

<?php
if (isset($_POST['confirm_delete'])) {
    // Delete the student's image from the server
    if (file_exists('uploads/' . $student['image'])) {
        unlink('uploads/' . $student['image']);
    }

    // Delete the student from the database
    $sql = "DELETE FROM student WHERE id = $student_id";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the home page after successful deletion
        header("Location: display.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
