<?php
session_start();
include('connect.php'); // Database connection

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    $_SESSION['message'] = "Please log in to proceed with payment.";
    $_SESSION['alert-class'] = "alert-danger";
    header("Location: order.php");
    exit;
}

// Fetch total amount from orders
$sql = "SELECT SUM(TotalAmount) AS TotalOrderAmount FROM Orders WHERE CustomerID = ? AND PaymentStatus = 'Pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['customer_id']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$totalAmount = $row['TotalOrderAmount'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Make Payment</h2>

        <!-- Session Message -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?= $_SESSION['alert-class']; ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['alert-class']); ?>
        <?php endif; ?>

        <form method="POST" action="process_payment.php">
            <div class="mb-3">
                <label for="total_amount" class="form-label">Total Order Amount</label>
                <input type="text" id="total_amount" class="form-control" value="$<?= number_format($totalAmount, 2); ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="payment_method" class="form-label">Payment Method</label>
                <select id="payment_method" name="payment_method" class="form-select" required>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="PayPal">PayPal</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="amount_paid" class="form-label">Amount to Pay</label>
                <input type="number" id="amount_paid" name="amount_paid" class="form-control" placeholder="Enter amount to pay" required>
            </div>

            <button type="submit" class="btn btn-success w-100">Pay Now</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


