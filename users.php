<?php
session_start();
require_once 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    exit('Unauthorized access');
}

// Fetch all users from database, ordered by coins from high to low
$stmt = $pdo->query("SELECT full_name, coins FROM users ORDER BY coins DESC, full_name ASC");
$users = $stmt->fetchAll();
?>

<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">Users List</h5>
    </div>
    <div class="card-body">
        <div style="max-height: 300px; overflow-y: auto;">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Full Name</th>
                        <th>Coins</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $number = 1;
                    foreach ($users as $user): 
                    ?>
                        <tr>
                            <td><?php echo $number++; ?></td>
                            <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['coins']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
