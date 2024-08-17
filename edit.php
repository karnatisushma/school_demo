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
$sql = "SELECT student.*, classes.name AS class_name
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

// Fetch all classes for the dropdown
$classResult = $conn->query("SELECT * FROM classes");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="css/edit.css">
</head>


<h1>Edit Student</h1>
<form action="edit.php?id=<?php echo $student_id; ?>" method="POST" enctype="multipart/form-data">
    <label for="name">Name:</label><br>
    <input type="text" name="name" id="name" value="<?php echo $student['name']; ?>" required><br>

    <label for="email">Email:</label><br>
    <input type="email" name="email" id="email" value="<?php echo $student['email']; ?>" required><br>

    <label for="address">Address:</label><br>
    <textarea name="address" id="address" required><?php echo $student['address']; ?></textarea><br>

    <label for="class_id">Class:</label><br>
    <select name="class_id" id="class_id" required>
        <?php while ($class = $classResult->fetch_assoc()) { ?>
            <option value="<?php echo $class['class_id']; ?>" 
                <?php if ($class['class_id'] == $student['class_id']) echo 'selected'; ?>>
                <?php echo $class['name']; ?>
            </option>
        <?php } ?>
        <option value="add_class">Add Class</option>
    </select><br>

    <label for="image">Image:</label><br>
    <input type="file" name="image" id="image" accept="image/jpeg, image/png"><br>
    <p>Current Image:</p>
    <img src="uploads/<?php echo $student['image']; ?>" width="100"><br><br>

    
    <a href="display.php">
    <button type="submit" name="submit">Update Student</button>
    </a>
</form>

<script>
document.getElementById('class_id').addEventListener('change', function() {
    var selectedValue = this.value;
    if (selectedValue === 'add_class') {
        window.location.href = 'classes.php';
    }
});
</script>


<?php
if (isset($_POST['submit'])) {
    // Get the form data
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address']);
    $class_id = intval($_POST['class_id']);
    $image = $_FILES['image'];

    // Validate the name is not empty
    if (empty($name)) {
        echo "Name cannot be empty.";
        exit;
    }

    // If a new image is uploaded, validate and upload it
    if ($image['name']) {
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $fileExtension = pathinfo($image['name'], PATHINFO_EXTENSION);
        if (!in_array($fileExtension, $allowedExtensions)) {
            echo "Invalid image format. Only JPG and PNG are allowed.";
            exit;
        }

        // Upload the new image
        $imageName = time() . '_' . basename($image['name']);
        $imagePath = 'uploads/' . $imageName;
        if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
            echo "Failed to upload image.";
            exit;
        }

        // Delete the old image from the server
        if (file_exists('uploads/' . $student['image'])) {
            unlink('uploads/' . $student['image']);
        }
    } else {
        // If no new image is uploaded, keep the old image name
        $imageName = $student['image'];
    }

    // Update the student data in the database
    $sql = "UPDATE student SET name='$name', email='$email', address='$address', class_id=$class_id, image='$imageName', created_at=NOW()
            WHERE id=$student_id";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the home page after successful update
        header("Location: display.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>



