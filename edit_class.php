<?php
$conn = new mysqli('localhost', 'root', '', 'school_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $class_id = intval($_GET['id']);
    $sql = "SELECT * FROM classes WHERE class_id = $class_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $class = $result->fetch_assoc();
    } else {
        echo "No class found.";
        exit;
    }
} else {
    echo "No class ID provided.";
    exit;
}
?>


<h1>Edit Class</h1>
<form method="POST" action="edit_class.php?id=<?php echo $class_id; ?>">
    <input type="text" name="name" value="<?php echo $class['name']; ?>" required>
    <button type="submit" name="update_class">Update Class</button>
</form>


<?php
if (isset($_POST['update_class'])) {
    $name = $conn->real_escape_string($_POST['name']);
    
    $sql = "UPDATE classes SET name = '$name' WHERE class_id = $class_id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: classes.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
