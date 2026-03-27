<?php
require 'config.php';

/**
 * Retrieves the hashed password for a given email from the users table.
 * @param string $email The email of the user.
 * @return string|null The hashed password if found, otherwise null.
 * @throws PDOException If the database operation fails.
 */
function getUserPasswordByEmail($email) {
    global $pdo;
    
    $sql = "SELECT password FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        return $result['password']; // Return the hashed password
    }
    
    return null; // User not found
}
?>