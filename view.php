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
$sql = "SELECT student.name, student.email, student.address, student.image, student.created_at, classes.name AS class_name
        FROM student
        JOIN classes ON student.class_id = classes.class_id
        WHERE student.id = $student_id";
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
    <title>View Student</title>
    <link rel="stylesheet" href="css/view.css">
</head>

<h1>View Student</h1>

<div class="student-card">
    <img src="uploads/<?php echo $student['image']; ?>" alt="Student Image">
    
    <p class="name"><?php echo $student['name']; ?></p>
    
    <p class="email"><?php echo $student['email']; ?></p>
    
    <p class="address"><strong>Address:</strong> <?php echo $student['address']; ?></p>

    <p class="class"><strong>Class:</strong> <?php echo $student['class_name']; ?></p>
    
    <p class="created_at"><strong>Created At:</strong> <?php echo $student['created_at']; ?></p>
    
    
</div>


<?php $conn->close(); ?>
