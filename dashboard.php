<?php
include 'header.php'; // Include the sidebar header
require 'db_connect.php'; // Connect to the database

// Fetch total number of inventory items
$inventory_query = "SELECT COUNT(*) AS total_items FROM tbl_inventory";
$inventory_result = mysqli_query($conn, $inventory_query);
$total_items = mysqli_fetch_assoc($inventory_result)['total_items'];

// Fetch total number of categories
$categories_query = "SELECT COUNT(DISTINCT product_category) AS total_categories FROM tbl_inventory";
$categories_result = mysqli_query($conn, $categories_query);
$total_categories = mysqli_fetch_assoc($categories_result)['total_categories'];

// Fetch total number of orders
$orders_query = "SELECT COUNT(*) AS total_orders FROM tbl_transaction WHERE transaction_type = 'Purchase'";
$orders_result = mysqli_query($conn, $orders_query);
$total_orders = mysqli_fetch_assoc($orders_result)['total_orders'];
?>

<div id="dashboard" class="main-content">
    <h1>Dashboard</h1>
    <p>Welcome to the Inventory Management System dashboard!</p>
    <div class="dashboard-cards">
        <div class="card blue">
            <h2>Total Inventory Items</h2>
            <p><?php echo $total_items; ?></p>
        </div>
        <div class="card green">
            <h2>Total Categories</h2>
            <p><?php echo $total_categories; ?></p>
        </div>
        <div class="card cyan">
            <h2>Total Orders</h2>
            <p><?php echo $total_orders; ?></p>
        </div>
    </div>
</div>
