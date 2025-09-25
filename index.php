<?php
session_start();
require_once 'db_connect.php';
require_once 'auth_check.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ad Click - Login/Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <div class="container-fluid vh-100 d-flex align-items-center justify-content-center" 
         style="background: linear-gradient(135deg, #097969 0%, #064e3b 100%);">
        <div class="row justify-content-center w-100">
            <div class="col-md-5">
                <!-- Notifications Section -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                        <?php
                        switch($_GET['error']) {
                            case 'empty_fields':
                                echo '<i class="bi bi-exclamation-triangle-fill me-2"></i>Please fill in all fields.';
                                break;
                            case 'invalid_credentials':
                                echo '<i class="bi bi-exclamation-triangle-fill me-2"></i>Invalid email or password.';
                                break;
                            case 'user_blocked':
                                echo '<i class="bi bi-exclamation-triangle-fill me-2"></i>Your account has been blocked. Please contact support.';
                                break;
                            default:
                                echo '<i class="bi bi-exclamation-triangle-fill me-2"></i>An error occurred. Please try again.';
                        }
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                        <?php
                        switch($_GET['success']) {
                            case 'registered':
                                echo '<i class="bi bi-check-circle-fill me-2"></i>Registration successful! Please login.';
                                break;
                            case 'logout':
                                echo '<i class="bi bi-check-circle-fill me-2"></i>You have been successfully logged out.';
                                break;
                            default:
                                echo '<i class="bi bi-check-circle-fill me-2"></i>Operation successful!';
                        }
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Logo and Welcome Text -->
                <div class="text-center text-white mb-3">
                    <h1 class="display-5 fw-bold mb-2">Welcome to AdClick</h1>
                </div>
                
                <!-- Card Container -->
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4">
                        <!-- Tabs -->
                        <ul class="nav nav-pills nav-justified mb-3" id="authTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-semibold" data-bs-toggle="pill" data-bs-target="#login">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-semibold" data-bs-toggle="pill" data-bs-target="#signup">
                                    <i class="bi bi-person-plus me-2"></i>Sign Up
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content">
                            <!-- Login Form -->
                            <div class="tab-pane fade show active" id="login">
                                <form action="login.php" method="POST">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="loginEmail" name="email" placeholder="name@example.com" required>
                                        <label for="loginEmail"><i class="bi bi-envelope me-2"></i>Email address</label>
                                    </div>
                                    <div class="form-floating mb-3 position-relative">
                                        <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Password" required>
                                        <label for="loginPassword"><i class="bi bi-lock me-2"></i>Password</label>
                                        <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3" id="toggleLoginPassword" style="cursor: pointer;"></i>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                                    </button>
                                </form>
                            </div>
                            
                            <!-- Signup Form -->
                            <div class="tab-pane fade" id="signup">
                                <form action="signup.php" method="POST">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control" id="signupFullName" name="full_name" placeholder="John Doe" required>
                                        <label for="signupFullName"><i class="bi bi-person me-2"></i>Full Name</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control" id="signupUsername" name="username" placeholder="Username" required>
                                        <label for="signupUsername"><i class="bi bi-person me-2"></i>Username</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="email" class="form-control" id="signupEmail" name="email" placeholder="name@example.com" required>
                                        <label for="signupEmail"><i class="bi bi-envelope me-2"></i>Email address</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control" id="signupMobile" name="mobile" placeholder="Mobile Number" required>
                                        <label for="signupMobile"><i class="bi bi-phone me-2"></i>Mobile Number</label>
                                    </div>
                                    <div class="form-floating mb-2 position-relative">
                                        <input type="password" class="form-control" id="signupPassword" name="password" placeholder="Password" required>
                                        <label for="signupPassword"><i class="bi bi-lock me-2"></i>Password</label>
                                        <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3" id="toggleSignupPassword" style="cursor: pointer;"></i>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                                        <i class="bi bi-person-plus me-2"></i>Sign Up
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
    <script>
        // Toggle password visibility
        document.getElementById('toggleLoginPassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('loginPassword');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                passwordInput.type = 'password';
                this.classList.replace('bi-eye', 'bi-eye-slash');
            }
        });

        document.getElementById('toggleSignupPassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('signupPassword');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                passwordInput.type = 'password';
                this.classList.replace('bi-eye', 'bi-eye-slash');
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