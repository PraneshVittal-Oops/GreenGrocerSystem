<?php
session_start();
include('connect.php'); // Database connection

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    echo "Please log in to view your orders.";
    exit;
}

// Check if order_id is provided
if (empty($_GET['order_id'])) {
    echo "Order not found.";
    exit;
}

$orderID = intval($_GET['order_id']);
$customerID = $_SESSION['customer_id'];

// Fetch order details for the selected order
$sql = "SELECT OrderID, OrderDate, TotalAmount, PaymentStatus, DeliveryStatus 
        FROM Orders 
        WHERE OrderID = ? AND CustomerID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $orderID, $customerID);
$stmt->execute();
$orderResult = $stmt->get_result();

if ($orderResult->num_rows === 0) {
    echo "Order not found or access denied.";
    exit;
}
$order = $orderResult->fetch_assoc();

// Fetch all products in the selected order
$sql = "SELECT p.ProductName, p.Price, op.Quantity 
        FROM OrderProduct op
        JOIN Products p ON op.ProductID = p.ProductID
        WHERE op.OrderID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orderID);
$stmt->execute();
$productResult = $stmt->get_result();

// Include the header
include('header2.php');
?>
<div class="container mt-5">
    <h2 class="text-center">Order Details</h2>

    <div class="card mb-4">
        <div class="card-header">Order Information</div>
        <div class="card-body">
            <p><strong>Order ID:</strong> <?= htmlspecialchars($order['OrderID']); ?></p>
            <p><strong>Order Date:</strong> <?= htmlspecialchars($order['OrderDate']); ?></p>
            <p><strong>Total Amount:</strong> $<?= number_format($order['TotalAmount'], 2); ?></p>
            <p><strong>Payment Status:</strong> <?= htmlspecialchars($order['PaymentStatus']); ?></p>
            <p><strong>Delivery Status:</strong> <?= htmlspecialchars($order['DeliveryStatus']); ?></p>
        </div>
    </div>

    <h3>Products in this Order</h3>
    <?php if ($productResult->num_rows > 0): ?>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $orderTotal = 0; 
                while ($product = $productResult->fetch_assoc()): 
                    $productTotal = $product['Price'] * $product['Quantity'];
                    $orderTotal += $productTotal; 
                ?>
                    <tr>
                        <td><?= htmlspecialchars($product['ProductName']); ?></td>
                        <td>$<?= number_format($product['Price'], 2); ?></td>
                        <td><?= htmlspecialchars($product['Quantity']); ?></td>
                        <td>$<?= number_format($productTotal, 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="text-right">
            <h4><strong>Total Order Amount: $<?= number_format($orderTotal, 2); ?></strong></h4>
        </div>
    <?php else: ?>
        <p>No products found in this order.</p>
    <?php endif; ?>
</div>

<?php 
include('footer2.php');
?>


