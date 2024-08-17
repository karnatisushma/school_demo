<?php
$conn = new mysqli('localhost', 'root', '', 'school_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $class_id = intval($_GET['id']);
    
    // Delete the class
    $sql = "DELETE FROM classes WHERE class_id = $class_id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: classes.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "No class ID provided.";
    exit;
}
?>
