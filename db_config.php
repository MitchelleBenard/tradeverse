<?php
$servername = "localhost"; // Host name
$username = "root"; // Database username (default in XAMPP is root)
$password = ""; // Database password (default in XAMPP is an empty string)
$dbname = "user_system"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
