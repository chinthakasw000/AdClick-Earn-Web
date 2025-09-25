<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$full_name = $user['full_name'] ?? '';
$email = $user['email'] ?? '';
$mobile = $user['mobile'] ?? '';
$username = $user['username'] ?? '';
$coins = $user['coins'] ?? 0;

if (isset($_POST['update'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $username = trim($_POST['username']);
    
    if (!empty($password) && $password != $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, mobile = ?, username = ?, password = ? WHERE id = ?");
            $stmt->execute([$full_name, $email, $mobile, $username, $hashed_password, $_SESSION['user_id']]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, mobile = ?, username = ? WHERE id = ?");
            $stmt->execute([$full_name, $email, $mobile, $username, $_SESSION['user_id']]);
        }

        // Debugging statement
        error_log("Profile updated for user_id: " . $_SESSION['user_id'] . " with new email: " . $email);

        // Redirect to logout
        header("Location: logout.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #097969 !important;
        }
        .card {
            background-color: white;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h1>User Profile</h1>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="full_name" class="form-label">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>">
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="username" class="form-label">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-at"></i></span>
                                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="mobile" class="form-label">Mobile</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                        <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo htmlspecialchars($mobile); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password">
                                        <span class="input-group-text"><i class="bi bi-eye-slash" id="togglePassword"></i></span>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                        <span class="input-group-text"><i class="bi bi-eye-slash" id="toggleConfirmPassword"></i></span>
                                    </div>
                                </div>
                            </div>
                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $error; ?>
                                </div>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-success w-100" name="update">Update</button>
                            <a href="home.php" class="btn btn-secondary w-100 mt-3">
                                <i class="bi bi-arrow-left"></i> Back to Home
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        togglePassword.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                togglePassword.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                passwordInput.type = 'password';
                togglePassword.classList.replace('bi-eye', 'bi-eye-slash');
            }
        });
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordInput = document.getElementById('confirm_password');
        toggleConfirmPassword.addEventListener('click', function() {
            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                toggleConfirmPassword.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                confirmPasswordInput.type = 'password';
                toggleConfirmPassword.classList.replace('bi-eye', 'bi-eye-slash');
            }
        });

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