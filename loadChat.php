<?php
session_start();
require_once 'db_connect.php';

// Fetch the latest 10 messages
$stmt = $pdo->prepare("SELECT chat_messages.id, chat_messages.message, users.username, chat_messages.created_at, chat_messages.user_id FROM chat_messages JOIN users ON chat_messages.user_id = users.id ORDER BY chat_messages.created_at DESC LIMIT 20");
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Reverse the order to display the oldest message first
$messages = array_reverse($messages);

// Display the messages
foreach ($messages as $message) {
    $isCurrentUser = $message['user_id'] == $_SESSION['user_id'];
    $messageClass = $isCurrentUser ? 'current-user' : 'other-user';
    echo '<div class="chat-message ' . $messageClass . '"><span class="username">' . htmlspecialchars($message['username']) . ':</span> ' . htmlspecialchars($message['message']) . ' <span class="timestamp">' . $message['created_at'] . '</span></div>';
}

// Delete older messages if there are more than 10
$stmt = $pdo->prepare("DELETE FROM chat_messages WHERE id NOT IN (SELECT id FROM (SELECT id FROM chat_messages ORDER BY created_at DESC LIMIT 20) as temp)");
$stmt->execute();
?>