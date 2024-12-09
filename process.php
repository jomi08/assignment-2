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
        // Validate and sanitize input data
        $fullName = filter_input(INPUT_POST, 'fullName', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
        $dob = filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING);
        $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);

        // Additional validation
        if (!$fullName || strlen($fullName) < 2) {
            throw new Exception('Please enter a valid name');
        }

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Please enter a valid email address');
        }

        if (!$phone || !preg_match('/^\+?\d{10,14}$/', preg_replace('/[- )(]/', '', $phone))) {
            throw new Exception('Please enter a valid phone number');
        }

        if (!$dob || !strtotime($dob)) {
            throw new Exception('Please enter a valid date of birth');
        }

        if (!$gender || !in_array($gender, ['male', 'female', 'other', 'prefer-not-to-say'])) {
            throw new Exception('Please select a valid gender');
        }

        if (!$address || strlen($address) < 10) {
            throw new Exception('Please enter a complete address');
        }

        // Format the date
        $formattedDate = date('F j, Y', strtotime($dob));

        // Format the response data with better HTML styling
        $formattedData = "
            <div style='background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>
                <h2 style='color: #4a5568; margin-bottom: 20px; text-align: center;'>Registration Successful!</h2>
                <div style='margin-bottom: 15px;'>
                    <strong style='color: #4a5568;'>Full Name:</strong>
                    <span style='color: #2d3748;'>{$fullName}</span>
                </div>
                <div style='margin-bottom: 15px;'>
                    <strong style='color: #4a5568;'>Email:</strong>
                    <span style='color: #2d3748;'>{$email}</span>
                </div>
                <div style='margin-bottom: 15px;'>
                    <strong style='color: #4a5568;'>Phone:</strong>
                    <span style='color: #2d3748;'>{$phone}</span>
                </div>
                <div style='margin-bottom: 15px;'>
                    <strong style='color: #4a5568;'>Date of Birth:</strong>
                    <span style='color: #2d3748;'>{$formattedDate}</span>
                </div>
                <div style='margin-bottom: 15px;'>
                    <strong style='color: #4a5568;'>Gender:</strong>
                    <span style='color: #2d3748;'>" . ucfirst($gender) . "</span>
                </div>
                <div style='margin-bottom: 15px;'>
                    <strong style='color: #4a5568;'>Address:</strong>
                    <span style='color: #2d3748;'>{$address}</span>
                </div>
            </div>";

        // Save data to a JSON file
        $data = [
            'fullName' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'dob' => $formattedDate,
            'gender' => $gender,
            'address' => $address
        ];

        // Load existing data
        $filePath = 'data.json';
        if (file_exists($filePath)) {
            $existingData = json_decode(file_get_contents($filePath), true);
        } else {
            $existingData = [];
        }

        // Append new data
        $existingData[] = $data;

        // Save back to JSON file
        file_put_contents($filePath, json_encode($existingData, JSON_PRETTY_PRINT));

        // Return success response
        echo json_encode([
            'success' => true,
            'message' => $formattedData
        ]);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // New endpoint to retrieve stored data
        $filePath = 'data.json';
        if (file_exists($filePath)) {
            $storedData = json_decode(file_get_contents($filePath), true);
            echo json_encode([
                'success' => true,
                'data' => $storedData
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No data found'
            ]);
        }
    } else {
        throw new Exception('Invalid request method. Only POST and GET requests are allowed.');
    }

} catch (Exception $e) {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
