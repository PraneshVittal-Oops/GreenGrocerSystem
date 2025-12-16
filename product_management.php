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

        <!-- Form to Add Product and Supplier -->
        <h3 class="mt-5 text-center">Add New Product and Supplier</h3>
        <form action="add_product_supplier.php" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <h5>Add Product</h5>
                    <div class="mb-3">
                        <label for="productName" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="productName" name="productName" required>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" class="form-control" id="category" name="category" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="stockLevel" class="form-label">Stock Level</label>
                        <input type="number" class="form-control" id="stockLevel" name="stockLevel" required>
                    </div>
                    <div class="mb-3">
                        <label for="imagePath" class="form-label">Image Path</label>
                        <input type="text" class="form-control" id="imagePath" name="imagePath" placeholder="image/me.jpg" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5>Add Supplier</h5>
                    <div class="mb-3">
                        <label for="supplierName" class="form-label">Supplier Name</label>
                        <input type="text" class="form-control" id="supplierName" name="supplierName" required>
                    </div>
                    <div class="mb-3">
                        <label for="contactPerson" class="form-label">Contact Person</label>
                        <input type="text" class="form-control" id="contactPerson" name="contactPerson" required>
                    </div>
                    <div class="mb-3">
                        <label for="contactEmail" class="form-label">Contact Email</label>
                        <input type="email" class="form-control" id="contactEmail" name="contactEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="contactPhone" class="form-label">Contact Phone</label>
                        <input type="tel" class="form-control" id="contactPhone" name="contactPhone" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" required></textarea>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success w-100">Add Product and Supplier</button>
        </form>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
