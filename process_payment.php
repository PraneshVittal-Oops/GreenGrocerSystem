<?php
session_start();
include('connect.php'); // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in
    if (!isset($_SESSION['customer_id'])) {
        $_SESSION['message'] = "Please log in to proceed with payment.";
        $_SESSION['alert-class'] = "alert-danger";
        header("Location: payment.php");
        exit;
    }

    $customerId = $_SESSION['customer_id'];
    $paymentMethod = $_POST['payment_method'];
    $amountPaid = $_POST['amount_paid'];

    // Fetch total pending amount
    $sql = "SELECT SUM(TotalAmount) AS TotalOrderAmount FROM Orders WHERE CustomerID = ? AND PaymentStatus = 'Pending'";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Error (Fetch total amount): " . $conn->error);
    }
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    $totalAmount = $row['TotalOrderAmount'] ?? 0;

    // Check if the amount paid is sufficient
    if ($amountPaid < $totalAmount) {
        $_SESSION['message'] = "Insufficient payment! Please top up the remaining amount.";
        $_SESSION['alert-class'] = "alert-warning";
        header("Location: userpage.php");
        exit;
    }

    // Process payment for all pending orders
    $orderIdQuery = "SELECT OrderID FROM Orders WHERE CustomerID = ? AND PaymentStatus = 'Pending'";
    $orderStmt = $conn->prepare($orderIdQuery);
    if (!$orderStmt) {
        die("SQL Error (Fetch orders): " . $conn->error);
    }
    $orderStmt->bind_param("i", $customerId);
    $orderStmt->execute();
    $orderResult = $orderStmt->get_result();
    $orderStmt->close();

    while ($order = $orderResult->fetch_assoc()) {
        $orderId = $order['OrderID'];

        // Insert or update payment record
        $insertPayment = "
            INSERT INTO Payment (OrderID, PaymentMethod, AmountPaid, PaymentStatus, PaymentDate)
            VALUES (?, ?, ?, 'Paid', CURRENT_TIMESTAMP)
            ON DUPLICATE KEY UPDATE
                PaymentMethod = VALUES(PaymentMethod),
                AmountPaid = VALUES(AmountPaid),
                PaymentStatus = 'Paid',
                PaymentDate = CURRENT_TIMESTAMP
        ";
        $paymentStmt = $conn->prepare($insertPayment);
        if (!$paymentStmt) {
            die("SQL Error (Insert payment): " . $conn->error);
        }
        $paymentStmt->bind_param("isd", $orderId, $paymentMethod, $amountPaid);
        if (!$paymentStmt->execute()) {
            die("Execution Error (Insert payment): " . $paymentStmt->error);
        }
        $paymentStmt->close();

        // Update order payment status
        $updateOrder = "UPDATE Orders SET PaymentStatus = 'Paid' WHERE OrderID = ?";
        $updateStmt = $conn->prepare($updateOrder);
        if (!$updateStmt) {
            die("SQL Error (Update order status): " . $conn->error);
        }
        $updateStmt->bind_param("i", $orderId);
        if (!$updateStmt->execute()) {
            die("Execution Error (Update order status): " . $updateStmt->error);
        }
        $updateStmt->close();
    }

    $_SESSION['message'] = "Payment successful!";
    $_SESSION['alert-class'] = "alert-success";
    header("Location: userpage.php");
    exit;
} else {
    $_SESSION['message'] = "Invalid payment request.";
    $_SESSION['alert-class'] = "alert-danger";
    header("Location: payment.php");
    exit;
}
?>
