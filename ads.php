<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require_once 'db_connect.php';

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
    <title>Ad Clicks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
<div class="container my-3">
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
            <span class="text-success fw-bold fs-4">💰 <?php echo htmlspecialchars($ad['coins']); ?> Coins</span>
            <span class="badge bg-success">+2.5%</span>
          </div>
          <p class="card-text">
            <i class="bi bi-info-circle me-2"></i>
            <?php echo htmlspecialchars($ad['description']); ?>
          </p>
          <div class="d-flex justify-content-between align-items-center mt-3">
            <button class="btn btn-success btn-sm earnCoinsBtn" data-ad-id="<?php echo $ad['id']; ?>" data-ad-link="<?php echo htmlspecialchars($ad['link']); ?>">Click Here</button>
            <a class="btn btn-success btn-sm" href="<?php echo htmlspecialchars($ad['new_tab_link']); ?>" target="_blank">Earn More</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
</div>

<script>
$(document).ready(function () {
    $('.earnCoinsBtn').each(function () {
        var $btn = $(this);
        var adId = $btn.data('ad-id');
        var lastClickTime = localStorage.getItem('ads_adClicked_' + adId);

        if (lastClickTime) {
            var elapsedTime = Date.now() - lastClickTime;
            if (elapsedTime < 300000) { // 5 minutes = 300,000 milliseconds
                $btn.prop('disabled', true);
                setTimeout(() => {
                    $btn.prop('disabled', false);
                }, 300000 - elapsedTime);
            }
        }

        $btn.off('click').on('click', function () {
            var adId = $btn.data('ad-id');
            var adLink = $btn.data('ad-link');

            // Open the link in a new tab
            window.open(adLink, '_blank');

            // Disable button and store timestamp
            $btn.prop('disabled', true);
            localStorage.setItem('ads_adClicked_' + adId, Date.now());

            // Re-enable button after 5 minutes
            setTimeout(() => {
                $btn.prop('disabled', false);
                localStorage.removeItem('ads_adClicked_' + adId);
            }, 300000);

            // Proceed with AJAX request after 10 seconds
            setTimeout(function () {
                $.ajax({
                    url: 'update_coins.php',
                    type: 'POST',
                    data: { ad_id: adId },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            $('.coin-count').text(response.coins.toLocaleString());

                            Swal.fire({
                                title: 'Congratulations!',
                                text: 'You earned ' + response.earned_coins + ' coins!',
                                icon: 'success',
                                confirmButtonColor: '#198754',
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonColor: '#198754'
                            });
                            $btn.prop('disabled', false);
                            localStorage.removeItem('ads_adClicked_' + adId);
                        }
                    },
                    error: function () {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Error updating coins',
                            icon: 'error',
                            confirmButtonColor: '#198754'
                        });
                        $btn.prop('disabled', false);
                        localStorage.removeItem('ads_adClicked_' + adId);
                    }
                });
            }, 10000); // 10 seconds delay
        });
    });
});
</script>

</body>
</html>
