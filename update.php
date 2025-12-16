<?php
session_start();
include('connect.php'); // Database connection

// Ensure user is logged in
if (!isset($_SESSION['customer_id'])) {
    $_SESSION['cart_message'] = "Please log in to update your cart.";
    header("Location: Cart.php");
    exit;
}

// Validate request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $productID = filter_var($_POST['product_id'], FILTER_VALIDATE_INT);
    $newQuantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);

    if ($productID && $newQuantity !== false && $newQuantity >= 0) {
        // Fetch the user's active cart
        $sql = "SELECT CartID FROM cart WHERE CustomerID = ? AND CartStatus = 'active'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION['customer_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $cart = $result->fetch_assoc();
            $cartID = $cart['CartID'];

            if ($newQuantity == 0) {
                // Remove item if quantity is 0
                $sql = "DELETE FROM cart_items WHERE CartID = ? AND ProductID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $cartID, $productID);
                $stmt->execute();
            } else {
                // Update item quantity
                $sql = "UPDATE cart_items SET Quantity = ? WHERE CartID = ? AND ProductID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iii", $newQuantity, $cartID, $productID);
                $stmt->execute();
            }

            // Recalculate cart totals
            $sql = "UPDATE cart 
                    SET TotalItems = (SELECT IFNULL(SUM(Quantity), 0) FROM cart_items WHERE CartID = ?),
                        TotalPrice = (SELECT IFNULL(SUM(ci.Quantity * p.Price), 0) 
                                      FROM cart_items ci 
                                      JOIN Products p ON ci.ProductID = p.ProductID 
                                      WHERE ci.CartID = ?)
                    WHERE CartID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $cartID, $cartID, $cartID);
            $stmt->execute();

            $_SESSION['cart_message'] = "Cart updated successfully!";
        } else {
            $_SESSION['cart_message'] = "No active cart found.";
        }
    } else {
        $_SESSION['cart_message'] = "Invalid product or quantity.";
    }
} else {
    $_SESSION['cart_message'] = "Invalid request.";
}

// Redirect back to Cart.php
header("Location: userpage.php");
exit;
?>
