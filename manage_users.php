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

// Handle adding a new user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    // Get user data from the form
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $usertype = mysqli_real_escape_string($conn, $_POST['usertype']);

    // Hash password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into the database
    $query = "INSERT INTO tbl_users (username, password, firstname, lastname, usertype) VALUES ('$username', '$hashed_password', '$firstname', '$lastname', '$usertype')";
    if (mysqli_query($conn, $query)) {
        echo "<p>User added successfully.</p>";
    } else {
        echo "<p>Error: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!-- Add this Bootstrap CDN to your <head> section if it's not already there -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="styles.css">

<div id="manage-users" class="main-content container">
    <h1 class="my-4">Manage Users</h1>

    <!-- Add User Form -->
    <div class="card p-4 mb-4">
        <h2>Add New User</h2>
        <form method="POST" action="manage_users.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" id="firstname" name="firstname" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" id="lastname" name="lastname" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="usertype" class="form-label">User Type</label>
                <select id="usertype" name="usertype" class="form-select" required>
                    <option value="Admin">Admin</option>
                    <option value="User">User</option>
                </select>
            </div>

            <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
        </form>
    </div>

    <!-- Users Table -->
    <table class="table mt-4">
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

<!-- Add Bootstrap JS and Popper.js for modal/tooltip support -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
