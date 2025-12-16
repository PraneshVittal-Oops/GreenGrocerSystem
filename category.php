<?php  
session_start();
include('connect.php'); // Database connection

// Fetch categories and their products
$sql = "SELECT c.CategoryID, c.CategoryName, c.Description, p.ProductName, p.Price, p.StockLevel, p.ImagePath
        FROM Categories c
        LEFT JOIN Products p ON c.CategoryID = p.CategoryID  -- Join by CategoryID
        ORDER BY c.CategoryName";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories and Products</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Card Styling */
        .category-card {
            margin-bottom: 20px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .category-card:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            color: #fff;
            font-weight: bold;
            padding: 15px;
        }

        .product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #ddd;
        }

        .no-products {
            font-style: italic;
            color: #777;
            text-align: center;
            padding: 10px;
        }

        .product-info {
            font-size: 0.9rem;
            color: #555;
        }

        /* Adjust for horizontal layout */
        .horizontal-card {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 20px;
        }

        .card-content {
            flex: 1;
        }

        .product-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .product-list-item {
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<div class="container my-5">
    <h2 class="text-center mb-4">Categories and Products</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert <?= $_SESSION['alert-class']; ?>"><?= $_SESSION['message']; ?></div>
        <?php unset($_SESSION['message']); unset($_SESSION['alert-class']); ?>
    <?php endif; ?>

    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php
            $currentCategory = "";
            while ($row = $result->fetch_assoc()):
                if ($currentCategory != $row['CategoryName']):
                    if ($currentCategory != "") echo "</div>"; // Close previous category
                    $currentCategory = $row['CategoryName'];
            ?>
                    <div class="col-12 mb-3">
                        <div class="card category-card">
                            <div class="horizontal-card">
                                <div class="card-header">
                                    <?= htmlspecialchars($row['CategoryName']); ?>
                                </div>
                                <div class="card-content p-3">
                                    <p><?= htmlspecialchars($row['Description']); ?></p>
                                    <h6 class="text-muted">Products:</h6>
                                    <?php if ($row['ProductName']): ?>
                                        <div class="product-list">
                                            <div class="product-list-item">
                                                <img src="<?= htmlspecialchars($row['ImagePath']); ?>" alt="<?= htmlspecialchars($row['ProductName']); ?>" class="product-img">
                                                <div>
                                                    <strong><?= htmlspecialchars($row['ProductName']); ?></strong>
                                                    <div class="product-info">
                                                        Price: $<?= number_format($row['Price'], 2); ?><br>
                                                        Stock: <?= htmlspecialchars($row['StockLevel']); ?> available
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <p class="no-products">No products available in this category.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                endif;
            endwhile;
            ?>
        <?php else: ?>
            <p class="text-center">No categories or products found.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
