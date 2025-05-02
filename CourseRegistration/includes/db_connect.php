<?php
$host = 'localhost';
$dbname = 'CourseRegistration';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function getRelativePath($targetPath) {
    $currentDir = dirname($_SERVER['PHP_SELF']);
    $basePath = dirname($currentDir);
    return rtrim($basePath, '/') . '/' . ltrim($targetPath, '/');
}
?>