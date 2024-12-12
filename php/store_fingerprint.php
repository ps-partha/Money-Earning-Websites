<?php
header('Content-Type: application/json');
require './db.php'; // Ensure db.php contains your database connection code

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['fingerprint']) || !isset($_POST['short_code'])) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request. Missing fingerprint or code.']);
        exit;
    }

    $fingerprint = $_POST['fingerprint'];
    $short_code = $_POST['short_code'];
    $userIp = $_SERVER['REMOTE_ADDR']; // Get user IP address
    $userAgent = $_SERVER['HTTP_USER_AGENT']; // Get the user agent

    // Check if the fingerprint and user agent exist in the user_device table
    $stmt = $conn->prepare("SELECT id FROM user_device WHERE fingerprint = ? AND user_agent = ?");
    if ($stmt === false) {
        die(json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]));
    }
    $stmt->bind_param('ss', $fingerprint, $userAgent);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Get the device ID if it exists
        $device_row = $result->fetch_assoc();
        $device_id = $device_row['id'];
    } else {
        // Insert new device information if not found
        $stmt = $conn->prepare("INSERT INTO user_device (fingerprint, user_agent) VALUES (?, ?)");
        if ($stmt === false) {
            die(json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]));
        }
        $stmt->bind_param('ss', $fingerprint, $userAgent);
        $stmt->execute();
        $device_id = $stmt->insert_id;
    }

    // Check if this visit already exists for the given short code
    $stmt = $conn->prepare("SELECT * FROM user_visits WHERE device_id = ? AND user_ip = ? AND short_code = ?");
    if ($stmt === false) {
        die(json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]));
    }
    $stmt->bind_param("iss", $device_id, $userIp, $short_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'You have already visited this link.']);
    } else {
        // Check if short code exists in the URLs table
        $stmt = $conn->prepare("SELECT user_id FROM urls WHERE short_code = ?");
        if ($stmt === false) {
            die(json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]));
        }
        $stmt->bind_param('s', $short_code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_id = intval($row['user_id']);

            // Fetch user country using IPinfo API
            $api_token = "039e183ed6cf5d"; // Replace with your ipinfo.io token
            $api_url = "https://ipinfo.io/{$userIp}/json?token={$api_token}";

            // Use curl to handle API request
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code !== 200 || $response === false) {
                $country = 'other'; // Fallback if the API fails
            } else {
                $details = json_decode($response, true);
                $country = $details['country'] ?? 'other';
            }

            // Define country codes and corresponding balances
            $countries = [
                "US" => 6.00,
                "GB" => 5.00,
                "NL" => 4.75,
                "CA" => 4.50,
                "AE" => 4.50,
                "AU" => 4.00,
                "DE" => 3.50,
                "FR" => 3.25,
                "SG" => 3.25,
                "BE" => 3.00,
                "SA" => 2.75,
                "NO" => 2.50,
                "ES" => 2.00,
                "IN" => 1.30,
                "other" => 1.50
            ];

            // Check if country code is valid and update the balance
            $balance_amount = $countries[$country] ?? $countries['other'];
            $add_balance = $balance_amount / 1000;

            // Update user's balance
            $stmt = $conn->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
            if ($stmt === false) {
                die(json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]));
            }
            $stmt->bind_param('di', $add_balance, $user_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                // Store user visit data with device_id reference
                $stmt = $conn->prepare("INSERT INTO user_visits (user_ip, device_id, short_code) VALUES (?, ?, ?)");
                if ($stmt === false) {
                    die(json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]));
                }
                $stmt->bind_param("sis", $userIp, $device_id, $short_code);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    // Update view count for the short code
                    $stmt = $conn->prepare("UPDATE urls SET view_count = view_count + 1 WHERE short_code = ?");
                    if ($stmt === false) {
                        die(json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]));
                    }
                    $stmt->bind_param("s", $short_code);
                    $stmt->execute();

                    echo json_encode(["status" => "success", 'message' => 'Visit recorded and balance updated.']);
                } else {
                    echo json_encode(["status" => "error", 'message' => 'Error storing visit: ' . $stmt->error]);
                }
            } else {
                echo json_encode(["status" => "error", 'message' => 'Error updating balance: ' . $stmt->error]);
            }
        } else {
            echo json_encode(["status" => "error", 'message' => 'Short code not found.']);
        }
    }

    if ($stmt) {
        $stmt->close();
    }
}

$conn->close();
?>
