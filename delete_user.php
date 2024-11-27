<?php
require 'db_connect.php';
session_start();

// Ensure the user is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['usertype'] !== 'Admin') {
    header("Location: login_screen.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = (int)$_POST['user_id'];

    // Prevent deleting the currently logged-in admin
    if ($_SESSION['userID'] === $user_id) {
        echo "<script>alert('You cannot delete your own account.'); window.location.href='manage_users.php';</script>";
        exit;
    }

    // Delete the user
    $query = "DELETE FROM tbl_users WHERE userID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('User deleted successfully.'); window.location.href='manage_users.php';</script>";
    } else {
        echo "<script>alert('Failed to delete user.'); window.location.href='manage_users.php';</script>";
    }
}
?>
