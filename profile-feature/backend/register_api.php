<?php
header('Content-Type: application/json');

require 'config.php';
require 'db_register.php'; // Use the new database file

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

$data = $_POST;

if (!isset($data['email']) || !isset($data['password']) || !isset($data['confirm_password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Email, Password, and Confirm Password are required']);
    exit;
}

$email = trim($data['email']);
$password = trim($data['password']);
$confirmPassword = trim($data['confirm_password']);

// Basic validation
if ($password !== $confirmPassword) {
    http_response_code(400);
    echo json_encode(['error' => 'Passwords do not match']);
    exit;
}

if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(['error' => 'Password must be at least 6 characters long']);
    exit;
}

try {
    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    insertUser($email, $hashedPassword);
    echo json_encode(['message' => 'Registration successful']);
} catch (PDOException $e) {
    // Check for duplicate entry error specifically
    if ($e->getCode() == 23000) { // SQLSTATE code for integrity constraint violation
        http_response_code(400);
        echo json_encode(['error' => 'A user with this email already exists.']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>