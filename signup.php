<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $password = $_POST['password'];

    // Basic validation
    if (empty($full_name) || empty($username) || empty($email) || empty($mobile) || empty($password)) {
        header("Location: index.php?error=empty_fields");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?error=invalid_email");
        exit();
    }

    // Mobile number regex validation
    $mobile_regex = '/^[0]{1}[7]{1}[01245678]{1}[0-9]{7}$/';
    if (!preg_match($mobile_regex, $mobile)) {
        header("Location: index.php?error=invalid_mobile");
        exit();
    }

    try {
        // Check if the mobile number already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE mobile = ?");
        $stmt->execute([$mobile]);
        $mobileExists = $stmt->fetchColumn();

        if ($mobileExists) {
            header("Location: index.php?error=duplicate_mobile");
            exit();
        }

        // Insert the new user
        $stmt = $pdo->prepare("INSERT INTO users (full_name, username, email, mobile, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$full_name, $username, $email, $mobile, password_hash($password, PASSWORD_DEFAULT)]);
        
        header("Location: index.php?success=registered");
        exit();
    } catch(PDOException $e) {
        if($e->getCode() == 23000) {
            header("Location: index.php?error=duplicate_entry");
        } else {
            header("Location: index.php?error=registration_failed");
        }
        exit();
    }
}
?>