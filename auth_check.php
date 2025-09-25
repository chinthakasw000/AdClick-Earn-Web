<?php
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token']) && isset($_COOKIE['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND remember_token = ?");
    $stmt->execute([$_COOKIE['user_id'], $_COOKIE['remember_token']]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        // Debugging statement
        error_log("Remember token login successful for user_id: " . $user['id'] . " with username: " . $user['username']);
    } else {
        // Invalid remember token, clear cookies
        setcookie('remember_token', '', time() - 3600, '/');
        setcookie('user_id', '', time() - 3600, '/');
    }
}
?>