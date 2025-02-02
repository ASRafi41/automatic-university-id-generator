<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

// Check if the user has an image in the database
$image = '';
if (!empty($user['id_card_picture'])) {
    $image = 'data:image/jpeg;base64,' . base64_encode($user['id_card_picture']);
} 
else {
    $image = 'default-profile.jpg'; // Ensure this file exists in the same directory
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>ID Card</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
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

        .id-card-container {
            max-width: 400px;
            border: 2px solid #000;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            background: #fff;
            margin: 50px auto;
            text-align: center;
            position: relative;
        }

        .university-logo {
            width: 80px;
            margin-bottom: 10px;
        }

        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            overflow: hidden;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <a href="dashboard.php"><i class="fas fa-home"></i> Home</a>
        <a href="edit_info.php"><i class="fas fa-edit"></i> Edit Information</a>
        <a href="developerInfo.php"><i class="fas fa-code"></i> Developer Info</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="id-card-container" id="idCard">
        <img src="luLogo.png" alt="University Logo" class="university-logo">
        <h3>Leading University</h3>
        <h5>Sylhet</h5>
        <hr>

        <!-- User Image -->
        <img src="<?php echo $image; ?>" alt="User Image" class="profile-image">

        <div class="info">
            <p><strong>Student ID:</strong> <?php echo htmlspecialchars($user['student_id']); ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
            <p><strong>Program:</strong> <?php echo htmlspecialchars($user['program']); ?></p>
            <p><strong>Department:</strong> <?php echo htmlspecialchars($user['department']); ?></p>
            <p><strong>Valid Until:</strong> <?php echo htmlspecialchars($user['valid_until']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['number']); ?></p>
        </div>
    </div>

    <div class="download-btns text-center">
        <button onclick="downloadImage()" class="btn btn-primary">
            <i class="fas fa-image"></i> Download Image
        </button>
        <button onclick="downloadPDF()" class="btn btn-success">
            <i class="fas fa-file-pdf"></i> Download PDF
        </button>
    </div>

    <script>
        function downloadImage() {
            html2canvas(document.getElementById("idCard")).then(canvas => {
                let link = document.createElement("a");
                link.download = "id_card.png";
                link.href = canvas.toDataURL("image/png");
                link.click();
            });
        }

        function downloadPDF() {
            html2canvas(document.getElementById("idCard")).then(canvas => {
                const imgData = canvas.toDataURL("image/png");
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
                doc.addImage(imgData, "PNG", 10, 10, 190, 0);
                doc.save("id_card.pdf");
            });
        }
    </script>

</body>

</html>