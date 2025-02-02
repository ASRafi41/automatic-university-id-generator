<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            font-weight: bold;
            color: #343a40;
        }
        .btn-custom {
            width: 100%;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</h2>
        <img src="custom.jpeg" alt="User Avatar" class="rounded-circle my-3" width="120">
        
        <a href="id_card.php" class="btn btn-success btn-custom">
            <i class="fas fa-id-card"></i> Generate ID Card
        </a>
        <a href="edit_info.php" class="btn btn-warning btn-custom">
            <i class="fas fa-edit"></i> Edit Information
        </a>
        <a href="developerInfo.php" class="btn btn-primary btn-custom">
            <i class="fas fas fa-code"></i> Developer Information
        </a>
        <a href="logout.php" class="btn btn-danger btn-custom">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

</body>
</html>
