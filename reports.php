<?php include 'header.php'; ?>
<?php
require 'db_connect.php';

// Fetch items with low stock
$query = "SELECT product_name, product_quantity FROM tbl_inventory WHERE product_quantity < 20";
$result = mysqli_query($conn, $query);
$low_stock_items = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low Stock Report</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="main-content">
        <h1 class="text-center">Reports</h1>
        <h3 class="mt-3">Low Stock Items</h3>
        <ul class="list-group">
            <?php if (count($low_stock_items) > 0): ?>
                <?php foreach ($low_stock_items as $item): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo $item['product_name']; ?>
                        <span class="badge bg-warning text-dark"><?php echo $item['product_quantity']; ?> left</span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-group-item">All items are sufficiently stocked.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
