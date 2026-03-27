<?php
header('Content-Type: application/json');

require 'config.php';
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

// Use $_POST to get form data
$data = $_POST;

// Only check for email and name now
if (!isset($data['email']) || !isset($data['name'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Email and Password are required']);
    exit;
}

$email = trim($data['email']);
$name = trim($data['name']); // This will be the password in the form

try {
    // Call the function with only two arguments
    insertProfile($email, $name);
    echo json_encode(['message' => 'Profile created successfully']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>