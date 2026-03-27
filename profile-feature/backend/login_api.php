<?php
header('Content-Type: application/json');

require 'config.php';
require 'db_login.php'; // We'll create this next

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

$data = $_POST;

if (!isset($data['email']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Email and Password are required']);
    exit;
}

$email = trim($data['email']);
$password = trim($data['password']);

try {
    // Fetch user data from the database
    $storedHashedPassword = getUserPasswordByEmail($email);

    if ($storedHashedPassword) {
        // Verify the provided password against the stored hash
        if (password_verify($password, $storedHashedPassword)) {
            // Password is correct
            session_start();
            $_SESSION['logged_in_user'] = $email; // Store user info in session
            
            echo json_encode(['success' => true, 'message' => 'Login successful']);
        } else {
            // Password is incorrect
            http_response_code(401); // Unauthorized
            echo json_encode(['success' => false, 'message' => 'Incorrect password']);
        }
    } else {
        // No user found with that email
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'No user found with this email']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>