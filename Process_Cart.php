<?php
session_start();
include('connect.php'); // Database connection

// Ensure the user is logged in
if (!isset($_SESSION['customer_id'])) {
    $_SESSION['cart_message'] = "Please log in to manage your cart.";
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Helper function to handle errors and redirect
function handleError($message, $redirect = "userpage.php") {
    $_SESSION['cart_message'] = $message;
    error_log($message); // Log error for debugging
    header("Location: $redirect");
    exit();
}

// Function to get or create an active cart for the user
function getActiveCart($customerID, $conn) {
    try {
        // Check for existing active cart
        $sql = "SELECT CartID FROM cart WHERE CustomerID = ? AND CartStatus = 'active'";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare query: " . $conn->error);
        }
        $stmt->bind_param("i", $customerID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Return existing cart ID
            return $result->fetch_assoc()['CartID'];
        } else {
            // Create a new active cart
            $sql = "INSERT INTO cart (CustomerID, CartStatus, TotalItems, TotalPrice) VALUES (?, 'active', 0, 0)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare query for cart creation: " . $conn->error);
            }
            $stmt->bind_param("i", $customerID);
            if ($stmt->execute()) {
                return $stmt->insert_id; // Return new cart ID
            } else {
                throw new Exception("Error creating cart: " . $stmt->error);
            }
        }
    } catch (Exception $e) {
        handleError($e->getMessage());
    }
}

// Get active cart for the logged-in user
$cartID = getActiveCart($customer_id, $conn);

// Add product to the cart
if (isset($_GET['add'])) {
    $productID = filter_var($_GET['add'], FILTER_VALIDATE_INT);
    if ($productID) {
        try {
            // Get the product details
            $sql = "SELECT ProductID, Price FROM Products WHERE ProductID = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare query: " . $conn->error);
            }
            $stmt->bind_param("i", $productID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $product = $result->fetch_assoc();

                // Check if the product already exists in the cart
                $checkCartItemSql = "SELECT Quantity FROM cart_items WHERE CartID = ? AND ProductID = ?";
                $stmt = $conn->prepare($checkCartItemSql);
                if (!$stmt) {
                    throw new Exception("Failed to prepare query: " . $conn->error);
                }
                $stmt->bind_param("ii", $cartID, $productID);
                $stmt->execute();
                $checkResult = $stmt->get_result();

                if ($checkResult->num_rows > 0) {
                    // Update product quantity
                    $cartItem = $checkResult->fetch_assoc();
                    $newQuantity = $cartItem['Quantity'] + 1;

                    $updateCartItemSql = "UPDATE cart_items SET Quantity = ? WHERE CartID = ? AND ProductID = ?";
                    $stmt = $conn->prepare($updateCartItemSql);
                    $stmt->bind_param("iii", $newQuantity, $cartID, $productID);
                    $stmt->execute();
                } else {
                    // Add new product to cart
                    $sql = "INSERT INTO cart_items (CartID, ProductID, Quantity) VALUES (?, ?, 1)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ii", $cartID, $productID);
                    $stmt->execute();
                }

                // Update cart totals
                $sql = "UPDATE cart SET TotalItems = TotalItems + 1, TotalPrice = TotalPrice + ? WHERE CartID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("di", $product['Price'], $cartID);
                $stmt->execute();

                $_SESSION['cart_message'] = "Product added to your cart!";
            } else {
                throw new Exception("Product not found.");
            }
        } catch (Exception $e) {
            handleError($e->getMessage());
        }
    } else {
        handleError("Invalid product ID.");
    }
    header("Location: userpage.php");
    exit();
}

// Remove product from the cart
if (isset($_GET['remove'])) {
    $productID = filter_var($_GET['remove'], FILTER_VALIDATE_INT);
    if ($productID) {
        try {
            $sql = "SELECT Products.Price, cart_items.Quantity 
                    FROM Products 
                    JOIN cart_items ON Products.ProductID = cart_items.ProductID 
                    WHERE cart_items.CartID = ? AND cart_items.ProductID = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare query: " . $conn->error);
            }
            $stmt->bind_param("ii", $cartID, $productID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $product = $result->fetch_assoc();
                $totalPriceReduction = $product['Price'] * $product['Quantity'];

                // Remove product from cart_items
                $sql = "DELETE FROM cart_items WHERE CartID = ? AND ProductID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $cartID, $productID);
                $stmt->execute();

                // Update cart totals
                $sql = "UPDATE cart SET TotalItems = TotalItems - ?, TotalPrice = TotalPrice - ? WHERE CartID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("idi", $product['Quantity'], $totalPriceReduction, $cartID);
                $stmt->execute();

                $_SESSION['cart_message'] = "Product removed from your cart!";
            } else {
                throw new Exception("Product not found in the cart.");
            }
        } catch (Exception $e) {
            handleError($e->getMessage());
        }
    } else {
        handleError("Invalid product ID.");
    }
    header("Location: userpage.php");
    exit();
}
?>
