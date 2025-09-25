<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Navbar</title>
  <!-- Add Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
  
  <nav class="navbar navbar-expand-lg bg-success navbar-dark px-3 mt-2 rounded-4" style="border-radius: 15px;">
    <div class="container-fluid">
      <!-- Brand Name -->
      <a class="navbar-brand fw-bold" href="#">Ad Click</a>

      <!-- Coin Section -->
      <div class="bg-light rounded-3 d-flex align-items-center px-3 py-1" style="border-radius: 12px;">
        <p class="m-0 text-success fw-bold">Coin Count: 10,000</p>
      </div>

      <!-- Profile Image and Logout -->
      <div class="d-flex align-items-center gap-3">
        <i class="fas fa-user-circle text-light fs-4"></i>
        <a href="logout.php" class="btn btn-success" style="border-radius: 10px;">
          <i class="fas fa-sign-out-alt me-1"></i>Logout
        </a>
      </div>
    </div>
  </nav>

  <!-- Add Bootstrap JS and Popper.js -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>