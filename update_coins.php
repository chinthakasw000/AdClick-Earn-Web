<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

require_once 'db_connect.php';

$adId = $_POST['ad_id'] ?? null;
if (!$adId) {
    echo json_encode(['success' => false, 'message' => 'Ad ID is required']);
    exit();
}

// Fetch the ad details
$stmt = $pdo->prepare("SELECT coins FROM ads WHERE id = ?");
$stmt->execute([$adId]);
$ad = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ad) {
    echo json_encode(['success' => false, 'message' => 'Ad not found']);
    exit();
}

// Update the user's coins
$stmt = $pdo->prepare("UPDATE users SET coins = coins + ? WHERE id = ?");
$stmt->execute([$ad['coins'], $_SESSION['user_id']]);

// Fetch the updated coin count
$stmt = $pdo->prepare("SELECT coins FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'coins' => $user['coins'], 'earned_coins' => $ad['coins']]);
