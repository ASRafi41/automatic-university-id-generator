<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;

        // Optionally, fetch and store the ID card picture in the session
        $id_card_picture = $user['id_card_picture']; // This is BLOB data (binary)
        $_SESSION['id_card_picture'] = base64_encode($id_card_picture);  // Store the image as base64 to display it in HTML

        // Redirect to the dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Login</h2>
    <form method="POST" action="" class="text-center">
        <div class="mb-3">
            <input type="email" class="form-control w-50 mx-auto" name="email" placeholder="Email" required>
        </div>
        <div class="mb-3">
            <input type="password" class="form-control w-50 mx-auto" name="password" placeholder="Password" required>
        </div>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-primary mx-2">Login</button>
            <a href="register.php" class="btn btn-secondary mx-2">Register</a>
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
