<?php
session_start();
include('connect.php'); // Include your database connection file

// Fetch promotions
$query = "SELECT PromotionID, PromotionName, DiscountPercentage, StartDate, EndDate FROM Promotions ORDER BY StartDate DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Promotions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Manage Promotions</h2>

    <!-- Display Session Messages -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo htmlspecialchars($_SESSION['message_type']); ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <!-- Form to Add Promotion -->
    <div class="card mb-4">
        <div class="card-header">Add New Promotion</div>
        <div class="card-body">
            <form action="add_delete_promotion.php" method="POST">
                <div class="mb-3">
                    <label for="promotion_name" class="form-label">Promotion Name</label>
                    <input type="text" class="form-control" id="promotion_name" name="promotion_name" required>
                </div>
                <div class="mb-3">
                    <label for="discount_percentage" class="form-label">Discount Percentage</label>
                    <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" required>
                </div>
                <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                </div>
                <button type="submit" name="add_promotion" class="btn btn-primary">Add Promotion</button>
            </form>
        </div>
    </div>

    <!-- Display Promotions -->
    <h4>Current Promotions</h4>
    <table class="table table-bordered">
        <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Promotion Name</th>
            <th>Discount (%)</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['PromotionID']); ?></td>
                    <td><?php echo htmlspecialchars($row['PromotionName']); ?></td>
                    <td><?php echo htmlspecialchars($row['DiscountPercentage']); ?></td>
                    <td><?php echo htmlspecialchars($row['StartDate']); ?></td>
                    <td><?php echo htmlspecialchars($row['EndDate']); ?></td>
                    <td>
                        <a href="add_delete_promotion.php?delete_id=<?php echo $row['PromotionID']; ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to delete this promotion?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">No promotions available.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
