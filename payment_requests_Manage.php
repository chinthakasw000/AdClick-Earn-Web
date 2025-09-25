<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_request'])) {
        $requestId = $_POST['request_id'] ?? null;

        if ($requestId) {
            $stmt = $pdo->prepare("DELETE FROM payment_requests WHERE id = ?");
            if ($stmt->execute([$requestId])) {
                $_SESSION['success'] = "Payment request deleted successfully!";
            } else {
                $_SESSION['error'] = "Failed to delete payment request.";
            }
        }
    } elseif (isset($_POST['update_status'])) {
        $requestId = $_POST['request_id'] ?? null;
        $newStatus = $_POST['status'] ?? null;

        if ($requestId && $newStatus) {
            $stmt = $pdo->prepare("UPDATE payment_requests SET status = ? WHERE id = ?");
            if ($stmt->execute([$newStatus, $requestId])) {
                $_SESSION['success'] = "Payment request status updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update payment request status.";
            }
        }
    }
    header("Location: payment_requests_manage.php");
    exit();
}

// Fetch payment requests from the database
$stmt = $pdo->prepare("SELECT pr.*, u.username, u.email, u.mobile FROM payment_requests pr JOIN users u ON pr.user_id = u.id WHERE pr.status = 'Pending'");
$stmt->execute();
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payment Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h2>Manage Payment Requests</h2>
    <a href="home.php" class="btn btn-secondary mb-3">Back to Home</a>
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

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $request): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($request['id']); ?></td>
                        <td><?php echo htmlspecialchars($request['username']); ?></td>
                        <td><?php echo htmlspecialchars($request['email']); ?></td>
                        <td><?php echo htmlspecialchars($request['mobile']); ?></td>
                        <td><?php echo htmlspecialchars($request['amount']); ?></td>
                        <td><?php echo htmlspecialchars($request['payment_method']); ?></td>
                        <td><?php echo htmlspecialchars($request['details']); ?></td>
                        <td>
                            <form method="POST" action="payment_requests_manage.php" class="mb-0">
                                <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                <select name="status" class="form-select form-select-sm mb-2">
                                    <option value="Pending" <?php echo $request['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Approved" <?php echo $request['status'] == 'Approved' ? 'selected' : ''; ?>>Approved</option>
                                    <option value="Rejected" <?php echo $request['status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-primary btn-sm">Update</button>
                            </form>
                        </td>
                        <td><?php echo htmlspecialchars($request['created_at']); ?></td>
                        <td>
                            <form method="POST" action="payment_requests_manage.php" class="mb-0">
                                <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                <input type="hidden" name="delete_request" value="1">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>