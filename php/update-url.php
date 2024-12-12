<?php
header('Content-Type: application/json');
// Include your database connection
require 'db.php'; // This file should handle your MySQL connection

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the short code and new original URL from the POST request
    $short_code = $_POST['short_code'] ?? null;
    $original_url = $_POST['original_url'] ?? null;
    $status = $_POST['status'] ?? null;

    // Validate inputs
    if ($short_code && $original_url && $status === 'update') {
        // Prepare the SQL query to update the original URL in the database
        $stmt = $conn->prepare("UPDATE urls SET original_url = ? WHERE short_code = ?");
        $stmt->bind_param('ss', $original_url, $short_code);

        // Execute the query and check if it was successful
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update the URL.']);
        }

        
    }elseif($short_code && $status === 'delete'){
        $stmt = $conn->prepare("DELETE FROM urls WHERE short_code = ?");
        $stmt->bind_param('s',$short_code);

        // Execute the query and check if it was successful
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete the URL.']);
        }

    }
    else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

// Close the database connection
$stmt->close();
$conn->close();
?>
