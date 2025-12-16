<?php  
session_start();
include('connect.php'); // Database connection

// Ensure user is logged in
if (!isset($_SESSION['customer_id'])) {
    echo "<p>Please log in to view your orders.</p>";
    exit;
}

try {
    // Fetch orders for the logged-in user
    $sql = "SELECT OrderID, OrderDate, TotalAmount, PaymentStatus, DeliveryStatus 
            FROM Orders 
            WHERE CustomerID = ? 
            ORDER BY OrderDate DESC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Failed to prepare SQL statement: " . $conn->error);
    }

    $stmt->bind_param("i", $_SESSION['customer_id']);
    if (!$stmt->execute()) {
        throw new Exception("Failed to execute SQL statement: " . $stmt->error);
    }

    $orderResult = $stmt->get_result();
    if (!$orderResult) {
        throw new Exception("Failed to fetch order results: " . $stmt->error);
    }

    // Calculate the total amount of all orders
    $totalAmount = 0;
    while ($order = $orderResult->fetch_assoc()) {
        $totalAmount += $order['TotalAmount'];
    }

    // Reset result pointer for displaying orders again
    $stmt->execute();
    $orderResult = $stmt->get_result();
} catch (Exception $e) {
    error_log($e->getMessage()); // Log the error to a file
    echo "<p>An error occurred while fetching your orders. Please try again later.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <!-- Session Message -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?= htmlspecialchars($_SESSION['alert-class']); ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['alert-class']); ?>
        <?php endif; ?>

        <h2 class="text-center">My Orders</h2>

        <!-- Order Details -->
        <?php if ($orderResult->num_rows > 0): ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
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
                            <td><?= htmlspecialchars($order['OrderDate']); ?></td>
                            <td>$<?= number_format((float)$order['TotalAmount'], 2); ?></td>
                            <td><?= htmlspecialchars($order['PaymentStatus']); ?></td>
                            <td><?= htmlspecialchars($order['DeliveryStatus']); ?></td>
                            <td>
                                <a href="order_details.php?order_id=<?= urlencode($order['OrderID']); ?>" class="btn btn-primary btn-sm">View</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div class="text-right mt-3">
                <h4>Total Amount: $<?= number_format((float)$totalAmount, 2); ?></h4>
            </div>
        <?php else: ?>
            <p>You have no orders.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
