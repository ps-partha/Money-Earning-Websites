<?php
// Database configuration
require './db.php';
session_start();
header('Content-Type: application/json');
// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}


// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = sanitizeInput($_POST['usernameoremail']);
    $password = $_POST['password']; // Keep password in raw form for verification

    // Validate inputs
    if (empty($usernameOrEmail) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields']);
        exit();
    }

    // Prepare statement to check user credentials
    $query = "SELECT id, username, password, session_token, user_agent FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId, $username, $hashedPassword, $sessionToken, $storedUserAgent);
        $stmt->fetch();

        // Check if the user is already logged in
        if (!empty($sessionToken)) {
            // Verify user agent
            if ($storedUserAgent === $_SERVER['HTTP_USER_AGENT']) {
                // User is already logged in on this device
                $_SESSION['user_id'] = $userId;
                $_SESSION['username'] = $username;
                $_SESSION['session_token'] = $sessionToken;
                $_SESSION['is_logged_in'] = true;

                echo json_encode(['status' => 'success', 'message' => 'Already logged in']);
                exit();
            } else {
                // User is logged in from another device
                echo json_encode(['status' => 'error', 'message' => 'You are already logged in from another device.']);
                exit();
            }
        }

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            // Generate a new session token
            $newSessionToken = bin2hex(random_bytes(32));
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;
            $_SESSION['session_token'] = $newSessionToken;
            $_SESSION['is_logged_in'] = true;

            // Store user agent and session token in the database
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            $updateTokenStmt = $conn->prepare("UPDATE users SET session_token = ?, user_agent = ? WHERE id = ?");
            $updateTokenStmt->bind_param("ssi", $newSessionToken, $userAgent, $userId);
            $updateTokenStmt->execute();
            $updateTokenStmt->close();

            echo json_encode(['status' => 'success', 'message' => 'Login successful']);
            
            // Log successful login
        } else {
            // Log failed login attempt
            echo json_encode(['status' => 'error', 'message' => 'Invalid password']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$conn->close();
