<?php  
include('connect.php'); // Database connection
session_start(); // Start
try {
    // Fetch all orders for admin
    $sql = "SELECT OrderID, CustomerID, OrderDate, TotalAmount, PaymentStatus, DeliveryStatus 
            FROM Orders 
            ORDER BY OrderDate DESC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Failed to prepare SQL statement: " . $conn->error);
    }

    if (!$stmt->execute()) {
        throw new Exception("Failed to execute SQL statement: " . $stmt->error);
    }

    $orderResult = $stmt->get_result();
    if (!$orderResult) {
        throw new Exception("Failed to fetch order results: " . $stmt->error);
    }

    // Calculate total orders and total amount
    $totalOrders = $orderResult->num_rows;
    $totalAmount = 0;
    while ($order = $orderResult->fetch_assoc()) {
        $totalAmount += $order['TotalAmount'];
    }

    // Reset result pointer for displaying orders
    $stmt->execute();
    $orderResult = $stmt->get_result();
} catch (Exception $e) {
    error_log($e->getMessage()); // Log the error
    echo "<p>An error occurred while fetching orders. Please try again later.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <!-- Admin Message -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?= htmlspecialchars($_SESSION['alert-class']); ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['alert-class']); ?>
        <?php endif; ?>

        <h2 class="text-center">Order Management</h2>

        <!-- Total Orders and Amount -->
        <div class="mb-4">
            <h4>Total Orders: <?= $totalOrders; ?></h4>
            <h4>Total Revenue: $<?= number_format((float)$totalAmount, 2); ?></h4>
        </div>

        <!-- Order Details Table -->
        <?php if ($orderResult->num_rows > 0): ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer ID</th>
                        <th>Order Date</th>
                        <th>Total Amount</th>
                        <th>Payment Status</th>
                        <th>Delivery Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $orderResult->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['OrderID']); ?></td>
                            <td><?= htmlspecialchars($order['CustomerID']); ?></td>
                            <td><?= htmlspecialchars($order['OrderDate']); ?></td>
                            <td>$<?= number_format((float)$order['TotalAmount'], 2); ?></td>
                            <td><?= htmlspecialchars($order['PaymentStatus']); ?></td>
                            <td><?= htmlspecialchars($order['DeliveryStatus']); ?></td>
                            <td>
                                <a href="admindeleteorder.php?order_id=<?= urlencode($order['OrderID']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this order?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No orders available.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


