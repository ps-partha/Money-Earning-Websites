<?php
// Database configuration
require './db.php';
session_start();

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function generateReferralCode() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle($characters), 0, 10);
}

// Function to validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($_SESSION['user_id']);
    $payments_mathod = sanitizeInput($_POST['payments_mathod']);
    $Currency = sanitizeInput($_POST['Currency']);
    $amount = floatval(sanitizeInput($_POST['amount'])); // Ensure amount is a float
    $email = sanitizeInput($_POST['email']);
    
    // Validate email
    if (!validateEmail($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email address']);
        exit;
    }

    $transaction_id = generateReferralCode();
    $status = 'Pending';

    // Prepare statement to check user credentials
    $query = "INSERT INTO `payments_history`(`user_id`, `amount`, `currency`, `payment_method`, `transaction_id`, `payment_status`) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("idssss", $userId, $amount, $Currency, $payments_mathod, $transaction_id, $status);
        
        if ($stmt->execute()) {
            $query = "UPDATE `users` SET  balance = balance - ?  WHERE id =?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("di",$amount, $userId);
            if($stmt->execute()){
                echo json_encode(['status' => 'success']);
            } 
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to execute statement']);
        }
        
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
