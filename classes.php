<?php
$conn = new mysqli('localhost', 'root', '', 'school_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php
$sql = "SELECT * FROM classes";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/classes.css">
</head>

<h1>Manage Classes</h1>
<table border="1">
    <tr>
        <th>Class ID</th>
        <th>Class Name</th>
        <th>Created At</th>
        <th>Actions</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['class_id']; ?></td>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['created_at']; ?></td>
        <td>
            <a href="edit_class.php?id=<?php echo $row['class_id']; ?>">Edit</a> |
            <a href="delete_class.php?id=<?php echo $row['class_id']; ?>">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>


<h2>Add New Class</h2>
<form method="POST" action="classes.php">
    <input type="text" name="name" placeholder="Class Name" required>
    <button type="submit" name="add_class">Add Class</button>
</form>


<?php
if (isset($_POST['add_class'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $created_at = date('Y-m-d H:i:s');
    
    $sql = "INSERT INTO classes (name, created_at) VALUES ('$name', '$created_at')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: classes.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>


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
