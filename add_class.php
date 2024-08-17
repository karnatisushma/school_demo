<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'school_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $name = $conn->real_escape_string($_POST['name']);

    // Insert the new class into the database
    $sql = "INSERT INTO classes (name) VALUES ('$name')";

    if ($conn->query($sql) === TRUE) {
        echo "New class added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();

// Redirect back to the classes.php page
header("Location: classes.php");
exit;
?>
