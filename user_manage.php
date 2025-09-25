<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require_once 'db_connect.php';

$search = $_GET['search'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_user'])) {
        $full_name = trim($_POST['full_name']);
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $mobile = trim($_POST['mobile']);
        $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
        $is_admin = isset($_POST['is_admin']) ? 1 : 0;

        // Validate input
        if (empty($full_name) || empty($username) || empty($email) || empty($mobile) || empty($password)) {
            $_SESSION['error'] = "All fields are required.";
        } else {
            // Check for duplicate username, email, or mobile
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ? OR mobile = ?");
            $stmt->execute([$username, $email, $mobile]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $_SESSION['error'] = "Username, email, or mobile number already exists.";
            } else {
                try {
                    // Insert user into the database
                    $stmt = $pdo->prepare("INSERT INTO users (full_name, username, email, mobile, password, is_admin) VALUES (?, ?, ?, ?, ?, ?)");
                    if ($stmt->execute([$full_name, $username, $email, $mobile, $password, $is_admin])) {
                        $_SESSION['success'] = "User added successfully!";
                    } else {
                        $_SESSION['error'] = "Failed to add user.";
                    }
                } catch (PDOException $e) {
                    $_SESSION['error'] = "Database error: " . $e->getMessage();
                }
            }
        }
    } elseif (isset($_POST['delete_user'])) {
        $userId = $_POST['user_id'] ?? null;

        if ($userId) {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            if ($stmt->execute([$userId])) {
                $_SESSION['success'] = "User deleted successfully!";
            } else {
                $_SESSION['error'] = "Failed to delete user.";
            }
        }
    } elseif (isset($_POST['block_user'])) {
        $userId = $_POST['user_id'] ?? null;
        $block = $_POST['block'] ?? null;

        if ($userId && $block !== null) {
            $stmt = $pdo->prepare("UPDATE users SET blocked = ? WHERE id = ?");
            if ($stmt->execute([$block, $userId])) {
                $_SESSION['success'] = $block ? "User blocked successfully!" : "User unblocked successfully!";
            } else {
                $_SESSION['error'] = "Failed to update user status.";
            }
        }
    } elseif (isset($_POST['edit_user'])) {
        $userId = $_POST['user_id'] ?? null;
        $full_name = trim($_POST['full_name']);
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $mobile = trim($_POST['mobile']);
        $password = trim($_POST['password']);
        $is_admin = isset($_POST['is_admin']) ? 1 : 0;

        // Validate input
        if (empty($full_name) || empty($username) || empty($email) || empty($mobile)) {
            $_SESSION['error'] = "All fields are required.";
        } else {
            try {
                // Update user in the database
                if (!empty($password)) {
                    $password = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $pdo->prepare("UPDATE users SET full_name = ?, username = ?, email = ?, mobile = ?, password = ?, is_admin = ? WHERE id = ?");
                    $stmt->execute([$full_name, $username, $email, $mobile, $password, $is_admin, $userId]);
                } else {
                    $stmt = $pdo->prepare("UPDATE users SET full_name = ?, username = ?, email = ?, mobile = ?, is_admin = ? WHERE id = ?");
                    $stmt->execute([$full_name, $username, $email, $mobile, $is_admin, $userId]);
                }
                $_SESSION['success'] = "User updated successfully!";
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) { // Integrity constraint violation
                    $_SESSION['error'] = "Username, email, or mobile number already exists.";
                } else {
                    $_SESSION['error'] = "Database error: " . $e->getMessage();
                }
            }
        }
    }
    header("Location: user_manage.php");
    exit();
}

