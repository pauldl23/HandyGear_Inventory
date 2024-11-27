<?php include 'header.php'; ?>
<?php
require 'db_connect.php';

// Fetch items with low stock
$query = "SELECT product_name, product_quantity FROM tbl_inventory WHERE product_quantity < 10";
$result = mysqli_query($conn, $query);
$low_stock_items = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<div class="main-content">
    <h1>Reports</h1>
    <h3>Low Stock Items</h3>
    <ul class="list-group">
        <?php if (count($low_stock_items) > 0): ?>
            <?php foreach ($low_stock_items as $item): ?>
                <li class="list-group-item">
                    <?php echo $item['product_name']; ?> - <?php echo $item['product_quantity']; ?> left
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li class="list-group-item">All items are sufficiently stocked.</li>
        <?php endif; ?>
    </ul>
</div>
</body>
</html>
