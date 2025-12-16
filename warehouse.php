

<?php
session_start();
include('connect.php');

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];


include('connect.php');

// Verify database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all warehouse-product relations
$query = "
    SELECT 
        w.Location AS WarehouseLocation, 
        p.ProductName, 
        pw.Quantity
    FROM ProductWarehouse pw
    JOIN Warehouse w ON pw.WarehouseID = w.WarehouseID
    JOIN Products p ON pw.ProductID = p.ProductID
    ORDER BY w.Location, p.ProductName
";

$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Warehouse Products</h2>

    <!-- Display Products by Warehouse -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Warehouse Location</th>
                <th>Product Name</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['WarehouseLocation']); ?></td>
                        <td><?php echo htmlspecialchars($row['ProductName']); ?></td>
                        <td><?php echo htmlspecialchars($row['Quantity']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">No data found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
