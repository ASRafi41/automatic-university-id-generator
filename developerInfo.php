<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developer Information</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Arial', sans-serif;
        }
        .container-content {
            margin: 100px auto;
            max-width: 750px;
            background: #fff;
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            border-left: 8px solid #343a40;
        }
        h2 {
            font-size: 32px;
            font-weight: bold;
            color: #343a40;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        p {
            font-size: 18px;
            color: #555;
            line-height: 1.8;
            font-weight: 500;
            margin-bottom: 15px;
        }
        .highlight {
            font-weight: bold;
            color: #007bff;
        }
        .developer-pic {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: contain; /* Adjusted to prevent stretching */
            border: 6px solid #343a40;
            margin-bottom: 30px;
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
    </style>
</head>
<body>

<div class="sidebar">
    <a href="dashboard.php"><i class="fas fa-home"></i> Home</a>
    <a href="id_card.php"><i class="fas fa-id-card"></i> Generate ID Card</a>
    <a href="edit_info.php"><i class="fas fa-edit"></i> Edit Information</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="container container-content">
    <img src="developerPic.jpg" alt="Developer Picture" class="developer-pic">
    <h2>Meet the Developer</h2>
    <p>Hello! I'm Abu Sufian Rafi, a passionate software developer currently pursuing my BSc in Computer Science & Engineering at Leading University, Sylhet.</p>
    <p>My journey into the world of programming has been fueled by curiosity and a relentless drive to create meaningful digital solutions.</p>
    <p>This project was inspired by an initial idea from my friend Md Mahmud Hossain Ferdous, and I've shaped it into reality.</p>
    <p>When I'm not coding, I enjoy exploring new technologies, solving complex problems, and continuously improving my skills.</p>
</div>

</body>
</html>
