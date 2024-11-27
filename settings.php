<?php
include 'header.php'; // Include the sidebar header
require 'db_connect.php'; // Connect to the database

// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login_screen.php");
    exit;
}

// Initialize variables
$username = $_SESSION['username'];
$userID = $_SESSION['userID'];
$usertype = $_SESSION['usertype']; // 'Admin' or 'User'
$message = "";

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch current password from the database
    $query = "SELECT password FROM tbl_users WHERE userID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $user['password'] === $current_password) {
        if ($new_password === $confirm_password) {
            // Update the password
            $update_query = "UPDATE tbl_users SET password = ? WHERE userID = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("si", $new_password, $userID);
            if ($stmt->execute()) {
                $message = "<div class='alert alert-success'>Password updated successfully!</div>";
            } else {
                $message = "<div class='alert alert-danger'>Failed to update password. Please try again.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>New passwords do not match.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Current password is incorrect.</div>";
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory System</title>
    <link rel="stylesheet" href="styles.css">
</head>

<div id="settings-page">


    <h1>Settings</h1>
    <p class="lead">Manage your account settings and preferences here.</p>

    <?php echo $message; ?>

    <!-- Change Password Section -->
    <div class="card">
        <div class="card-header bg-primary text-white">Change Password</div>
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Password</button>
            </form>
        </div>
    </div>

    <!-- Profile Information Section -->
    <div class="card">
        <div class="card-header bg-success text-white">Profile Information</div>
        <div class="card-body profile-info">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
            <p><strong>User Type:</strong> <?php echo htmlspecialchars($usertype); ?></p>
        </div>
    </div>

    <!-- Admin Settings Section (Only for Admins) -->
    <?php if ($usertype === 'Admin'): ?>
    <div class="card">
        <div class="card-header bg-warning text-dark">Admin Settings</div>
        <div class="card-body">
            <p>As an admin, you can manage system-wide settings here. Contact your system administrator for advanced configuration options.</p>
            <ul>
                <li><a href="inventory.php" class="text-primary">Manage Inventory</a></li>
                <li><a href="reports.php" class="text-primary">View Reports</a></li>
            </ul>
        </div>
    </div>
    <?php endif; ?>
</div>
