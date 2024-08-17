<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'school_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch classes for the dropdown
$classResult = $conn->query("SELECT * FROM classes");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Student</title>
    <link rel="stylesheet" href="css/create.css">
</head>


<h1>Create Student</h1>
<form action="create.php" method="POST" enctype="multipart/form-data">
    <label for="name">Name:</label><br>
    <input type="text" name="name" id="name" required><br>

    <label for="email">Email:</label><br>
    <input type="email" name="email" id="email" required><br>

    <label for="address">Address:</label><br>
    <textarea name="address" id="address" required></textarea><br>

    <label for="class_id">Class:</label><br>
    <select name="class_id" id="class_id" required>
        <option value="">Select Class</option>
        <?php while ($class = $classResult->fetch_assoc()) { ?>
            <option value="<?php echo $class['class_id']; ?>"><?php echo $class['name']; ?></option>
        <?php } ?>
        <option value="add_class">Add Class</option>
    </select>
    

    <label for="image">Image:</label><br>
    <input type="file" name="image" id="image" accept="image/jpeg, image/png" required><br><br>

    <button type="submit" name="submit">Create Student</button>
</form>

<script>
document.getElementById('class_id').addEventListener('change', function() {
    var selectedValue = this.value;
    if (selectedValue === 'add_class') {
        window.location.href = 'classes.php';
    }
});
</script>
<?php $conn->close(); ?>

<?php
if (isset($_POST['submit'])) {
    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'school_db');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

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

    // Validate the image format (only jpg, png)
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $fileExtension = pathinfo($image['name'], PATHINFO_EXTENSION);
    if (!in_array($fileExtension, $allowedExtensions)) {
        echo "Invalid image format. Only JPG and PNG are allowed.";
        exit;
    }

    // Upload the image to the server
    $imageName = time() . '_' . basename($image['name']);
    $imagePath = 'uploads/' . $imageName;
    if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
        echo "Failed to upload image.";
        exit;
    }

    // Insert the student data into the database
    $sql = "INSERT INTO student (name, email, address, class_id, image, created_at)
            VALUES ('$name', '$email', '$address', $class_id, '$imageName', NOW())";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect to the home page after successful insertion
        header("Location: display.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>


