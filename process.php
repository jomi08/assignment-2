<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Check if the request is POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'fullName' => $_POST['fullName'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'dob' => $_POST['dob'],
            'gender' => $_POST['gender'],
            'address' => $_POST['address']
        ];

        // Store data in JSON format
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents('data.json', $jsonData);

        // Return a simple success message
        echo json_encode(['success' => true, 'message' => 'Successfully run or submitted']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    }

} catch (Exception $e) {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
