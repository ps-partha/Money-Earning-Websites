<?php
require 'db.php'; // Include your database connection file
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data and sanitize input
    $user_id = intval($_SESSION['user_id']);
    $first_name = trim($_POST['First_Name']);
    $last_name = trim($_POST['Last_Name']);
    $address = trim($_POST['Address']);
    $city = trim($_POST['City']);
    $state = trim($_POST['State']);
    $country = trim($_POST['Country']);
    $zip = trim($_POST['ZIP']);
    $phone_number = trim($_POST['Phone_Number']);
    $withdrawal_method = trim($_POST['Withdrawal_Method']);
    $withdrawal_account = trim($_POST['Withdrawal_Account']);

    // Check if billing info already exists
    $stmt = $conn->prepare("SELECT id FROM billing_info WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Billing info exists, update it
        $stmt = $conn->prepare("UPDATE billing_info SET first_name = ?, last_name = ?, address = ?, city = ?, state = ?, country = ?, zip = ?, phone_number = ?, withdrawal_method = ?, withdrawal_account = ? WHERE user_id = ?");
        $stmt->bind_param("sssssssssss", $first_name, $last_name, $address, $city, $state, $country, $zip, $phone_number, $withdrawal_method, $withdrawal_account, $user_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Billing info updated successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating billing info: ' . $stmt->error]);
        }
    } else {
        // Billing info does not exist, insert new
        $stmt = $conn->prepare("INSERT INTO billing_info (user_id, first_name, last_name, address, city, state, country, zip, phone_number, withdrawal_method, withdrawal_account) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssss", $user_id, $first_name, $last_name, $address, $city, $state, $country, $zip, $phone_number, $withdrawal_method, $withdrawal_account);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Billing info saved successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error saving billing info: ' . $stmt->error]);
        }
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
