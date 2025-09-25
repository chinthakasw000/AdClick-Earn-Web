<?php
session_start();
require_once 'db_connect.php';

if (isset($_POST['verify_code'])) {
    $secret_code = trim($_POST['secret_code']);
    
    // Check if the entered code exists in the database and is not used
    $stmt = $pdo->prepare("SELECT * FROM secret_codes WHERE code = ? AND used = 0");
    $stmt->execute([$secret_code]);
    $code = $stmt->fetch();

    if ($code) {
        // Begin transaction
        $pdo->beginTransaction();
        try {
            // Add 50 coins to user
            $stmt = $pdo->prepare("UPDATE users SET coins = coins + 50 WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);

            // Mark the code as used
            $stmt = $pdo->prepare("UPDATE secret_codes SET used = 1 WHERE id = ?");
            $stmt->execute([$code['id']]);

            $pdo->commit();
            $response = ['success' => true, 'message' => 'Success! 50 coins added to your account.'];
        } catch (Exception $e) {
            $pdo->rollBack();
            $response = ['success' => false, 'message' => 'An error occurred while processing the code.'];
        }
    } else {
        $response = ['success' => false, 'message' => 'Invalid or already used code. Please try again.'];
    }
    
    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Enter Secret Code</title>
</head>

<body>

  <div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="max-width: 500px; margin: auto;">
      <div class="card-body">
        <h4 class="card-title fw-bold text-primary text-center mb-4">🔐 Secret Code</h4>

        <div id="alert-container"></div>

        <!-- Secret Code Input -->
        <div class="mb-3">
          <label for="secretCode" class="form-label fw-bold text-secondary">Enter the Secret Code</label>
          <input type="text" class="form-control" id="secretCode" placeholder="Enter Code">
        </div>

        <!-- Submit and Search Buttons -->
        <div class="d-grid gap-2">
          <button class="btn btn-success" id="submitCode">✅ Submit Code</button>
          <a href="https://www.google.com/search?q=secret+codes" target="_blank" class="btn btn-primary">
            🔍 Search Secret Codes on Google
          </a>
        </div>
      </div>
    </div>
  </div>

  
</body>

</html>