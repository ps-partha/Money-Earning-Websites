<?php
session_start();
require './db.php';
header('Content-Type: application/json');
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = $_SESSION['user_id'];
    // Log out the specific session by clearing the session token and user agent in the database
    $stmt = $conn->prepare("UPDATE users SET session_token = NULL WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Session successfully logged out']);
        session_unset();
        session_destroy();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to log out session or session not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
