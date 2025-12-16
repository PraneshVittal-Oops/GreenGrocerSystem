<?php 
session_start();
include('connect.php'); // Database connection

// Function to fetch statistics
function getStatistics($conn) {
    $statistics = [];

    // Total orders returned
    $query = "SELECT COUNT(*) AS total_returns FROM Returns";
    $result = $conn->query($query);
    $statistics['total_returns'] = $result->fetch_assoc()['total_returns'] ?? 0;

    // Total orders
    $query = "SELECT COUNT(*) AS total_orders FROM Orders";
    $result = $conn->query($query);
    $statistics['total_orders'] = $result->fetch_assoc()['total_orders'] ?? 0;

    // Total canceled deliveries
    $query = "SELECT COUNT(*) AS canceled_deliveries FROM Deliveries WHERE DeliveryStatus = 'Cancelled'";
    $result = $conn->query($query);
    $statistics['canceled_deliveries'] = $result->fetch_assoc()['canceled_deliveries'] ?? 0;

    // Percentage of returned orders
    if ($statistics['total_orders'] > 0) {
        $statistics['return_percentage'] = round(($statistics['total_returns'] / $statistics['total_orders']) * 100, 2);
    } else {
        $statistics['return_percentage'] = 0;
    }

    return $statistics;
}

// Fetch statistics
$stats = getStatistics($conn);

// Fetch returned orders details
$query = "SELECT Orders.OrderID, Orders.CustomerID, Returns.ReasonForReturn, Orders.OrderDate FROM Orders
          JOIN Returns ON Orders.OrderID = Returns.OrderID";
$result = $conn->query($query);
?>
<body>
    <div class="container mt-5">
        <h1>Order Return Statistics</h1>

        <!-- Display session message -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?= $_SESSION['alert-class']; ?>">
                <?= $_SESSION['message']; ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <!-- Statistics Table -->
        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Statistic</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Total Orders</td>
                            <td><?= $stats['total_orders']; ?></td>
                        </tr>
                        <tr>
                            <td>Total Orders Returned</td>
                            <td><?= $stats['total_returns']; ?></td>
                        </tr>
                        <tr>
                            <td>Total Canceled Deliveries</td>
                            <td><?= $stats['canceled_deliveries']; ?></td>
                        </tr>
                        <tr>
                            <td>Percentage of Returned Orders</td>
                            <td><?= $stats['return_percentage']; ?>%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Returned Orders Table -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Returned Orders</h5>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer ID</th>
                            <th>Reason for Return</th>
                            <th>Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['OrderID']; ?></td>
                                <td><?= $row['CustomerID']; ?></td>
                                <td><?= $row['ReasonForReturn']; ?></td>
                                <td><?= $row['OrderDate']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>


    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
