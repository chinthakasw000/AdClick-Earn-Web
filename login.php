<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header("Location: index.php?error=empty_fields");
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        if ($user['blocked']) {
            // User is blocked
            header("Location: index.php?error=user_blocked");
            exit();
        }

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // Debugging statement
            error_log("Login successful for user_id: " . $user['id'] . " with username: " . $user['username']);

            header("Location: home.php");
            exit();
        } else {
            // Debugging statement
            error_log("Login failed for email: " . $email . " with provided password: " . $password);

            header("Location: index.php?error=invalid_credentials");
            exit();
        }
    } else {
        // Debugging statement
        error_log("Login failed for email: " . $email . " with provided password: " . $password);

        header("Location: index.php?error=invalid_credentials");
        exit();
    }
}
?>