<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>About Us | coinpaylanka</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="style.css">
</head>

<body class="container-fluid light-mode">
  <nav class="navbar navbar-expand-lg bg-success rounded-4 mt-2 navbar-light-mode">
    <div class="container-fluid">
      <a class="navbar-brand" href="home.php">Coin Pay Lanka</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="home.php">Home</a>
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

  <div class="container text-center mt-5">
    <h1 class="display-4 text-success fw-bold">About Us</h1>
    <p class="lead">Welcome to Coin Pay Lanka. We are dedicated to providing the best service for our users.</p>
  </div>

  <div class="container text-center mt-5">
    <h2 class="text-success fw-bold">Contact Information</h2>
    <p class="lead">You can reach us at:</p>
    <p>Email: support@coinpaylanka.com</p>
    <p>Phone: +94 123 456 789</p>
    <p>Address: 123 Coin Pay Lane, Colombo, Sri Lanka</p>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="script.js"></script>
</body>

</html>
