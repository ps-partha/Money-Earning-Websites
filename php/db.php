<?php
// Database configuration
$host = 'localhost';   // Replace with your database host (often 'localhost')
$user = '';        // Replace with your database username
$password =         // Replace with your database password
$database = ''; // Replace with your database name

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
