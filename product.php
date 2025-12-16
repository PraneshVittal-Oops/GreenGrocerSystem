<?php
session_start();
include('connect.php'); // Include your database connection file

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenGrocer Product Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Our Products</h2>
        
        <?php
        // Display the cart success message if it exists
        if (isset($_SESSION['cart_message'])) {
            echo '<div class="alert alert-success" role="alert">' . $_SESSION['cart_message'] . '</div>';
            unset($_SESSION['cart_message']); // Unset the session message after displaying
        }
        ?>
        <div class="row">
            <?php
            // Query to fetch product and supplier data
            $sql = "SELECT 
                        p.ProductID, 
                        p.ProductName, 
                        p.Category, 
                        p.Price, 
                        p.StockLevel, 
                        p.ImagePath,
                        s.SupplierName, 
                        s.ContactPerson, 
                        s.ContactEmail, 
                        s.ContactPhone, 
                        s.Address
                    FROM Products p
                    JOIN Suppliers s ON p.SupplierID = s.SupplierID";
            $result = $conn->query($sql);

            // Check if products exist
            if ($result && $result->num_rows > 0) {
                while ($product = $result->fetch_assoc()): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card h-100">
                            <img src="<?= htmlspecialchars($product['ImagePath']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['ProductName']) ?>" onerror="this.src='image/default.webp';">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($product['ProductName']) ?></h5>
                                <p class="card-text"><strong>Category:</strong> <?= htmlspecialchars($product['Category']) ?></p>
                                <p class="card-text"><strong>Price:</strong> $<?= number_format($product['Price'], 2) ?></p>
                                <p class="card-text"><strong>Stock Level:</strong> <?= htmlspecialchars($product['StockLevel']) ?></p>
                                <div class="mt-auto">
                                    <a href="Process_Cart.php?add=<?= $product['ProductID'] ?>" class="btn btn-primary w-100 mb-2">Add to Cart</a>
                                    <button class="btn btn-secondary w-100" data-bs-toggle="modal" data-bs-target="#supplierModal<?= htmlspecialchars($product['ProductID']) ?>">View Supplier</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Supplier Modal -->
                    <div class="modal fade" id="supplierModal<?= htmlspecialchars($product['ProductID']) ?>" tabindex="-1" aria-labelledby="supplierModalLabel<?= htmlspecialchars($product['ProductID']) ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="supplierModalLabel<?= htmlspecialchars($product['ProductID']) ?>">Supplier: <?= htmlspecialchars($product['SupplierName']) ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Contact Person:</strong> <?= htmlspecialchars($product['ContactPerson'] ?: 'N/A') ?></p>
                                    <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($product['ContactEmail']) ?>"><?= htmlspecialchars($product['ContactEmail']) ?></a></p>
                                    <p><strong>Phone:</strong> <a href="tel:<?= htmlspecialchars($product['ContactPhone']) ?>"><?= htmlspecialchars($product['ContactPhone']) ?></a></p>
                                    <p><strong>Address:</strong> <?= htmlspecialchars($product['Address']) ?></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile;
            } else { ?>
                <p class="text-center text-muted">No products available.</p>
            <?php }
            // Close the database connection
            $conn->close();
            ?>
        </div>
    </div>
    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
