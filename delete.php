<?php
session_start();
include('connect.php'); // Database connection

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    $_SESSION['message'] = "Please log in to delete orders.";
    $_SESSION['alert-class'] = "alert-danger";
    header("Location: order.php");
    exit;
}

// Check if the order ID is provided
if (!isset($_GET['order_id'])) {
    $_SESSION['message'] = "No order selected for deletion.";
    $_SESSION['alert-class'] = "alert-danger";
    header("Location: order.php");
    exit;
}

$orderID = intval($_GET['order_id']);

// Verify if the order belongs to the logged-in user
$sql = "SELECT * FROM Orders WHERE OrderID = ? AND CustomerID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $orderID, $_SESSION['customer_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['message'] = "Order not found or does not belong to you.";
    $_SESSION['alert-class'] = "alert-danger";
    header("Location: order.php");
    exit;
}

// Delete the order and its associated items
$sql = "DELETE FROM OrderProduct WHERE OrderID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orderID);
$stmt->execute();

$sql = "DELETE FROM Orders WHERE OrderID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orderID);

if ($stmt->execute()) {
    $_SESSION['message'] = "Order deleted successfully.";
    $_SESSION['alert-class'] = "alert-success";
} else {
    $_SESSION['message'] = "Error deleting order: " . $stmt->error;
    $_SESSION['alert-class'] = "alert-danger";
}

header("Location: userpage.php");
exit;
?>
