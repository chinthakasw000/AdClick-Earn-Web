<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require_once 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .bg-success {
            background-color: #198754 !important;
        }
        .btn-success {
            background-color: #198754 !important;
            border-color: #198754 !important;
        }
        .btn-success:hover {
            background-color: #157347 !important;
            border-color: #146c43 !important;
        }
        .text-success {
            color: #198754 !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ads_manage.php">Manage Ads</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h2>Welcome to the Admin Dashboard</h2>
        <p>Use the navigation menu to manage ads and other administrative tasks.</p>

        <!-- Add your admin functionalities here -->
        <div class="row">
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden hover-effect">
                    <div class="card-body">
                        <h5 class="card-title">Manage Ads</h5>
                        <p class="card-text">Add And Delete</p>
                        <a href="ads_manage.php" class="btn btn-success">Click Here</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden hover-effect">
                    <div class="card-body">
                        <h5 class="card-title">Manage Secret Codes</h5>
                        <p class="card-text">Add And Delete Secret Codes</p>
                        <a href="secretCodeManage.php" class="btn btn-success">Click Here</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden hover-effect">
                    <div class="card-body">
                        <h5 class="card-title">Manage Payment Requests</h5>
                        <p class="card-text">Manage Payment Requests</p>
                        <a href="payment_requests_Manage.php" class="btn btn-success">Clicke Here</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden hover-effect">
                    <div class="card-body">
                        <h5 class="card-title">User Manage</h5>
                        <p class="card-text">Add / Delete / Block / Edite Users</p>
                        <a href="user_manage.php" class="btn btn-success">Click Here</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden hover-effect">
                    <div class="card-body">
                        <h5 class="card-title">Users Messages</h5>
                        <p class="card-text">Manage Users Messages</p>
                        <a href="#" class="btn btn-success">Click Here</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden hover-effect">
                    <div class="card-body">
                        <h5 class="card-title">Card 6</h5>
                        <p class="card-text">Content for card 6.</p>
                        <a href="#" class="btn btn-success">Go somewhere</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden hover-effect">
                    <div class="card-body">
                        <h5 class="card-title">Card 7</h5>
                        <p class="card-text">Content for card 7.</p>
                        <a href="#" class="btn btn-success">Go somewhere</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden hover-effect">
                    <div class="card-body">
                        <h5 class="card-title">Card 8</h5>
                        <p class="card-text">Content for card 8.</p>
                        <a href="#" class="btn btn-success">Go somewhere</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden hover-effect">
                    <div class="card-body">
                        <h5 class="card-title">Card 9</h5>
                        <p class="card-text">Content for card 9.</p>
                        <a href="#" class="btn btn-success">Go somewhere</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden hover-effect">
                    <div class="card-body">
                        <h5 class="card-title">Card 10</h5>
                        <p class="card-text">Content for card 10.</p>
                        <a href="#" class="btn btn-success">Go somewhere</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden hover-effect">
                    <div class="card-body">
                        <h5 class="card-title">Card 11</h5>
                        <p class="card-text">Content for card 11.</p>
                        <a href="#" class="btn btn-success">Go somewhere</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden hover-effect">
                    <div class="card-body">
                        <h5 class="card-title">Card 12</h5>
                        <p class="card-text">Content for card 12.</p>
                        <a href="#" class="btn btn-success">Go somewhere</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden hover-effect">
                    <div class="card-body">
                        <h5 class="card-title">Card 13</h5>
                        <p class="card-text">Content for card 13.</p>
                        <a href="#" class="btn btn-success">Go somewhere</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden hover-effect">
                    <div class="card-body">
                        <h5 class="card-title">Card 14</h5>
                        <p class="card-text">Content for card 14.</p>
                        <a href="#" class="btn btn-success">Go somewhere</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden hover-effect">
                    <div class="card-body">
                        <h5 class="card-title">Card 15</h5>
                        <p class="card-text">Content for card 15.</p>
                        <a href="#" class="btn btn-success">Go somewhere</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden hover-effect">
                    <div class="card-body">
                        <h5 class="card-title">Card 16</h5>
                        <p class="card-text">Content for card 16.</p>
                        <a href="#" class="btn btn-success">Go somewhere</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>