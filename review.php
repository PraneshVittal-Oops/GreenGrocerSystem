<?php  
session_start();
include('connect.php'); // Include your database connection file

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Handle review submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
    // Check if the user is logged in
    if (!isset($_SESSION['customer_id'])) {
        die("Error: You must be logged in to submit a review.");
    }

    $userID = $_SESSION['user_id']; // Assuming the user ID is stored in the session

    // Proceed with the review submission
    $productID = $_POST['product_id'];
    $rating = $_POST['rating'];
    $reviewText = $_POST['review_text'];
    $reviewDate = $_POST['review_date']; // Capture the selected review date from the form

    // If no review date is provided, use the current date
    if (empty($reviewDate)) {
        $reviewDate = date('Y-m-d'); // Default to today's date if no date is selected
    }

    // Insert the review into the database
    $insertReviewSQL = "INSERT INTO Reviews (CustomerID, ProductID, Rating, ReviewText, ReviewDate)
                        VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($insertReviewSQL);

    if (!$stmt) {
        die("Error preparing query: " . $conn->error); // Display query error
    }

    $stmt->bind_param("iiiss", $userID, $productID, $rating, $reviewText, $reviewDate);

    if ($stmt->execute()) {
        $_SESSION['review_message'] = "Your review has been submitted!";
    } else {
        $_SESSION['review_message'] = "Error submitting review: " . $stmt->error;
    }

    $stmt->close();

    // Redirect to avoid duplicate submissions
    header("Location: userpage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Reviews</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Products</h2>

        <?php
        // Display the review submission message if it exists
        if (isset($_SESSION['review_message'])) {
            echo '<div class="alert alert-success" role="alert">' . $_SESSION['review_message'] . '</div>';
            unset($_SESSION['review_message']); // Unset the session message after displaying
        }
        ?>

        <!-- Display all products -->
        <div class="row">
            <?php
            $sql = "SELECT ProductID, ProductName, ImagePath FROM Products";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($product = $result->fetch_assoc()): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card h-100">
                            <img src="<?= htmlspecialchars($product['ImagePath']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['ProductName']) ?>" onerror="this.src='image/default.webp';">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= htmlspecialchars($product['ProductName']) ?></h5>
                            </div>
                        </div>
                    </div>
                <?php endwhile;
            } else {
                echo "<p class='text-center text-muted'>No products available.</p>";
            }
            ?>
        </div>

        <!-- Review form -->
        <div class="mt-5">
            <h3 class="text-center">Leave a Review</h3>
            <form method="POST" action="review.php" class="mt-4">
                <div class="mb-3">
                    <label for="product_id" class="form-label">Select Product</label>
                    <select id="product_id" name="product_id" class="form-select" required>
                        <option value="">-- Choose a Product --</option>
                        <?php
                        // Fetch products for the dropdown
                        $sql = "SELECT ProductID, ProductName FROM Products";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while ($product = $result->fetch_assoc()) {
                                echo '<option value="' . htmlspecialchars($product['ProductID']) . '">' . htmlspecialchars($product['ProductName']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="rating" class="form-label">Rating (1 to 5)</label>
                    <input type="number" id="rating" name="rating" class="form-control" min="1" max="5" required>
                </div>
                <div class="mb-3">
                    <label for="review_text" class="form-label">Your Review</label>
                    <textarea id="review_text" name="review_text" class="form-control" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="review_date" class="form-label">Review Date</label>
                    <input type="date" id="review_date" name="review_date" class="form-control" value="<?= date('Y-m-d') ?>">
                    <small class="form-text text-muted">Leave empty for the current date.</small>
                </div>
                <button type="submit" name="submit_review" class="btn btn-primary w-100">Submit Review</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


