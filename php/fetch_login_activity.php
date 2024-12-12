<?php
// Include database connection file
include 'db.php';

// Query to get the login activity logs
$sql = "SELECT la.id, la.user_id, u.username, la.login_time, la.ip_address, la.user_agent, la.status
        FROM login_activity la
        JOIN users u ON la.user_id = u.id
        ORDER BY la.login_time DESC";

$result = $conn->query($sql);

$logs = array();

// Fetch the result as an associative array and add it to $logs
while($row = $result->fetch_assoc()) {
    $logs[] = $row;
}

// Return the logs as JSON
header('Content-Type: application/json');
echo json_encode($logs);
?>
