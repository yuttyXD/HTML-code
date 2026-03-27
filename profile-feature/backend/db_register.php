<?php
require 'config.php';

/**
 * Inserts a new user into the users table.
 * @param string $email The user's email.
 * @param string $hashedPassword The hashed password.
 * @throws PDOException If the database operation fails.
 */
function insertUser($email, $hashedPassword) {
    global $pdo;
    
    // SQL query to insert a new user
    $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([$email, $hashedPassword]);
}
?>