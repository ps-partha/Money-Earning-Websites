<?php

include 'db.php';
session_start(); // Start the session

$response = array();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit;
}

$user_id = intval($_SESSION['user_id']); // Get user ID from session

// Generate a transaction ID
function generate_transaction_id($conn) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $transaction_id = substr(str_shuffle($characters), 0, 10);

    // Ensure the transaction ID is unique by checking the database
    $query = "SELECT * FROM payments_history WHERE transaction_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $transaction_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Recursively generate a new ID if it already exists
        return generate_transaction_id($conn);
    }

    return $transaction_id;
}

// Check if billing information exists
$query = "SELECT * FROM billing_info WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Billing info exists
    $billing_info = $result->fetch_assoc();
    $withdrawal_method = $billing_info['withdrawal_method'];
    $withdrawal_account = $billing_info['withdrawal_account'];
    
    // Set withdrawal amount and currency based on method
    if ($withdrawal_method == 'paypal' || $withdrawal_method == 'payoneer') {
        $amount = 10.00;
        $Currency = "USDT";
    } elseif ($withdrawal_method == 'binance') {
        $amount = 20.00;
        $Currency = "USDT";
    } elseif ($withdrawal_method == 'bkash') {
        $amount = 5.00; // Set amount in USD equivalent
        $Currency = "BDT"; // Use BDT for display purposes
        $convertedAmount = $amount * 85; // USD to BDT conversion rate
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Invalid withdrawal method.';
        echo json_encode($response);
        exit;
    }

    // Check user balance
    $query = "SELECT balance FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $current_balance = floatval($user['balance']);

        // Ensure the user has enough balance
        

        if ($amount <= $current_balance) { 
            // Generate a unique transaction ID
            $transaction_id = generate_transaction_id($conn);
            $status = 'Pending';
            $withdrawal_amount = ($withdrawal_method == 'bkash') ? $convertedAmount : $amount;
            // Insert into payments history
            $query = "INSERT INTO payments_history (user_id, amount, currency, payment_method, transaction_id, payment_status) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);

            if ($stmt) {
                $stmt->bind_param("idssss", $user_id, $withdrawal_amount, $Currency, $withdrawal_method, $transaction_id, $status);
                
                if ($stmt->execute()) {
                    // Update user balance
                    $query = "UPDATE users SET balance = balance - ? WHERE id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("di", $withdrawal_amount, $user_id);

                    if ($stmt->execute()) {
                        $response['status'] = 'success';
                        $response['message'] = 'Withdrawal request created successfully.';
                    } else {
                        $response['status'] = 'error';
                        $response['message'] = 'Failed to update user balance.';
                    }
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Failed to create withdrawal request.';
                }

                $stmt->close();
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Failed to prepare statement for withdrawal request.';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Insufficient balance for withdrawal.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'User balance not found.';
    }
    
} else {
    // Billing info is missing
    $response['status'] = 'error';
    $response['message'] = 'Please fill in your billing information before making a withdrawal request.';
}

$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);

?>
