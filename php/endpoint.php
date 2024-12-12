<?php
// db.php - Include your database connection file
include 'db.php';
session_start(); // Start the session

// Assuming user_id and username are stored in the session
$user_id = intval($_SESSION['user_id']);
$username = $_SESSION['username'];

// Query to get total views for day ranges 1-3, 4-9, 10-12, etc.
// Use COALESCE to ensure it returns 0 if there are no records
$sql = "
    SELECT 
        COALESCE(SUM(CASE WHEN DAY(u.created_at) BETWEEN 1 AND 3 THEN u.view_count ELSE 0 END), 0) AS view_counts_3,
        COALESCE(SUM(CASE WHEN DAY(u.created_at) BETWEEN 4 AND 6 THEN u.view_count ELSE 0 END), 0) AS view_counts_6,
        COALESCE(SUM(CASE WHEN DAY(u.created_at) BETWEEN 7 AND 9 THEN u.view_count ELSE 0 END), 0) AS view_counts_9,
        COALESCE(SUM(CASE WHEN DAY(u.created_at) BETWEEN 10 AND 12 THEN u.view_count ELSE 0 END), 0) AS view_counts_12,
        COALESCE(SUM(CASE WHEN DAY(u.created_at) BETWEEN 13 AND 15 THEN u.view_count ELSE 0 END), 0) AS view_counts_15,
        COALESCE(SUM(CASE WHEN DAY(u.created_at) BETWEEN 16 AND 18 THEN u.view_count ELSE 0 END), 0) AS view_counts_18,
        COALESCE(SUM(CASE WHEN DAY(u.created_at) BETWEEN 19 AND 21 THEN u.view_count ELSE 0 END), 0) AS view_counts_21,
        COALESCE(SUM(CASE WHEN DAY(u.created_at) BETWEEN 22 AND 24 THEN u.view_count ELSE 0 END), 0) AS view_counts_24,
        COALESCE(SUM(CASE WHEN DAY(u.created_at) BETWEEN 26 AND 27 THEN u.view_count ELSE 0 END), 0) AS view_counts_27,
        COALESCE(SUM(CASE WHEN DAY(u.created_at) BETWEEN 29 AND 30 THEN u.view_count ELSE 0 END), 0) AS view_counts_30,
        COALESCE(SUM(u.view_count), 0) AS total_view_count,
        COALESCE(us.balance, 0) AS total_balance
    FROM urls u
    JOIN users us ON u.user_id = us.id 
    WHERE u.created_at >= CURDATE() - INTERVAL 30 DAY
    AND us.id = ? AND us.username = ?"; // Filter by user_id and username

// Prepare the statement
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('Prepare failed: ' . $conn->error);
}

// Bind parameters to the query
$stmt->bind_param("is", $user_id, $username); // "i" for integer (user_id), "s" for string (username)

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Initialize the data array
$data = [];
if ($result->num_rows > 0) {
    // Fetch data for all ranges
    $data = $result->fetch_assoc();
} else {
    // If no records are found, return zeros for all values
    $data = [
        'view_counts_3' => 0,
        'view_counts_6' => 0,
        'view_counts_9' => 0,
        'view_counts_12' => 0,
        'view_counts_15' => 0,
        'view_counts_18' => 0,
        'view_counts_21' => 0,
        'view_counts_24' => 0,
        'view_counts_27' => 0,
        'view_counts_30' => 0,
        'total_view_count' => 0,
        'total_balance' => 0
    ];
}

// Return the data in JSON format
header('Content-Type: application/json');
echo json_encode($data);

// Close the statement and connection
$stmt->close();
$conn->close();
?>
