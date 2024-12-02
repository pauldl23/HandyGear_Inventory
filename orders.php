<?php include 'header.php'; ?>
<?php
require 'db_connect.php'; // Database connection

// Fetch purchase orders from tbl_transaction
$query = "SELECT t.transaction_ID, t.inventory_ID, i.product_name, t.transaction_date
          FROM tbl_transaction t
          JOIN tbl_inventory i ON t.inventory_ID = i.inventory_ID
          WHERE t.transaction_type = 'Purchase'";
$result = mysqli_query($conn, $query);

// Check if query executed successfully
if (!$result) {
    die('Query failed: ' . mysqli_error($conn));
}

// Fetch all rows as an associative array
$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Orders</title>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 bg-white text-white vh-100 p-4">
            <h2>Sidebar</h2>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Transactions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Inventory</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Reports</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 p-5">
            <h1 class="mb-4">Purchase Orders</h1>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Transaction ID</th>
                        <th>Inventory ID</th>
                        <th>Product Name</th>
                        <th>Transaction Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['transaction_ID']); ?></td>
                                <td><?php echo htmlspecialchars($order['inventory_ID']); ?></td>
                                <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['transaction_date']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">No purchase orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
