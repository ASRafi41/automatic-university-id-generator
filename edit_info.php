<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$success_message = '';  
$error_message = '';  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $user['student_id']; // Use the logged-in user's student ID
    $full_name = $_POST['full_name'];
    $program = $_POST['program'];
    $department = $_POST['department'];
    $valid_until = $_POST['valid_until'];
    $email = $_POST['email'];
    $number = $_POST['number'];

    $id_card_picture = null; // Initialize variable for image data

    // Check if a new image is uploaded
    if (isset($_FILES['id_card_picture']) && $_FILES['id_card_picture']['error'] === UPLOAD_ERR_OK) {
        $file_size = $_FILES['id_card_picture']['size'];

        // Ensure file size is within 1MB limit
        if ($file_size > 1024 * 1024) {
            $error_message = "File size must be less than 1MB.";
        } 
        else {
            $id_card_picture = file_get_contents($_FILES['id_card_picture']['tmp_name']);
        }
    }

    if (!$error_message) {
        // Prepare SQL query
        $sql = "UPDATE users 
                SET full_name = ?, program = ?, department = ?, valid_until = ?, 
                    email = ?, number = ?, id_card_picture = ? WHERE student_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters
            if ($id_card_picture) {
                // Bind binary data when a new image is uploaded
                $stmt->bind_param(
                    "ssssssbs", 
                    $full_name, $program, $department, $valid_until, 
                    $email, $number, $id_card_picture, $student_id
                );
                $stmt->send_long_data(6, $id_card_picture); // Send binary data
            } 
            else {
                // Keep the current image if no new image is uploaded
                $sql = "UPDATE users 
                        SET full_name = ?, program = ?, department = ?, valid_until = ?, 
                            email = ?, number = ? 
                        WHERE student_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param(
                    "sssssss", 
                    $full_name, $program, $department, $valid_until, 
                    $email, $number, $student_id
                );
            }

            try {
                if ($stmt->execute()) {
                    // Fetch updated user data to refresh session
                    $sql_fetch_user = "SELECT * FROM users WHERE student_id = ?";
                    $stmt_fetch_user = $conn->prepare($sql_fetch_user);
                    $stmt_fetch_user->bind_param("s", $student_id);
                    $stmt_fetch_user->execute();
                    $result = $stmt_fetch_user->get_result();

                    if ($result->num_rows === 1) {
                        $_SESSION['user'] = $result->fetch_assoc(); // Update session with new data
                        $success_message = "Your information has been successfully updated!";
                    } 
                    else {
                        $error_message = "Failed to refresh user data.";
                    }
                } else {
                    $error_message = "Failed to update information.";
                }
            } 
            catch (Exception $e) {
                $error_message = "An error occurred: " . $e->getMessage();
            }
        } 
        else {
            $error_message = "Database error: " . $conn->error;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Information</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .sidebar {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #343a40;
            padding: 10px 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            color: #fff;
            text-decoration: none;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: background-color 0.3s;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .sidebar a i {
            margin-right: 10px;
        }
        .container-content {
            margin-top: 70px;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 700px;
        }
        h2 {
            font-weight: bold;
            color: #343a40;
        }
        .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <a href="dashboard.php"><i class="fas fa-home"></i> Home</a>
    <a href="id_card.php"><i class="fas fa-edit"></i> Generate ID Card</a>
    <a href="developerInfo.php"><i class="fas fa-code"></i> Developer Info</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="container mt-5 container-content" id="content">
    <h2 class="text-center">Edit Information</h2>

    <?php if ($success_message) { echo "<div class='alert alert-success'>$success_message</div>"; } ?>
    <?php if ($error_message) { echo "<div class='alert alert-danger'>$error_message</div>"; } ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="student_id" class="form-label">Student ID</label>
            <input type="text" class="form-control" name="student_id" value="<?php echo htmlspecialchars($user['student_id']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="program" class="form-label">Program</label>
            <input type="text" class="form-control" name="program" value="<?php echo htmlspecialchars($user['program']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="department" class="form-label">Department</label>
            <input type="text" class="form-control" name="department" value="<?php echo htmlspecialchars($user['department']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="valid_until" class="form-label">Valid Until</label>
            <input type="text" class="form-control" name="valid_until" value="<?php echo htmlspecialchars($user['valid_until']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="number" class="form-label">Phone Number</label>
            <input type="text" class="form-control" name="number" value="<?php echo htmlspecialchars($user['number']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="id_card_picture" class="form-label">Upload New ID Card Picture (Max 1MB)</label>
            <input type="file" class="form-control" name="id_card_picture">
        </div>
        <button type="submit" class="btn btn-primary">Update Information</button>
    </form>
</div>

</body>
</html>
