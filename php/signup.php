<?php
require './db.php'; // Include your database connection file
header('Content-Type: application/json');
$response = [];

// Function to generate referral code
function generateReferralCode() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle($characters), 0, 8);
}

// Function to generate a session token
function generateSessionToken() {
    return bin2hex(random_bytes(16)); // Generate a 32-character session token
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $referral_code = $_POST['referral']; // Referral code from POST request

    // Validate inputs
    if (empty($username) || empty($email) || empty($password)) {
        $response['status'] = 'error';
        $response['message'] = 'All fields are required.';
        echo json_encode($response);
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid email format.';
        echo json_encode($response);
        exit();
    }

    // Check if username or email already exists
    $sql = "SELECT id FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $response['status'] = 'error';
        $response['message'] = 'Username or email already exists.';
        $stmt->close(); // Close the statement
        echo json_encode($response);
        exit();
    }

    // Generate referral code and session token
    $newReferralCode = generateReferralCode();
    $newSessionToken = generateSessionToken();

    // Capture user's IP address
    $user_ip = $_SERVER['REMOTE_ADDR'];

    // Fetch the country based on IP using the IPInfo API
    $api_token = "YOUR_API_TOKEN"; // Replace with your ipinfo.io token
    $api_url = "https://ipinfo.io/{$user_ip}/json?token={$api_token}";

    $api_response = @file_get_contents($api_url); // Suppress error if request fails
    $details = $api_response ? json_decode($api_response, true) : [];
    $country = $details['country'] ?? 'other';

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if referral code was provided and exists
    $referrer_id = null; // Default to null if no referral code
    if ($referral_code != null) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE referral_code = ?");
        $stmt->bind_param("s", $referral_code);
        $stmt->execute();
        $stmt->bind_result($referrer_id);
        if (!$stmt->fetch()) {
            // If no referrer found, set referral ID to null
            $referrer_id = null;
        }
        $stmt->close();
        $id = intval($referrer_id);
    }

    // Insert the new user into the database with the referred_by field
    $sql = "INSERT INTO users (username, email, password, balance, country, referral_code, referred_by) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $balance = 0.00;
    
    // Adjusting the binding based on the types
    $stmt->bind_param("sssdssi", $username, $email, $hashedPassword,$balance, $country, $newReferralCode, $id);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;

        // Log referral in the referrals table if referral code was used
        if ($id != null) {
            $stmt = $conn->prepare("INSERT INTO referrals (referrer_id, referred_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $id, $user_id);
            $stmt->execute();
        }

        // Start a session and store session token
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['session_token'] = $newSessionToken;

        $response['status'] = 'success';
        $response['message'] = 'Signup successful!';
        http_response_code(201); // Created
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to sign up. Please try again.';
    }

    // Close the prepared statement
    $stmt->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method.';
    http_response_code(405); // Method Not Allowed
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
