<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require_once 'db_connect.php';
$stmt = $pdo->prepare("SELECT coins, is_admin FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home | coinpaylanka</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="style.css">
  <style>
    body {
        transition: background-color 0.5s, color 0.5s;
    }
    .dark-mode {
        background-color: #121212;
        color: #ffffff;
    }
    .switch {
        position: relative;
        display: inline-block;
        width: 40px; /* Adjusted width */
        height: 20px; /* Adjusted height */
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px; /* Adjusted height */
        width: 16px; /* Adjusted width */
        left: 2px; /* Adjusted position */
        bottom: 2px; /* Adjusted position */
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: rgb(40, 40, 40);
    }

    input:focus + .slider {
        box-shadow: 0 0 1px rgb(33, 33, 33);
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(20px); /* Adjusted transform */
        -ms-transform: translateX(20px); /* Adjusted transform */
        transform: translateX(20px); /* Adjusted transform */
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 20px; /* Adjusted border-radius */
    }

    .slider.round:before {
        border-radius: 50%;
    }

  </style>
</head>

<body class="container-fluid light-mode">

 

  <nav class="navbar navbar-expand-lg bg-success rounded-4 mt-2 navbar-light-mode">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Coin Pay Lanka</a>
    <div class="position-absolute start-50 translate-middle-x">
      <div class="bg-light rounded-3 px-3 py-2">
        <span class="text-success fw-bold">Coins: <span class="coin-count"><?php echo number_format($user['coins']); ?></span></span>
      </div>
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <?php if ($user['is_admin']): ?>
        <li class="nav-item">
          <a class="nav-link" href="admin.php">Admin</a>
        </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link" href="helper.php">Chat With Helper</a>
        <li class="nav-item">
          <a class="nav-link" href="sendGift.php">Send Gift</a>
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
          <a class="nav-link" href="aboutUs.php">about us</a>
        <li class="nav-item ">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
        <!-- <li class="nav-item mt-2 ms-5"> -->
        <li class="nav-item mt-2 ">
          <label class="switch">
            <input type="checkbox" id="toggleDarkMode">
            <span class="slider round"></span>
          </label>
        </li>
      </ul>
    </div>
  </div>
</nav>

  <div class="container text-center mt-5">
    <h1 class="display-4 text-success fw-bold">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p class="lead text-muted " style="color: green !important;">You have successfully logged into Ad Click.</p>
  </div>

  <hr class="mt-5" />

  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2">
      <div class="d-flex flex-column">
        <button class="btn btn-outline-success mb-3 btn-light-mode" id="adClickBtn">
          <i class="bi bi-plus-circle me-2"></i>Ad Click
        </button>
        <button class="btn btn-outline-success mb-3" id="secretCodeBtn">
          <i class="bi bi-key me-2"></i>Secret Code
        </button>
        <button class="btn btn-outline-success mb-3" id="buyProductBtn">
          <i class="bi bi-cart me-2"></i>Buy Product
        </button>
        <button class="btn btn-outline-success mb-3" id="watchVideoBtn">
          <i class="bi bi-play-circle me-2"></i>Watch Video
        </button>
        <button class="btn btn-outline-success mb-3" id="downloadBtn">
          <i class="bi bi-download me-2"></i>Download
        </button>
        <button class="btn btn-outline-success mb-3" id="usersBtn">
          <i class="bi bi-person me-2"></i>Users
        </button>
        
        
    
       
      </div>
    </div>

    <!-- Main Content -->
    <div class="col-md-9 mx-auto" id="mainContent">
      <!-- The PHP content will be loaded here -->
    </div>
  </div>


  <!-- footer here -->
 
   

  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="script.js"></script>
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


    // Toggle dark mode
    const toggle = document.getElementById('modeToggle');
    toggle.addEventListener('change', () => {
        document.body.classList.toggle('dark-mode', toggle.checked);
    });
  </script>
</body>

</html>
