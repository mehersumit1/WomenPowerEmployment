<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to add products.");
}

$host = "localhost";
$dbname = "your_database_name";
$username = "your_db_user";
$password = "your_db_password";

$conn = new mysqli($host, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $seller_name = $_POST['seller_name'];
    $contact_info = $_POST['contact_info'];
    $user_id = $_SESSION['user_id'];

    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $image_path = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);

    $sql = "INSERT INTO products (user_id, name, description, category, price, image_path, seller_name, contact_info)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssdsss", $user_id, $name, $description, $category, $price, $image_path, $seller_name, $contact_info);

    if ($stmt->execute()) {
        echo "Product added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>
