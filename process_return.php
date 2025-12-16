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

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['return_order'])) {
    $orderID = $_POST['order_id'];
    $productID = $_POST['product_id'];
    $reason = $_POST['reason_for_return'];

    // Validate inputs
    if (empty($orderID) || empty($productID) || empty($reason)) {
        $_SESSION['message'] = "Please fill in all the fields.";
        $_SESSION['alert-class'] = "alert-danger";
        header("Location: return.php");
        exit;
    }

    // Insert return record
    $sql = "INSERT INTO Returns (OrderID, ProductID, ReasonForReturn) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Check if the insert query was prepared successfully
    if ($stmt === false) {
        $_SESSION['message'] = "Failed to prepare the query: " . $conn->error;
        $_SESSION['alert-class'] = "alert-danger";
        header("Location:userpage.php");
        exit;
    }

    $stmt->bind_param("iis", $orderID, $productID, $reason);

    if ($stmt->execute()) {
        // Update order and delivery status
        $updateDeliveries = "UPDATE Deliveries SET DeliveryStatus = 'Cancelled' WHERE OrderID = ?";
        $updateOrders = "UPDATE Orders SET DeliveryStatus = 'Cancelled' WHERE OrderID = ?";

        // Prepare and execute the update statements
        $stmtDelivery = $conn->prepare($updateDeliveries);
        $stmtOrders = $conn->prepare($updateOrders);

        if ($stmtDelivery === false || $stmtOrders === false) {
            $_SESSION['message'] = "Failed to prepare the update queries: " . $conn->error;
            $_SESSION['alert-class'] = "alert-danger";
            header("Location: userpage.php");
            exit;
        }

        $stmtDelivery->bind_param("i", $orderID);
        $stmtOrders->bind_param("i", $orderID);

        if ($stmtDelivery->execute() && $stmtOrders->execute()) {
            $_SESSION['message'] = "Order returned successfully! Delivery status set to 'Cancelled'.";
            $_SESSION['alert-class'] = "alert-success";
        } else {
            $_SESSION['message'] = "Failed to update the status: " . $stmtDelivery->error;
            $_SESSION['alert-class'] = "alert-danger";
        }
    } else {
        $_SESSION['message'] = "Failed to process return: " . $stmt->error;
        $_SESSION['alert-class'] = "alert-danger";
    }

    header("Location:userpage.php");
    exit;
}
?>
