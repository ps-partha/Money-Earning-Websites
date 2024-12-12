<?php
require './db.php'; // Ensure db.php contains your database connection code
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the URL ID from the POST request
    $url_id = $_POST['url_id'];

    // Prepare and execute the query
    $sql = "SELECT original_url FROM urls WHERE short_code = ?";
    $stmt = $conn->prepare($sql);

    // Bind the parameter and check for any errors
    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare the statement.']);
        exit();
    }

    $stmt->bind_param("s", $url_id); // "s" for string
    $stmt->execute();

    $result = $stmt->get_result();

    // Check if a URL was found and return it
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['status' => 'success', 'data' => $row['original_url']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No URL found for the given short code.']);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
