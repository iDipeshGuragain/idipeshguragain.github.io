<?php
// Allow requests only from a specific origin
header("Access-Control-Allow-Origin: https://idipeshguragain.github.io");
// Allow the following HTTP methods
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
// Allow the following headers in the request
header("Access-Control-Allow-Headers: Content-Type");
// Set the content type for the response
header("Content-Type: application/json");

// Check if it's a pre-flight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Process the actual request here

// For example, if you want to handle a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $jsonFilePath = 'data.json';
   
   // Get the existing JSON data
    $existingData = json_decode(file_get_contents($jsonFilePath), true) ?: [];

    // Get the new JSON data
    $newData = $_REQUEST;

    // Validate required fields
    $requiredFields = ['name', 'email', 'subject', 'message'];
    foreach ($requiredFields as $field) {
        if (empty($newData[$field])) {
            http_response_code(400);
            echo json_encode(['error' => "$field is required"]);
            exit;
        }
    }

    // Add new entry to existing data
    $existingData[] = $newData;

    // Write the updated data back to the JSON file
    file_put_contents($jsonFilePath, json_encode($existingData, JSON_PRETTY_PRINT));

    // Send email
    sendEmail($newData['email'], $newData['subject'], $newData['message']);

    // Send a JSON response, replace this with your actual response
    echo json_encode(['status' => 'OK']);
} else {
    // Handle other HTTP methods or provide an error response
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed']);
}

function sendEmail($from, $subject, $message)
{
    $headers = "From: ".$from . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    if(mail('idipeshguragain@gmail.com', $subject, $message, $headers)){};
    if(mail('helloprasanjitroy@gmail.com', $subject, $message, $headers)){};
}


?>
