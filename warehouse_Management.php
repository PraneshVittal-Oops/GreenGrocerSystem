<?php 
session_start();
include('connect.php');

// Verify database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for adding a warehouse
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_warehouse'])) {
    $location = trim($_POST['location']);
    $capacity = trim($_POST['capacity']);

    if (!empty($location) && !empty($capacity) && is_numeric($capacity)) {
        $stmt = $conn->prepare("INSERT INTO Warehouse (Location, Capacity) VALUES (?, ?)");
        $stmt->bind_param("si", $location, $capacity);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Warehouse added successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to add warehouse: " . $conn->error;
            $_SESSION['message_type'] = "danger";
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = "Please provide valid location and numeric capacity.";
        $_SESSION['message_type'] = "warning";
    }

    header("Location: admin_dashboard.php"); // Redirect to reload the page and show updated data
    exit();
}

// Fetch all warehouse details
$query = "SELECT Location, Capacity FROM Warehouse ORDER BY Location";
$warehouse_result = $conn->query($query);

if (!$warehouse_result) {
    die("Query failed: " . $conn->error);
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

$product_result = $conn->query($query);

if (!$product_result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Warehouse Management</h2>

    <!-- Display Messages -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <!-- Form to Add a Warehouse -->
    <div class="mb-4">
        <h4>Add Warehouse</h4>
        <form action="warehouse_management.php" method="POST">
            <div class="mb-3">
                <label for="location" class="form-label">Warehouse Location</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <div class="mb-3">
                <label for="capacity" class="form-label">Warehouse Capacity</label>
                <input type="number" class="form-control" id="capacity" name="capacity" required>
            </div>
            <button type="submit" name="add_warehouse" class="btn btn-primary">Add Warehouse</button>
        </form>
    </div>

    <!-- Display All Warehouses -->
    <h4>All Warehouses</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Warehouse Location</th>
                <th>Capacity</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($warehouse_result->num_rows > 0): ?>
                <?php while ($row = $warehouse_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Location']); ?></td>
                        <td><?php echo htmlspecialchars($row['Capacity']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" class="text-center">No warehouses found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Display Products by Warehouse -->
    <h4>Warehouse Products</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Warehouse Location</th>
                <th>Product Name</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($product_result->num_rows > 0): ?>
                <?php while ($row = $product_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['WarehouseLocation']); ?></td>
                        <td><?php echo htmlspecialchars($row['ProductName']); ?></td>
                        <td><?php echo htmlspecialchars($row['Quantity']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">No products found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
