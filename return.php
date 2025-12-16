<?php 
session_start();
include('connect.php'); // Database connection

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    $_SESSION['message'] = "Please log in to access this feature.";
    $_SESSION['alert-class'] = "alert-danger";
    header("Location: login.php");
    exit;
}
// Fetch user's orders and products
$customerID = $_SESSION['customer_id'];
$sql = "SELECT o.OrderID, op.ProductID, p.ProductName 
        FROM Orders o
        JOIN OrderProduct op ON o.OrderID = op.OrderID
        JOIN Products p ON op.ProductID = p.ProductID
        WHERE o.CustomerID = ? AND o.DeliveryStatus != 'Cancelled'";
$stmt = $conn->prepare($sql);

// Check if the query was prepared successfully
if ($stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}

$stmt->bind_param("i", $customerID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
} else {
    $orders = []; // No orders found
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return an Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Return an Order</h2>

        <!-- Session Message -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?= $_SESSION['alert-class']; ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['alert-class']); ?>
        <?php endif; ?>

        <!-- Return Form -->
        <form method="POST" action="process_return.php">
            <div class="mb-3">
                <label for="order_id" class="form-label">Select Order</label>
                <select class="form-control" id="order_id" name="order_id" required>
                    <option value="" disabled selected>Choose an Order</option>
                    <?php foreach ($orders as $order): ?>
                        <option value="<?= $order['OrderID']; ?>">
                            Order #<?= $order['OrderID']; ?> - <?= $order['ProductName']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="product_id" class="form-label">Select Product</label>
                <select class="form-control" id="product_id" name="product_id" required>
                    <option value="" disabled selected>Choose a Product</option>
                    <?php foreach ($orders as $order): ?>
                        <option value="<?= $order['ProductID']; ?>">
                            <?= $order['ProductName']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="reason_for_return" class="form-label">Reason for Return</label>
                <textarea class="form-control" id="reason_for_return" name="reason_for_return" rows="4" required></textarea>
            </div>
            <button type="submit" name="return_order" class="btn btn-danger">Return Order</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
