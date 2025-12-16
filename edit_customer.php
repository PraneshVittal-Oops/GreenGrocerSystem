<?php 
session_start();
include('connect.php'); // Include the database connection

if (isset($_GET['customerID'])) {
    $customerID = $_GET['customerID'];

    // Fetch the customer's current data
    $sql = "SELECT * FROM Customer WHERE customerID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customerID);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();

    // If no customer is found, redirect
    if (!$customer) {
        $_SESSION['message'] = "Customer not found.";
        header("Location: admin_dashboard.php");
        exit();
    }
} else {
    $_SESSION['message'] = "Invalid request.";
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Include Header -->
    <?php include('header3.php'); ?>

    <div class="container mt-5">
        <h2>Edit Customer</h2>

        <!-- Display Session Messages -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info text-center">
                <?= $_SESSION['message'] ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <!-- Customer Edit Form -->
        <form action="update_customer.php" method="post">
            <input type="hidden" name="customerID" value="<?= htmlspecialchars($customer['customerID']) ?>">

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($customer['name']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($customer['email']) ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <!-- Include Footer -->
    <?php include('footer2.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
