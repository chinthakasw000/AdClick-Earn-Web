<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = trim($_POST['message']);
    if (empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Message cannot be empty']);
        exit();
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO chat_messages (user_id, message) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $message]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
}
?>