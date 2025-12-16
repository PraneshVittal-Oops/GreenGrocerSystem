<?php   
session_start();
include('connect.php'); // Database connection

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    die("Please log in to proceed with checkout.");
}

// Debug: Output session user ID (for development purposes; remove in production)
echo "Session User ID: " . $_SESSION['customer_id'] . "<br>";

// Verify if CustomerID exists in the database
$sql = "SELECT * FROM customer WHERE customerID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['customer_id']);
$stmt->execute();
$customerResult = $stmt->get_result();

if ($customerResult->num_rows === 0) {
    die("Error: Customer ID not found in the database.");
}

// Fetch cart data for the logged-in user
$sql = "SELECT CartID, TotalPrice FROM cart WHERE CustomerID = ? AND CartStatus = 'active'";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("i", $_SESSION['customer_id']);
$stmt->execute();
$cartResult = $stmt->get_result();

if ($cartResult->num_rows === 0) {
    die("No active cart found.");
}

$cart = $cartResult->fetch_assoc();
$cartID = $cart['CartID'];
$totalPrice = $cart['TotalPrice'];

// Fetch the products from the cart
$sql = "SELECT ProductID, Quantity FROM cart_items WHERE CartID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cartID);
$stmt->execute();
$cartItemsResult = $stmt->get_result();

// Check if the cart contains any items
if ($cartItemsResult->num_rows === 0) {
    die("Your cart is empty. Please add items to your cart before proceeding with checkout.");
}

// Insert the order into the Orders table
$orderDate = date('Y-m-d H:i:s');
$paymentStatus = 'Pending';
$deliveryStatus = 'Pending';

$sql = "INSERT INTO Orders (CustomerID, OrderDate, TotalAmount, PaymentStatus, DeliveryStatus) 
        VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("SQL Prepare Failed: " . $conn->error);
}

$stmt->bind_param("isdss", $_SESSION['customer_id'], $orderDate, $totalPrice, $paymentStatus, $deliveryStatus);

if ($stmt->execute()) {
    // If the order is placed successfully, get the Order ID
    $orderID = $conn->insert_id;

    // Debug: Output Order ID (for development purposes; remove in production)
    echo "Order placed successfully! Order ID: " . $orderID;

    // Now insert the products from the cart into the OrderProduct table
    $productsInOrder = []; // To track products in the order

    while ($item = $cartItemsResult->fetch_assoc()) {
        $productID = $item['ProductID'];
        $quantity = $item['Quantity'];

        // Check if the product exists in the Products table
        $sql = "SELECT ProductID FROM Products WHERE ProductID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productID);
        $stmt->execute();
        $productCheck = $stmt->get_result();

        if ($productCheck->num_rows === 0) {
            die("Error: Product ID $productID not found in the Products table.");
        }

        // Insert the product into the OrderProduct table
        $sql = "INSERT INTO OrderProduct (OrderID, ProductID, Quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $orderID, $productID, $quantity);

        if (!$stmt->execute()) {
            die("Error inserting product into OrderProduct table. Details: " . $stmt->error);
        }

        // Track the inserted product
        $productsInOrder[] = $productID;
    }

    // Check if the products in the cart match the products in the order
    if (count($productsInOrder) !== $cartItemsResult->num_rows) {
        die("Error: Mismatch between cart products and order products.");
    }

    // Set a success message in the session
    $_SESSION['message'] = "Order placed successfully! Your Order ID is: $orderID";
    $_SESSION['alert-class'] = "alert-success";

    // Redirect to userpage.php
    header("Location: userpage.php");
    exit();
} else {
    die("Error placing order. Details: " . $stmt->error);
}
?>



