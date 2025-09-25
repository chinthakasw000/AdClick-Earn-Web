<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_ad'])) {
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $coins = $_POST['coins'] ?? 0;
        $link = $_POST['link'] ?? '';
        $newTabLink = $_POST['new_tab_link'] ?? '';

        if ($title && $description && $coins && $link && $newTabLink) {
            $stmt = $pdo->prepare("INSERT INTO ads (title, description, coins, link, new_tab_link) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$title, $description, $coins, $link, $newTabLink])) {
                $_SESSION['success'] = "Ad added successfully!";
            } else {
                $_SESSION['error'] = "Failed to add ad.";
            }
        }
    } elseif (isset($_POST['remove_ad'])) {
        $adId = $_POST['ad_id'] ?? null;

        if ($adId) {
            $stmt = $pdo->prepare("DELETE FROM ads WHERE id = ?");
            if ($stmt->execute([$adId])) {
                $_SESSION['success'] = "Ad deleted successfully!";
            } else {
                $_SESSION['error'] = "Failed to delete ad.";
            }
        }
    }
    header("Location: ads_manage.php");
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
    <title>Manage Ads</title>
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
<body oncontextmenu="return false;">
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Ads</h2>
        <a href="home.php" class="btn btn-secondary">
            <i class="bi bi-house-door-fill"></i> Back to Home
        </a>
    </div>
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

    <div class="row">
        <div class="col-12 col-md-6 mb-4">
            <h3>Add New Ad</h3>
            <form method="POST" action="ads_manage.php">
                <input type="hidden" name="add_ad" value="1">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="coins" class="form-label">Coins</label>
                    <input type="number" class="form-control" id="coins" name="coins" required>
                </div>
                <div class="mb-3">
                    <label for="link" class="form-label">Link</label>
                    <input type="url" class="form-control" id="link" name="link" required>
                </div>
                <div class="mb-3">
                    <label for="new_tab_link" class="form-label">New Tab Link</label>
                    <select class="form-select" id="new_tab_link" name="new_tab_link" required>
                        <option value="">Select Page</option>
                        <option value="new_tab/page1.php">Page 1</option>
                        <option value="new_tab/page2.php">Page 2</option>
                        <option value="new_tab/page3.php">Page 3</option>
                        <option value="new_tab/page4.php">Page 4</option>
                        <option value="new_tab/page5.php">Page 5</option>
                        <option value="new_tab/page6.php">Page 6</option>
                        <option value="new_tab/page7.php">Page 7</option>
                        <option value="new_tab/page8.php">Page 8</option>
                        <option value="new_tab/page9.php">Page 9</option>
                        <option value="new_tab/page10.php">Page 10</option>
                        <option value="new_tab/page11.php">Page 11</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Add Ad</button>
            </form>
        </div>

        <div class="col-12 col-md-6">
            <h3>Existing Ads</h3>
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
                    <form action="ads_manage.php" method="POST">
                      <input type="hidden" name="ad_id" value="<?php echo $ad['id']; ?>">
                      <input type="hidden" name="remove_ad" value="1">
                      <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                    </form>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Block right-click context menu
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });
</script>
</body>
</html>
