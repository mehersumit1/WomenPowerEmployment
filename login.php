<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
$servername = "localhost";
$username = "root";
$password = ""; // Empty by default for XAMPP
$dbname = "ruralgirl"; // Make sure this DB exists

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form input safely
$user = $_POST['username'] ?? '';
$pass = $_POST['password'] ?? '';

// Check for empty input
if (empty($user) || empty($pass)) {
    echo "empty_fields";
    exit();
}

// Check if username already exists
$checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$checkStmt->bind_param("s", $user);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo "user_exists";
    $checkStmt->close();
    exit();
}
$checkStmt->close();

// Hash the password before saving
$hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

// Insert new user into database
$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $user, $hashedPassword);

if ($stmt->execute()) {
    echo "registered";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
