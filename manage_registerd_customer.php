<?php  
session_start();
include('connect.php'); // Include database connection

// Fetch all registered customers from the database
$sql = "SELECT * FROM Customer";
$result = $conn->query($sql);

// Count total customers
$totalCustomers = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Admin Dashboard</h2>

    <!-- Display Session Messages -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info text-center">
            <?= $_SESSION['message'] ?>
        </div>
        <?php
        unset($_SESSION['message']); // Clear session message
        ?>
    <?php endif; ?>

    <!-- Total Registered Customers -->
    <div class="mb-4">
        <h4>Total Registered Customers: <?= $totalCustomers ?></h4>
    </div>

    <!-- Registered Customers Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Registered Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['customerID']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                        <td>
                            <!-- Edit Button -->
                            <form action="edit_customer.php" method="get">
                                <input type="hidden" name="customerID" value="<?= htmlspecialchars($row['customerID']) ?>">
                                <button type="submit" class="btn btn-warning btn-sm">Edit</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No customers found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- View Other Customers Data Section -->
    <div class="mt-4 text-center">
        <h4>View Other Customer Data</h4>
        <a href="customerinfo.php" class="btn btn-primary btn-lg">view</a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
