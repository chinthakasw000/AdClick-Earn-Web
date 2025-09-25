<?php
session_start();
require_once 'db_connect.php';

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT coins FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    echo $user['coins'];
} else {
    echo 0;
}
?>