<?php
// Database configuration
$host = 'localhost';
$dbname = 'url_shortener';
$username = 'root';
$password = '';

// Database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Get the short code from the URL
$shortCode = basename($_SERVER['REQUEST_URI']);
header("Location: http://localhost/url-shortener/".$shortCode);
// $query = "SELECT original_url FROM urls WHERE short_code = ?";
// $stmt = $conn->prepare($query);
// $stmt->bind_param("s", $shortCode);
// $stmt->execute();
// $stmt->bind_result($originalUrl);
// $stmt->fetch();

// if ($originalUrl) {
//     header("Location: " . $originalUrl);
//     exit();
// } else {
//     echo "URL not found.";
// }

