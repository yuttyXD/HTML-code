<?php
require 'config.php';

function insertProfile($email, $name) { // Function signature updated
    global $pdo;
    
    // SQL query updated to only insert email and name
    $sql = "INSERT INTO profiles (email, name) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([$email, $name]);
}
?>