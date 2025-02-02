<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $full_name = $_POST['full_name'];
    $program = $_POST['program'];
    $department = $_POST['department'];
    $valid_until = $_POST['valid_until'];
    $email = $_POST['email'];
    $number = $_POST['number'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Handle ID card picture upload
    $id_card_picture_data = null;
    if (isset($_FILES['id_card_picture']) && $_FILES['id_card_picture']['error'] === UPLOAD_ERR_OK) {
        $id_card_picture = $_FILES['id_card_picture']['tmp_name'];

        // Validate file size (limit to 1MB)
        if ($_FILES['id_card_picture']['size'] > 1 * 1024 * 1024) { // 1MB
            $error_message = "File size must be less than 1MB.";
        }

        // Validate MIME type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($id_card_picture);
        if (!in_array($file_type, $allowed_types)) {
            $error_message = "Invalid image format. Please upload JPEG, PNG, or GIF images.";
        }

        // Read the file content
        if (!isset($error_message)) {
            $id_card_picture_data = file_get_contents($id_card_picture);
        }
    } else {
        $error_message = "Please upload your ID card picture.";
    }

    // Check if email or student ID already exists
    $sql_check = "SELECT * FROM users WHERE email = ? OR student_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $email, $student_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $error_message = "The email or student ID is already taken.";
    } elseif (!isset($error_message)) {
        // Insert new user (including the ID card image as a BLOB)
        $sql = "INSERT INTO users (student_id, full_name, program, department, valid_until, email, number, password, id_card_picture) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssss", $student_id, $full_name, $program, $department, $valid_until, $email, $number, $password, $id_card_picture_data);

        try {
            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $error_message = "An unexpected error occurred.";
            }
        } catch (mysqli_sql_exception $e) {
            if (strpos($e->getMessage(), 'max_allowed_packet') !== false) {
                $error_message = "File size too large. Please upload a smaller file.";
            } else {
                $error_message = "An unexpected error occurred: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Register</h2>
        <form method="POST" action="" class="text-center" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="text" class="form-control w-50 mx-auto" name="student_id" placeholder="Student ID" required>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control w-50 mx-auto" name="full_name" placeholder="Full Name" required>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control w-50 mx-auto" name="program" placeholder="Program" required>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control w-50 mx-auto" name="department" placeholder="Department" required>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control w-50 mx-auto" name="valid_until" placeholder="Valid Until (e.g., Spring 2025)" required>
            </div>
            <div class="mb-3">
                <input type="email" class="form-control w-50 mx-auto" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control w-50 mx-auto" name="number" placeholder="Phone Number" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control w-50 mx-auto" name="password" placeholder="Password" required>
            </div>
            <!-- File upload for ID card picture -->
            <div class="mb-3">
                <input type="file" class="form-control w-50 mx-auto" name="id_card_picture" accept="image/*" required>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary mx-2">Register</button>
                <a href="login.php" class="btn btn-secondary mx-2">Login</a>
            </div>
        </form>

        <?php
        if (isset($error_message)) {
            echo "<div class='alert alert-danger mt-3'>$error_message</div>";
        }
        ?>
    </div>
</body>

</html>
