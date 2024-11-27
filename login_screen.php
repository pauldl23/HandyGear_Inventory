<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check user credentials
    $query = "SELECT * FROM tbl_users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['userID'] = $user['userID'];
        $_SESSION['usertype'] = $user['usertype'];

        // Redirect based on user type
        if ($user['usertype'] === 'Admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: browse_items.php");
        }
        exit;
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HandyGear Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="loginstyle.css"> <!-- Custom CSS -->
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-left">
            <!-- Background gradient (left side) -->
        </div>
        <div class="login-right">
            <h1 class="text-orange">HandyGear</h1>
            <h2 class="text-light">Inventory</h2>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form method="post" class="login-form">
                <input type="text" name="username" placeholder="Username" class="form-control mb-3" required>
                <input type="password" name="password" placeholder="Password" class="form-control mb-3" required>
                <button type="submit" class="btn btn-orange w-100">Login</button>
                <a href="#" class="forgot-password text-light mt-2 d-block">Forgot Password</a>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
