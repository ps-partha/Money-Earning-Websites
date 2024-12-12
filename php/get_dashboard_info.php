<?php
session_start();
require './db.php';
header('Content-Type: application/json');
// Function to handle query execution and data fetching
function fetchData($conn, $sql, $types, $params) {
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("Failed to prepare statement: " . $conn->error);
        return false;
    }
    $stmt->bind_param($types, ...$params);
    if (!$stmt->execute()) {
        error_log("Failed to execute statement: " . $stmt->error);
        return false;
    }
    return $stmt->get_result();
}

// Check if the user is logged in and authorized
if (!isset($_SESSION['is_logged_in']) || !isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    $response = [
        'status' => 'error',
        'message' => 'Unauthorized access. Please log in.'
    ];
    echo json_encode($response);
    exit;
}

// Get user ID from session (assuming user_id and referrer_id are the same)
$userId = $_SESSION['user_id'];

// Input validation to ensure userId is a valid integer
if (!filter_var($userId, FILTER_VALIDATE_INT)) {
    http_response_code(400); // Bad request
    $response = [
        'status' => 'error',
        'message' => 'Invalid user ID'
    ];
    echo json_encode($response);
    exit;
}

// Fetch URLs
$sqlUrls = "SELECT original_url, short_code, created_at FROM urls WHERE user_id = ?";
$resultUrls = fetchData($conn, $sqlUrls, "i", [$userId]);
$urls = [];
if ($resultUrls && $resultUrls->num_rows > 0) {
    while ($url = $resultUrls->fetch_assoc()) {
        $urls[] = [
            'original_url' => htmlspecialchars($url['original_url']),
            'short_code' => htmlspecialchars($url['short_code']),
            'created_at' => $url['created_at']
        ];
    }
}

// Fetch referred users
$sqlReferredUsers = "SELECT users.username, users.balance, users.country
                     FROM users
                     LEFT JOIN referrals ON users.id = referrals.referred_id
                     WHERE referrals.referrer_id = ?";
$resultReferredUsers = fetchData($conn, $sqlReferredUsers, "i", [$userId]);

$referredUsers = [];
$totalReferredUsers = 0;

if ($resultReferredUsers && $resultReferredUsers->num_rows > 0) {
    $totalReferredUsers = $resultReferredUsers->num_rows; // Count the total referred users
    while ($referredUser = $resultReferredUsers->fetch_assoc()) {
        $referredUsers[] = [
            'username' => htmlspecialchars($referredUser['username']),
            'balance' => $referredUser['balance'],
            'country' => htmlspecialchars($referredUser['country'])
        ];
    }
}


// Fetch referral code for the current user
$sqlReferralCode = "SELECT referral_code FROM users WHERE id = ?";
$resultReferralCode = fetchData($conn, $sqlReferralCode, "i", [$userId]);
$referralCode = null;
if ($resultReferralCode && $resultReferralCode->num_rows > 0) {
    $referralRow = $resultReferralCode->fetch_assoc();
    $referralCode = htmlspecialchars($referralRow['referral_code']);
}

// Fetch payment history
$sqlPayments = "SELECT amount, currency, payment_method, payment_status, created_at
                FROM payments_history
                WHERE user_id = ?";
$resultPayments = fetchData($conn, $sqlPayments, "i", [$userId]);
$payments = [];
if ($resultPayments && $resultPayments->num_rows > 0) {
    while ($payment = $resultPayments->fetch_assoc()) {
        $payments[] = [
            'amount' => $payment['amount'],
            'currency' => htmlspecialchars($payment['currency']),
            'payment_method' => htmlspecialchars($payment['payment_method']),
            'payment_status' => htmlspecialchars($payment['payment_status']),
            'created_at' => $payment['created_at']
        ];
    }
}

// Fetch total amounts for Pending and Approved statuses for a specific user
$sqlPayments = "SELECT 
                    SUM(CASE WHEN payment_status = 'Pending' THEN amount ELSE 0 END) AS total_pending,
                    SUM(CASE WHEN payment_status = 'Approved' THEN amount ELSE 0 END) AS total_approved
                FROM payments_history 
                WHERE user_id = ?";

$resultPayments = fetchData($conn, $sqlPayments, "i", [$userId]);

$totalPending = 0;
$totalApproved = 0;

if ($resultPayments && $resultPayments->num_rows > 0) {
    $paymentTotals = $resultPayments->fetch_assoc();
    $totalPending = $paymentTotals['total_pending']; // Total amount for Pending status
    $totalApproved = $paymentTotals['total_approved']; // Total amount for Approved status
}



// Combine all arrays and return as JSON response
$response = [
    'status' => 'success',
    'urls' => $urls,
    'referred_users' => $referredUsers,
    'payments' => $payments,
    'referral_code' => $referralCode,
    'total_referred_users' => $totalReferredUsers,
    'totalPending' => $totalPending,
    'totalApproved'=> $totalApproved    
];

// Set HTTP status code for success
http_response_code(200); // OK
echo json_encode($response);

// Close the database connection
$conn->close();
?>
