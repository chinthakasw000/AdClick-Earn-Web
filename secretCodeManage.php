<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_code'])) {
        $code = trim($_POST['code']);

        // Validate input
        if (empty($code)) {
            $_SESSION['error'] = "Code is required.";
        } else {
            try {
                // Insert code into the database
                $stmt = $pdo->prepare("INSERT INTO secret_codes (code) VALUES (?)");
                if ($stmt->execute([$code])) {
                    $_SESSION['success'] = "Code added successfully!";
                } else {
                    $_SESSION['error'] = "Failed to add code.";
                }
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) { // Integrity constraint violation
                    $_SESSION['error'] = "Code already exists.";
                } else {
                    $_SESSION['error'] = "Database error: " . $e->getMessage();
                }
            }
        }
    } elseif (isset($_POST['delete_code'])) {
        $codeId = $_POST['code_id'] ?? null;

        if ($codeId) {
            $stmt = $pdo->prepare("DELETE FROM secret_codes WHERE id = ?");
            if ($stmt->execute([$codeId])) {
                $_SESSION['success'] = "Code deleted successfully!";
            } else {
                $_SESSION['error'] = "Failed to delete code.";
            }
        }
    } elseif (isset($_POST['toggle_code_status'])) {
        $codeId = $_POST['code_id'] ?? null;
        $currentStatus = $_POST['current_status'] ?? null;

        if ($codeId && $currentStatus !== null) {
            $newStatus = $currentStatus ? 0 : 1;
            $stmt = $pdo->prepare("UPDATE secret_codes SET used = ? WHERE id = ?");
            if ($stmt->execute([$newStatus, $codeId])) {
                $_SESSION['success'] = "Code status updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update code status.";
            }
        }
    } elseif (isset($_POST['delete_all_codes'])) {
        // Delete all codes
        $stmt = $pdo->prepare("DELETE FROM secret_codes");
        if ($stmt->execute()) {
            $_SESSION['success'] = "All codes deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete all codes.";
        }
    }
    header("Location: secretCodeManage.php");
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['auto_add_code'])) {
    $code = bin2hex(random_bytes(4)); // Generate a random code

    try {
        $stmt = $pdo->prepare("INSERT INTO secret_codes (code) VALUES (?)");
        if ($stmt->execute([$code])) {
            echo json_encode(['success' => true, 'code' => $code]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to add code.']);
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Integrity constraint violation
            echo json_encode(['success' => false, 'error' => 'Code already exists.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
        }
    }
    exit();
}

// Fetch secret codes from the database
$stmt = $pdo->prepare("SELECT * FROM secret_codes");
$stmt->execute();
$codes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Secret Codes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2>Manage Secret Codes</h2>
        <a href="home.php" class="btn btn-secondary mb-3">Back to Home</a>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success'];
                unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error'];
                unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12 col-md-6 mb-4">
                <h3>Add New Code</h3>
                <form method="POST" action="secretCodeManage.php">
                    <input type="hidden" name="add_code" value="1">
                    <div class="mb-3">
                        <label for="code" class="form-label">Code</label>
                        <input type="text" class="form-control" id="code" name="code" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Code</button>
                </form>
            </div>



            <div class="col-12 col-md-6 text-center">
                <!-- Add buttons for Start, Stop, and Delete All Codes -->
                <div class="row mt-4">
                    <div class="row d-flex justify-content-center">
                        <div class="col-6">
                            <button id="start-generate" class="btn btn-success w-100">Add</button>
                        </div>
                        <div class="col-6">
                            <form method="POST" action="secretCodeManage.php" class="d-inline">
                                <input type="hidden" name="delete_all_codes" value="1">
                                <button type="submit" class="btn btn-danger w-100">Delete All</button>
                            </form>
                        </div>
                    </div>
                </div>

                <h3>Existing Codes</h3>
                <ul class="list-group">
                    <?php foreach ($codes as $code): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <?php echo htmlspecialchars($code['code']); ?>
                                <span class="badge bg-<?php echo $code['used'] ? 'danger' : 'success'; ?> ms-2">
                                    <?php echo $code['used'] ? 'Used' : 'Unused'; ?>
                                </span>
                            </div>
                            <div>
                                <form method="POST" action="secretCodeManage.php" class="d-inline">
                                    <input type="hidden" name="code_id" value="<?php echo $code['id']; ?>">
                                    <input type="hidden" name="delete_code" value="1">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                <form method="POST" action="secretCodeManage.php" class="d-inline">
                                    <input type="hidden" name="code_id" value="<?php echo $code['id']; ?>">
                                    <input type="hidden" name="current_status" value="<?php echo $code['used']; ?>">
                                    <input type="hidden" name="toggle_code_status" value="1">
                                    <button type="submit" class="btn btn-<?php echo $code['used'] ? 'success' : 'warning'; ?> btn-sm">
                                        <?php echo $code['used'] ? 'Mark as Unused' : 'Mark as Used'; ?>
                                    </button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>


    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let generateInterval;

        function addSecretCode() {
            $.get('secretCodeManage.php?auto_add_code=1', function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    console.log('Code added:', data.code);
                    // Optionally, update the UI with the new code
                    location.reload(); // Reload the page to show the new code
                } else {
                    console.error('Error adding code:', data.error);
                }
            });
        }

        $('#start-generate').on('click', function() {
            generateInterval = setInterval(addSecretCode, 100); // Add a new code every 10 seconds
            console.log('Started generating codes...');
        });

        $('#stop-generate').on('click', function() {
            clearInterval(generateInterval);
            console.log('Stopped generating codes.');
        });
    </script>
</body>

</html>