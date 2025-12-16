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
    <title>GreenGrocer Promotions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Product image styling */
        .product-image {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #ddd;
        }

        /* Sidebar styling */
        .promotion-sidebar {
            background: linear-gradient(45deg, #f0e68c, #f4a261);
            color: #fff;
            padding: 20px;
            border-radius: 10px;
            position: sticky;
            top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .promotion-sidebar h4 {
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .promotion-sidebar ul {
            list-style: none;
            padding-left: 0;
        }

        .promotion-sidebar ul li {
            padding: 5px 0;
            font-size: 1rem;
            font-family: Arial, sans-serif;
        }

        .promotion-sidebar .btn {
            background-color: #264653;
            color: #fff;
            font-weight: bold;
            border: none;
            transition: transform 0.2s ease, background-color 0.3s;
        }

        .promotion-sidebar .btn:hover {
            background-color: #2a9d8f;
            transform: scale(1.05);
        }

        /* Promotions styling */
        .promotion-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 15px;
        }

        .promotion-card h3 {
            color: #2a9d8f;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .promotion-card p {
            color: #555;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <h2 class="mb-4 text-center">Current Promotions</h2>
                <div class="row">
                    <?php
                    // Query to fetch promotions and linked products
                    $sql = "SELECT 
                                p.PromotionID, 
                                p.PromotionName, 
                                p.DiscountPercentage, 
                                p.StartDate, 
                                p.EndDate, 
                                pr.ProductName, 
                                pr.ImagePath 
                            FROM Promotions p
                            LEFT JOIN Products pr ON p.PromotionID = pr.PromotionID";
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        $currentPromotion = null;
                        while ($row = $result->fetch_assoc()) {
                            // Display promotion details
                            if ($currentPromotion !== $row['PromotionID']) {
                                if ($currentPromotion !== null) echo '</ul></div>';
                                $currentPromotion = $row['PromotionID'];
                                echo '<div class="promotion-card">';
                                echo '<h3>' . htmlspecialchars($row['PromotionName']) . ' (' . htmlspecialchars($row['DiscountPercentage']) . '% off)</h3>';
                                echo '<p>Valid from ' . htmlspecialchars($row['StartDate']) . ' to ' . htmlspecialchars($row['EndDate']) . '</p>';
                                echo '<ul>';
                            }
                            // Display product linked to the promotion
                            echo '<li>';
                            if (!empty($row['ImagePath'])) {
                                echo '<img src="' . htmlspecialchars($row['ImagePath']) . '" alt="' . htmlspecialchars($row['ProductName']) . '" class="product-image me-2">';
                            }
                            echo htmlspecialchars($row['ProductName']) . '</li>';
                        }
                        echo '</ul></div>';
                    } else {
                        echo '<p class="text-center text-muted">No promotions available.</p>';
                    }
                    ?>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="promotion-sidebar">
                    <h4>Don't Miss Out!</h4>
                    <p>Check out our latest promotions and save big on fresh, quality products!</p>
                    <ul>
                        <li>✔ Exclusive discounts</li>
                        <li>✔ Limited-time offers</li>
                        <li>✔ Fresh produce</li>
                    </ul>
                    <button class="btn btn-lg w-100 mt-3">Move to Product to Shop Now</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
