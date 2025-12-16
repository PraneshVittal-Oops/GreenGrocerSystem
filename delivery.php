<?php 
session_start();
include('connect.php'); // Database connection

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    $_SESSION['message'] = "Please log in to enter delivery information.";
    $_SESSION['alert-class'] = "alert-danger";
    header("Location: login.php");
    exit;
}

// Fetch available delivery person
$deliveryPerson = null;
$sql = "SELECT DeliveryPersonID, FullName FROM DeliveryPersonnel WHERE Status = 'Active' LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $deliveryPerson = $result->fetch_assoc();
} else {
    // If no delivery person found
    $_SESSION['message'] = "No active delivery personnel available.";
    $_SESSION['alert-class'] = "alert-danger";
}

// Fetch pending order IDs for the user
$sql = "SELECT OrderID FROM Orders WHERE CustomerID = ? AND PaymentStatus = 'Pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['customer_id']);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Delivery Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-center">Delivery Information</h2>
            <a href="viewdeliveryinfo.php" class="btn btn-secondary">View</a>
        </div>

        <!-- Session Message -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?= $_SESSION['alert-class']; ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['alert-class']); ?>
        <?php endif; ?>

        <form action="process_deliveries.php" method="POST">
            <div class="mb-3">
                <label for="order_id" class="form-label">Select Order</label>
                <select id="order_id" name="order_id" class="form-select" required>
                    <?php foreach ($orders as $order): ?>
                        <option value="<?= $order['OrderID']; ?>">Order #<?= $order['OrderID']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="delivery_address" class="form-label">Delivery Address</label>
                <textarea id="delivery_address" name="delivery_address" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="delivery_date" class="form-label">Delivery Date</label>
                <input type="date" id="delivery_date" name="delivery_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="assigned_person" class="form-label">Assigned Delivery Person</label>
                <input type="text" id="assigned_person" class="form-control" 
                       value="<?= htmlspecialchars($deliveryPerson['FullName'] ?? 'No available person'); ?>" readonly>
            </div>
            <button type="submit" class="btn btn-primary w-100">Save Delivery Information</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
