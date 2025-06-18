<?php
// Set header for logging
header("Content-Type: application/json");

// Get the raw POST data from Safaricom
$data = file_get_contents("php://input");

// Decode JSON payload
$decoded = json_decode($data, true);

// OPTIONAL: Save to log file for testing
file_put_contents("mpesa_log.txt", print_r($decoded, true), FILE_APPEND);

// Send 200 OK response back to Safaricom
echo json_encode(["ResultCode" => 0, "ResultDesc" => "Callback received successfully"]);
?>
