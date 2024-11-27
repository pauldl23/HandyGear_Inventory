<?php
include 'header.php'; // Include the sidebar header
require 'db_connect.php'; // Connect to the database

// Handle search
$search_query = "";
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

// Fetch products based on the search query
$query = "SELECT * FROM tbl_inventory WHERE product_name LIKE ? OR product_ID LIKE ?";
$stmt = $conn->prepare($query);
$search_term = '%' . $search_query . '%';
$stmt->bind_param("ss", $search_term, $search_term);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="products-page" class="main-content">
        <h1>Items</h1>
        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search by product name or ID" value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" class="btn">Search</button>
        </form>

        <table class="product-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Product ID</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Category</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($products) > 0): ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['product_ID']); ?></td>
                            <td><?php echo number_format($product['product_price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($product['product_quantity']); ?></td>
                            <td><?php echo htmlspecialchars($product['product_category']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
