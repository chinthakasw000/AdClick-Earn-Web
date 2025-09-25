<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require_once 'db_connect.php';

// Fetch user data
$stmt = $pdo->prepare("SELECT email, coins FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Fetch sent gift data, ordered by created_at descending
$stmt = $pdo->prepare("SELECT recipient_email, amount, created_at FROM gifts WHERE sender_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$gifts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Gift</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #097969;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
        }
        .container {
            max-width: 1200px;
        }
        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-success {
            background-color: #097969;
            border-color: #097969;
        }
        .btn-success:hover {
            background-color: #065f4a;
            border-color: #065f4a;
        }
        .coin-count-container {
            text-align: center;
            margin-top: 10px;
        }
        .coin-count {
            font-size: 1.5rem;
            font-weight: bold;
            color: #097969;
        }
        .sent-gifts-container {
            max-height: 400px;
            overflow-y: auto;
            -ms-overflow-style: none;  /* Internet Explorer 10+ */
            scrollbar-width: none;  /* Firefox */
        }
        .sent-gifts-container::-webkit-scrollbar { 
            display: none;  /* Safari and Chrome */
        }
        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
            }
            .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Coin Pay Lanka</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sendGift.php">Send Gift</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="groupChat.php">Group Chat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="requestPayment.php">Payment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="userProfile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="aboutUs.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="coin-count-container">
        <span class="coin-count">Coins: <?php echo number_format($user['coins']); ?></span>
    </div>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="form-container">
                    <h2 class="text-center">Send Gift</h2>
                    <div id="alert-container"></div>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>
                    <form action="processGift.php" method="POST" class="mt-4" id="sendGiftForm">
                        <div class="form-group">
                            <label for="email">Recipient Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-success mt-4 w-100">Send Gift</button>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mt-5 mt-md-0 sent-gifts-container">
                    <h3 class="text-center">Sent Gifts</h3>
                    <table class="table table-striped mt-3">
                        <thead>
                            <tr>
                                <th>Recipient Email</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($gifts as $gift): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($gift['recipient_email']); ?></td>
                                    <td><?php echo number_format($gift['amount']); ?></td>
                                    <td><?php echo $gift['created_at']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('sendGiftForm').addEventListener('submit', function(event) {
            const emailInput = document.getElementById('email');
            const senderEmail = '<?php echo $user['email']; ?>';
            if (emailInput.value === senderEmail) {
                event.preventDefault();
                const alertContainer = document.getElementById('alert-container');
                alertContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        You cannot send a gift to your own email.
                        <button type="button" class="btn-close d-none" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
            }
        });
    </script>
</body>
</html>
