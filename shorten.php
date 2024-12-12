<?php
// Database configuration
require './php/db.php';

session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: log-in');
    exit();
}

// Function to generate a unique short code
function generateShortCode() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle($characters), 0, 6);
}

// Handle the POST request from jQuery
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the long URL from the form data
    $longUrl = trim($_POST['longUrl']);
    $userId = $_SESSION['user_id']; // Get the user ID from the session

    // Validate the URL format
    if (!filter_var($longUrl, FILTER_VALIDATE_URL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid URL']);
        exit();
    }

    // Check if the URL already exists for this user
    $query = "SELECT short_code FROM urls WHERE original_url = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $longUrl, $userId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // If the long URL exists, fetch the short code
        $stmt->bind_result($existingShortCode);
        $stmt->fetch();
        $shortCode = $existingShortCode;
    } else {
        // Generate a new short code and insert the long URL, short code, and user_id into the database
        $shortCode = generateShortCode();
        
        // Prepare an insert statement
        $query = "INSERT INTO urls (original_url, short_code, user_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $longUrl, $shortCode, $userId);

        // Execute the query and check if successful
        if ($stmt->execute()) {
            // Success: The URL has been shortened
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error']);
            exit();
        }
    }

    // Return the shortened URL
    $shortenedUrl = "https://url.skipthegames.tech/" . $shortCode;
    echo json_encode(['status' => 'success', 'shortenedUrl' => $shortenedUrl]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
