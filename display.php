<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect!";
    exit();    
}

?>

<?php
$sql = "SELECT student.id, student.name, student.email, student.created_at, student.image, classes.name AS class_name 
        FROM student 
        JOIN classes ON student.class_id = classes.class_id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
    <link rel="stylesheet" href="css/display.css">
</head>

<h1>Student List</h1>

<table>
    <tr align="center">
        <th>Name</th>
        <th>Email</th>
        <th>Creation Date</th>
        <th>Class</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr align="center">
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['created_at']; ?></td>
            <td><?php echo $row['class_name']; ?></td>
            <td><img src="uploads/<?php echo $row['image']; ?>" width="100"></td>
            <td>
                <a href="view.php?id=<?php echo $row['id']; ?>" class="button view">View</a> 
                <a href="edit.php?id=<?php echo $row['id']; ?>" class="button edit">Edit</a> 
                <a href="delete.php?id=<?php echo $row['id']; ?>" class="button delete">Delete</a>
            </td>
        </tr>
    <?php } ?>
</table>
<br><br>

<div class="container">
<a href="create.php">
    <button class="adduser" type="button">Add User</button>
</a>
</div>



<?php $conn->close(); ?>
