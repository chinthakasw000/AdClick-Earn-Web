<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $adId = $_POST['ad_id'] ?? null;

    if ($adId) {
        $stmt = $pdo->prepare("DELETE FROM ads WHERE id = ?");
        if ($stmt->execute([$adId])) {
            $_SESSION['success'] = "Ad deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete ad.";
        }
    }
    header("Location: ads_remove.php");
    exit();
}

// Fetch ads from the database
$stmt = $pdo->prepare("SELECT * FROM ads");
$stmt->execute();
$ads = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Ads</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    
<div class="container my-3">
    <h2>Manage Ads</h2>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <p><a href="home.php" class="btn btn-primary">Back to Home</a></p>
    <div class="d-flex flex-wrap gap-3 justify-content-center">
      <?php foreach ($ads as $ad): ?>
      <div class="card shadow-lg border-0 rounded-4 overflow-hidden hover-effect" style="max-width: 340px;">
        <div class="position-relative">
          <div class="bg-success bg-gradient p-3">
            <div class="d-flex align-items-center gap-3">
              <i class="bi bi-currency-bitcoin text-white" style="font-size: 2.5rem;"></i>
              <div>
                <h5 class="card-title text-white mb-0"><?php echo htmlspecialchars($ad['title']); ?></h5>
                <span class="badge bg-success">Popular</span>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-success fw-bold fs-4">Coins <?php echo htmlspecialchars($ad['coins']); ?></span>
            <span class="badge bg-success">+2.5%</span>
          </div>
          <p class="card-text">
            <i class="bi bi-info-circle me-2"></i>
            <?php echo htmlspecialchars($ad['description']); ?>
          </p>
          <div class="d-flex justify-content-between align-items-center mt-3">
            <form action="ads_remove.php" method="POST">
              <input type="hidden" name="ad_id" value="<?php echo $ad['id']; ?>">
              <button type="submit" class="btn btn-danger btn-sm">Remove</button>
            </form>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
</div>
</body>
</html>

