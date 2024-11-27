<?php include 'header.php'; ?>
<?php
require 'db_connect.php';

// Handle Add Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $product_name = trim($_POST['product_name']);
    $product_id = trim($_POST['product_id']);
    $product_price = (float)$_POST['product_price'];
    $product_quantity = (int)$_POST['product_quantity'];
    $product_category = trim($_POST['product_category']);

    // Prevent adding empty rows
    if (!empty($product_name) && !empty($product_id)) {
        $query = "INSERT INTO tbl_inventory (product_name, product_ID, product_price, product_quantity, product_category) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssdis", $product_name, $product_id, $product_price, $product_quantity, $product_category);
        $stmt->execute();
        $stmt->close();
    }
}

// Handle Edit Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product'])) {
    $inventory_id = (int)$_POST['inventory_id'];
    $product_name = trim($_POST['product_name']);
    $product_id = trim($_POST['product_id']);
    $product_price = (float)$_POST['product_price'];
    $product_category = trim($_POST['product_category']);

    $query = "UPDATE tbl_inventory SET product_name = ?, product_ID = ?, product_price = ?, product_category = ? WHERE inventory_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssdis", $product_name, $product_id, $product_price, $product_category, $inventory_id);
    $stmt->execute();
    $stmt->close();
}

// Handle Adjust Quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adjust_quantity'])) {
    $inventory_id = (int)$_POST['inventory_id'];
    $quantity_change = (int)$_POST['quantity_change'];

    $query = "UPDATE tbl_inventory SET product_quantity = GREATEST(0, product_quantity + ?) WHERE inventory_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $quantity_change, $inventory_id);
    $stmt->execute();
    $stmt->close();
}

// Handle Delete Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $inventory_id = (int)$_POST['inventory_id'];
    $query = "DELETE FROM tbl_inventory WHERE inventory_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $inventory_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch Inventory Items
$result = $conn->query("SELECT * FROM tbl_inventory WHERE product_name IS NOT NULL AND product_name != ''");
$items = $result->fetch_all(MYSQLI_ASSOC);
?>

<link rel="stylesheet" href="styles.css">
<div id="inventory" class="main-content">
    <h1>Inventory Management</h1>

    <!-- Add Product Form -->
    <div class="form-container">
        <h2>Add New Product</h2>
        <form method="post">
            <input type="text" name="product_name" placeholder="Product Name" required>
            <input type="text" name="product_id" placeholder="Product ID" required>
            <input type="number" step="0.01" name="product_price" placeholder="Price" required>
            <input type="number" name="product_quantity" placeholder="Quantity" required>
            <input type="text" name="product_category" placeholder="Category" required>
            <button type="submit" name="add_product" class="btn btn-search">Add Product</button>
        </form>
    </div>

    <!-- Inventory Table -->
    <table class="table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Product ID</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr id="row-<?php echo $item['inventory_ID']; ?>">
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($item['product_ID']); ?></td>
                    <td><?php echo htmlspecialchars($item['product_price']); ?></td>
                    <td><?php echo htmlspecialchars($item['product_quantity']); ?></td>
                    <td><?php echo htmlspecialchars($item['product_category']); ?></td>
                    <td>
                        <!-- Adjust Quantity -->
                        <form method="post" class="action-form" style="display: inline;">
                            <input type="hidden" name="inventory_id" value="<?php echo $item['inventory_ID']; ?>">
                            <input type="number" name="quantity_change" placeholder="Qty" required style="width: 60px;">
                            <button type="submit" name="adjust_quantity" value="1" class="btn btn-success">+</button>
                        </form>
                        <form method="post" class="action-form" style="display: inline;">
                            <input type="hidden" name="inventory_id" value="<?php echo $item['inventory_ID']; ?>">
                            <input type="hidden" name="quantity_change" value="-3"> <!-- Automatically deduct by 3 -->
                            <button type="submit" name="adjust_quantity" class="btn btn-danger">-</button>
                        </form>

                        <!-- Edit Product -->
                        <button class="btn btn-warning" onclick="toggleEditForm(<?php echo $item['inventory_ID']; ?>)">Edit</button>

                        <!-- Delete Product -->
                        <form method="post" class="action-form" style="display: inline;">
                            <input type="hidden" name="inventory_id" value="<?php echo $item['inventory_ID']; ?>">
                            <button type="submit" name="delete_product" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                <!-- Hidden Edit Form -->
                <tr id="edit-row-<?php echo $item['inventory_ID']; ?>" class="edit-row" style="display: none;">
                    <td colspan="6">
                        <form method="post" class="edit-form">
                            <input type="hidden" name="inventory_id" value="<?php echo $item['inventory_ID']; ?>">
                            <input type="text" name="product_name" value="<?php echo htmlspecialchars($item['product_name']); ?>" required>
                            <input type="text" name="product_id" value="<?php echo htmlspecialchars($item['product_ID']); ?>" required>
                            <input type="number" step="0.01" name="product_price" value="<?php echo htmlspecialchars($item['product_price']); ?>" required>
                            <input type="text" name="product_category" value="<?php echo htmlspecialchars($item['product_category']); ?>" required>
                            <button type="submit" name="edit_product" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-secondary" onclick="toggleEditForm(<?php echo $item['inventory_ID']; ?>)">Cancel</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    function toggleEditForm(id) {
        const editRow = document.getElementById(`edit-row-${id}`);
        editRow.style.display = editRow.style.display === 'none' ? 'table-row' : 'none';
    }
</script>
