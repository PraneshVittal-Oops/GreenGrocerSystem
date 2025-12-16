<?php 
session_start();
include('connect.php'); // Database connection

// Display success message if available
if (isset($_SESSION['message'])) {
    echo "<div class='alert " . htmlspecialchars($_SESSION['alert-class']) . " text-center'>" . 
         htmlspecialchars($_SESSION['message']) . 
         "</div>";
    // Unset the message after displaying it
    unset($_SESSION['message']);
    unset($_SESSION['alert-class']);
}

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    die("Please log in to proceed with checkout.");
}


// Fetch the active cart for the user
$sql = "SELECT CartID, TotalItems, TotalPrice, CartStatus FROM cart WHERE CustomerID = ? AND CartStatus = 'active'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['customer_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $cart = $result->fetch_assoc();
    $cartID = $cart['CartID'];
    $cartStatus = $cart['CartStatus']; // Fetch CartStatus
} else {
    echo "No active cart found.";
    exit;
}

// Fetch cart items
$sql = "SELECT ci.ProductID, p.ProductName, p.Price, ci.Quantity 
        FROM cart_items ci 
        JOIN Products p ON ci.ProductID = p.ProductID 
        WHERE ci.CartID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cartID);
$stmt->execute();
$itemsResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">My Cart Details</h2>
        
        <!-- Display session messages -->
        <?php if (isset($_SESSION['cart_message'])): ?>
            <div class="alert alert-info">
                <?= htmlspecialchars($_SESSION['cart_message']); ?>
            </div>
            <?php unset($_SESSION['cart_message']); ?>
        <?php endif; ?>

        <p><strong>Cart Status: </strong> <?= htmlspecialchars($cartStatus); ?></p>

        <?php if ($itemsResult->num_rows > 0): ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $itemsResult->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['ProductName']); ?></td>
                            <td>$<?= number_format($item['Price'], 2); ?></td>
                            <td>
                                <!-- Form to update quantity -->
                                <form action="update.php" method="POST" style="display: inline;">
                                    <input 
                                        type="number" 
                                        name="quantity" 
                                        value="<?= $item['Quantity']; ?>" 
                                        min="0" 
                                        max="99" 
                                        oninput="if(this.value<0){this.value=0;} if(this.value>99){this.value=99;}" 
                                        style="width: 60px;">
                                    <input type="hidden" name="product_id" value="<?= $item['ProductID']; ?>">
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                </form>
                            </td>
                            <td>$<?= number_format($item['Price'] * $item['Quantity'], 2); ?></td>
                            <td>
                                <a href="Process_Cart.php?remove=<?= $item['ProductID']; ?>" class="btn btn-danger btn-sm">Remove</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <tr>
                        <td colspan="3"><strong>Total</strong></td>
                        <td><strong>$<?= number_format($cart['TotalPrice'], 2); ?></strong></td>
                        <td><strong><?= $cart['TotalItems']; ?> items</strong></td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>

        <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
