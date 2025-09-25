<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch the user's current coin count
$stmt = $pdo->prepare("SELECT coins FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = trim($_POST['amount']);
    $payment_method = trim($_POST['payment_method']);
    $details = trim($_POST['details']);

    // Basic validation
    if (empty($amount) || empty($payment_method) || empty($details)) {
        $error = 'Please fill in all fields.';
    } elseif (!is_numeric($amount) || $amount <= 0) {
        $error = 'Please enter a valid positive amount.';
    } elseif ($amount < 100) {
        $error = 'The minimum amount for a payment request is 100 coins.';
    } else {
        try {
            // Check if the user has enough coins
            if ($user['coins'] < $amount) {
                $error = 'You do not have enough coins to make this payment request.';
            } else {
                // Deduct the requested amount of coins from the user's account
                $newCoins = $user['coins'] - $amount;
                $stmt = $pdo->prepare("UPDATE users SET coins = ? WHERE id = ?");
                $stmt->execute([$newCoins, $_SESSION['user_id']]);

                // Insert the payment request into the database
                $stmt = $pdo->prepare("INSERT INTO payment_requests (user_id, amount, payment_method, details, status) VALUES (?, ?, ?, ?, 'Pending')");
                $stmt->execute([$_SESSION['user_id'], $amount, $payment_method, $details]);

                $success = 'Payment request submitted successfully.';
                // Update the user's coin count
                $user['coins'] = $newCoins;

                // Reload the page after 3 seconds
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'home.php';
                        }, 3);
                      </script>";
            }
        } catch (PDOException $e) {
            $error = 'Error submitting payment request. Please try again.';
        }
    }
}

// Fetch the user's payment requests
$stmt = $pdo->prepare("SELECT * FROM payment_requests WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$payment_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="row g-3">
                    <div class="col-6 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header text-center">
                                <h1>Request Payment</h1>
                            </div>
                            <div class="card-body">
                                <div class="mb-3 text-center">
                                    <h5>Current Coins: <span class="text-success"><?php echo number_format($user['coins']); ?></span></h5>
                                    <p class="text-muted">Minimum amount for a payment request is 100 coins.</p>
                                </div>
                                <?php if (isset($error)): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <?php echo $error; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>
                                <?php if (isset($success)): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <?php echo $success; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>
                                <form action="requestPayment.php" method="post">
                                    <div class="mb-3">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input type="number" class="form-control" id="amount" name="amount" min="100" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label">Payment Method</label>
                                        <select class="form-select" id="payment_method" name="payment_method" required>
                                            <option value="" disabled selected>Select Payment Method</option>
                                            <option value="Bank Transfer">Bank Transfer (fee: Rs.30)</option>
                                            <option value="Mobile Reload">Mobile Reload</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="details" class="form-label">Details</label>
                                        <textarea class="form-control" id="details" name="details" rows="3" required placeholder="Type here your Mobile Reload Number or Bank Account Details : - Bank Name, Branch, Account Number"></textarea>
                                        <p class="text-muted">Text count: <span id="text_count">0</span>/50</p>
                                        <script>
                                            const textarea = document.getElementById('details');
                                            const textCount = document.getElementById('text_count');
                                            textarea.addEventListener('input', function() {
                                                const textLength = textarea.value.length;
                                                textCount.textContent = textLength;
                                                if (textLength > 50) {
                                                    textarea.setCustomValidity('Text count should be less than 50');
                                                } else {
                                                    textarea.setCustomValidity('');
                                                }
                                            });
                                        </script>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">Submit Request</button>
                                    <a href="home.php" class="btn btn-secondary w-100 mt-3">Back to Home</a>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header text-center">
                                <h2>Your Payment Requests</h2>
                            </div>
                            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                <?php if (count($payment_requests) > 0): ?>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Amount</th>
                                                <th>Payment Method</th>
                                                <th>Details</th>
                                                <th>Status</th>
                                                <th>Requested At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($payment_requests as $request): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($request['amount']); ?></td>
                                                    <td><?php echo htmlspecialchars($request['payment_method']); ?></td>
                                                    <td><?php echo htmlspecialchars($request['details']); ?></td>
                                                    <td><?php echo htmlspecialchars($request['status']); ?></td>
                                                    <td><?php echo htmlspecialchars($request['created_at']); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <p class="text-center">You have not made any payment requests yet.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
        <script>

  // Block right-click context menu
  document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });

    // Block F12 and other developer tools shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C')) || (e.ctrlKey && e.key === 'U')) {
            e.preventDefault();
        }
    });

    // Block text selection and copying
    document.addEventListener('selectstart', function(e) {
        e.preventDefault();
    });

    document.addEventListener('copy', function(e) {
        e.preventDefault();
    });

        </script>
</body>
</html>