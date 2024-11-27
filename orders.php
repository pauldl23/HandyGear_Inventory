<?php include 'header.php'; ?>
<?php
require 'db_connect.php';

// Fetch orders
$query = "SELECT t.transaction_ID, t.inventory_ID, i.product_name, t.transaction_date
          FROM tbl_transaction t
          JOIN tbl_inventory i ON t.inventory_ID = i.inventory_ID
          WHERE t.transaction_type = 'Purchase'";
$result = mysqli_query($conn, $query);
$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<link rel="stylesheet" href="styles.css">
<div class="main-content">
    <h1>Orders</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>Inventory ID</th>
                <th>Product Name</th>
                <th>Transaction Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['transaction_ID']; ?></td>
                    <td><?php echo $order['inventory_ID']; ?></td>
                    <td><?php echo $order['product_name']; ?></td>
                    <td><?php echo $order['transaction_date']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
