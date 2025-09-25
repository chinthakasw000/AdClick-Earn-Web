<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

require_once '../db_connect.php';

$coins = $_POST['coins'] ?? null;
if (!$coins) {
    echo json_encode(['success' => false, 'message' => 'Coin amount is required']);
    exit();
}

// Update the user's coins
$stmt = $pdo->prepare("UPDATE users SET coins = coins + ? WHERE id = ?");
$stmt->execute([$coins, $_SESSION['user_id']]);

// Fetch the updated coin count
$stmt = $pdo->prepare("SELECT coins FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'coins' => $user['coins'], 'earned_coins' => $coins]);