// Fetch admins and regular users separately from the database
if (!empty($search)) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE (full_name LIKE ? OR email LIKE ? OR mobile LIKE ?) AND is_admin = 1");
    $stmt->execute(["%$search%", "%$search%", "%$search%"]);
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE (full_name LIKE ? OR email LIKE ? OR mobile LIKE ?) AND is_admin = 0");
    $stmt->execute(["%$search%", "%$search%", "%$search%"]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE is_admin = 1");
    $stmt->execute();
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE is_admin = 0");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h2>Manage Users</h2>
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

    <div class="row mb-4">
        <div class="col-12">
            <form method="GET" action="user_manage.php">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search by name, email, or mobile" value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6 mb-4">
            <h3>Add New User</h3>
            <form method="POST" action="user_manage.php">
                <input type="hidden" name="add_user" value="1">
                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="mobile" class="form-label">Mobile</label>
                    <input type="text" class="form-control" id="mobile" name="mobile" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="is_admin" class="form-label">Admin</label>
                    <input type="checkbox" id="is_admin" name="is_admin">
                </div>
                <button type="submit" class="btn btn-primary">Add User</button>
            </form>
        </div>

        <div class="col-12 col-md-6">
            <h3>Admins</h3>
            <ul class="list-group mb-4">
                <?php foreach ($admins as $admin): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?php echo htmlspecialchars($admin['full_name']); ?></strong><br>
                            <small><?php echo htmlspecialchars($admin['email']); ?></small><br>
                            <small><?php echo htmlspecialchars($admin['mobile']); ?></small>
                        </div>
                        <div>
                            <form method="POST" action="user_manage.php" class="d-inline">
                                <input type="hidden" name="user_id" value="<?php echo $admin['id']; ?>">
                                <input type="hidden" name="delete_user" value="1">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            <form method="POST" action="user_manage.php" class="d-inline">
                                <input type="hidden" name="user_id" value="<?php echo $admin['id']; ?>">
                                <input type="hidden" name="block_user" value="1">
                                <input type="hidden" name="block" value="<?php echo $admin['blocked'] ? 0 : 1; ?>">
                                <button type="submit" class="btn btn-warning btn-sm"><?php echo $admin['blocked'] ? 'Unblock' : 'Block'; ?></button>
                            </form>
                            <button class="btn btn-info btn-sm" onclick="editUser(<?php echo $admin['id']; ?>, '<?php echo htmlspecialchars($admin['full_name']); ?>', '<?php echo htmlspecialchars($admin['username']); ?>', '<?php echo htmlspecialchars($admin['email']); ?>', '<?php echo htmlspecialchars($admin['mobile']); ?>', <?php echo $admin['is_admin'] ? 'true' : 'false'; ?>)">Edit</button>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <h3>Regular Users</h3>
            <ul class="list-group">
                <?php foreach ($users as $user): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?php echo htmlspecialchars($user['full_name']); ?></strong><br>
                            <small><?php echo htmlspecialchars($user['email']); ?></small><br>
                            <small><?php echo htmlspecialchars($user['mobile']); ?></small>
                        </div>
                        <div>
                            <form method="POST" action="user_manage.php" class="d-inline">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <input type="hidden" name="delete_user" value="1">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            <form method="POST" action="user_manage.php" class="d-inline">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <input type="hidden" name="block_user" value="1">
                                <input type="hidden" name="block" value="<?php echo $user['blocked'] ? 0 : 1; ?>">
                                <button type="submit" class="btn btn-warning btn-sm"><?php echo $user['blocked'] ? 'Unblock' : 'Block'; ?></button>
                            </form>
                            <button class="btn btn-info btn-sm" onclick="editUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['full_name']); ?>', '<?php echo htmlspecialchars($user['username']); ?>', '<?php echo htmlspecialchars($user['email']); ?>', '<?php echo htmlspecialchars($user['mobile']); ?>', <?php echo $user['is_admin'] ? 'true' : 'false'; ?>)">Edit</button>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="user_manage.php">
                        <input type="hidden" name="edit_user" value="1">
                        <input type="hidden" name="user_id" id="edit_user_id">
                        <div class="mb-3">
                            <label for="edit_full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="edit_full_name" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="edit_username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_mobile" class="form-label">Mobile</label>
                            <input type="text" class="form-control" id="edit_mobile" name="mobile" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_password" class="form-label">New Password (leave blank to keep current)</label>
                            <input type="password" class="form-control" id="edit_password" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="edit_is_admin" class="form-label">Admin</label>
                            <input type="checkbox" id="edit_is_admin" name="is_admin">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function editUser(id, fullName, username, email, mobile, isAdmin) {
    $('#edit_user_id').val(id);
    $('#edit_full_name').val(fullName);
    $('#edit_username').val(username);
    $('#edit_email').val(email);
    $('#edit_mobile').val(mobile);
    $('#edit_is_admin').prop('checked', isAdmin);
    $('#editUserModal').modal('show');
}
</script>
</body>
</html>