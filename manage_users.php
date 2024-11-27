<?php
include 'header.php';
require 'db_connect.php';

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['usertype'] !== 'Admin') {
    header("Location: login_screen.php");
    exit;
}

// Fetch all users
$result = mysqli_query($conn, "SELECT * FROM tbl_users");
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<link rel="stylesheet" href="styles.css">
<div id="manage-users" class="main-content">
    <h1>Manage Users</h1>
    <table class="table">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>User Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['userID']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['firstname']; ?></td>
                    <td><?php echo $user['lastname']; ?></td>
                    <td><?php echo $user['usertype']; ?></td>
                    <td>
                        <form method="post" action="delete_user.php" class="action-form">
                            <input type="hidden" name="user_id" value="<?php echo $user['userID']; ?>">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
