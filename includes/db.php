<?php
$host = "localhost";
$db_name = "quiz_system";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $exception) {
    die("Database Connection Error: " . $exception->getMessage() . "<br>Please ensure you have imported 'database.sql' into phpMyAdmin.");
}
?>
