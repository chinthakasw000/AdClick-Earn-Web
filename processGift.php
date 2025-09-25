<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$sender_id = $_SESSION['user_id'];
$recipient_email = $_POST['email'];
$amount = $_POST['amount'];

// Validate amount
if ($amount <= 0) {
    $_SESSION['error'] = "Invalid amount.";
    header("Location: sendGift.php");
    exit();
}

// Fetch sender's coin balance
$stmt = $pdo->prepare("SELECT coins FROM users WHERE id = ?");
$stmt->execute([$sender_id]);
$sender = $stmt->fetch();

if ($sender['coins'] < $amount) {
    $_SESSION['error'] = "You do not have enough coins to send.";
    header("Location: sendGift.php");
    exit();
}

// Check if recipient exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$recipient_email]);
$recipient = $stmt->fetch();

if (!$recipient) {
    $_SESSION['error'] = "Recipient not found.";
    header("Location: sendGift.php");
    exit();
}

// Begin transaction
$pdo->beginTransaction();

try {
    // Insert gift transaction
    $stmt = $pdo->prepare("INSERT INTO gifts (sender_id, recipient_email, amount) VALUES (?, ?, ?)");
    $stmt->execute([$sender_id, $recipient_email, $amount]);

    // Update sender's coin balance
    $stmt = $pdo->prepare("UPDATE users SET coins = coins - ? WHERE id = ?");
    $stmt->execute([$amount, $sender_id]);

    // Ensure sender's coin balance is not negative
    $stmt = $pdo->prepare("SELECT coins FROM users WHERE id = ?");
    $stmt->execute([$sender_id]);
    $sender = $stmt->fetch();
    if ($sender['coins'] < 0) {
        throw new Exception("Insufficient coins after transaction.");
    }

    // Update recipient's coin balance
    $stmt = $pdo->prepare("UPDATE users SET coins = coins + ? WHERE email = ?");
    $stmt->execute([$amount, $recipient_email]);

    // Commit transaction
    $pdo->commit();

    $_SESSION['success'] = "Gift sent successfully.";
} catch (Exception $e) {
    // Rollback transaction
    $pdo->rollBack();
    $_SESSION['error'] = "Transaction failed: " . $e->getMessage();
}

header("Location: sendGift.php");
exit();
?>
