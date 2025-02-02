<?php
$host = "127.0.0.1:3307"; 
$user = "root";      
$password = "";   
$dbname = "websessional";

// Create a connection to MySQL server
$conn = new mysqli($host, $user, $password);

// Check the connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Create the database if it doesn't exist
$sql_create_db = "CREATE DATABASE IF NOT EXISTS `$dbname`";
if (!$conn->query($sql_create_db)) {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Create the `users` table if it doesn't exist
$sql_create_table = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,        
    student_id VARCHAR(20) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,           
    program VARCHAR(100) NOT NULL,    
    department VARCHAR(100) NOT NULL,      
    valid_until VARCHAR(50) NOT NULL,         
    email VARCHAR(100) NOT NULL UNIQUE,      
    number VARCHAR(20) NOT NULL,             
    password VARCHAR(255) NOT NULL,         
    id_card_picture LONGBLOB NOT NULL,        
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
)";
if (!$conn->query($sql_create_table)) {
    die("Error creating table: " . $conn->error);
}

?>
