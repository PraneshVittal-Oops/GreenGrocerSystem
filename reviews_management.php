<?php
session_start();
include('connect.php'); // Include your database connection file

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Fetch statistics
$totalReviewsSQL = "SELECT COUNT(*) AS total_reviews FROM Reviews";
$totalReviewsResult = $conn->query($totalReviewsSQL);
$totalReviews = $totalReviewsResult->fetch_assoc()['total_reviews'];

$averageRatingSQL = "SELECT AVG(Rating) AS average_rating FROM Reviews";
$averageRatingResult = $conn->query($averageRatingSQL);
$averageRating = round($averageRatingResult->fetch_assoc()['average_rating'], 2);

$reviewsByProductSQL = "
    SELECT Products.ProductName, COUNT(Reviews.ReviewID) AS review_count, AVG(Reviews.Rating) AS avg_rating
    FROM Products
    LEFT JOIN Reviews ON Products.ProductID = Reviews.ProductID
    GROUP BY Products.ProductID, Products.ProductName
    ORDER BY review_count DESC";
$reviewsByProductResult = $conn->query($reviewsByProductSQL);

// Fetch all reviews
$allReviewsSQL = "
    SELECT Products.ProductName, Reviews.Rating, Reviews.ReviewText, Reviews.ReviewDate
    FROM Reviews
    JOIN Products ON Reviews.ProductID = Products.ProductID
    ORDER BY Reviews.ReviewDate DESC";
$allReviewsResult = $conn->query($allReviewsSQL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Statistics and Text</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Review Statistics</h1>

        <div class="row my-4">
            <div class="col-md-6">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Total Reviews</div>
                    <div class="card-body">
                        <h3 class="card-title"><?= $totalReviews ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Average Rating</div>
                    <div class="card-body">
                        <h3 class="card-title"><?= $averageRating ?> / 5</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive mt-4">
            <h2 class="text-center">Reviews by Product</h2>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Total Reviews</th>
                        <th>Average Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($reviewsByProductResult && $reviewsByProductResult->num_rows > 0) {
                        while ($row = $reviewsByProductResult->fetch_assoc()) {
                            echo "<tr>
                                <td>" . htmlspecialchars($row['ProductName']) . "</td>
                                <td>" . $row['review_count'] . "</td>
                                <td>" . round($row['avg_rating'], 2) . " / 5</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-center'>No reviews available.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="table-responsive mt-5">
            <h2 class="text-center">All Reviews</h2>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Rating</th>
                        <th>Review Text</th>
                        <th>Review Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($allReviewsResult && $allReviewsResult->num_rows > 0) {
                        while ($review = $allReviewsResult->fetch_assoc()) {
                            echo "<tr>
                                <td>" . htmlspecialchars($review['ProductName']) . "</td>
                                <td>" . htmlspecialchars($review['Rating']) . " / 5</td>
                                <td>" . htmlspecialchars($review['ReviewText']) . "</td>
                                <td>" . htmlspecialchars($review['ReviewDate']) . "</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>No reviews available.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
