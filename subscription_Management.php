<?php
// Start session and include database connection
session_start();
include('connect.php');
// Fetch all subscribers from the database
$sql = "
    SELECT 
        s.SubscriptionID, 
        c.Name AS CustomerName, 
        c.Email, 
        s.StartDate, 
        s.EndDate, 
        s.DeliveryFrequency, 
        s.SubscriptionStatus 
    FROM Subscription s
    JOIN Customer c ON s.CustomerID = c.CustomerID
    ORDER BY s.StartDate DESC
";
$result = $conn->query($sql);

// Check for errors in the query
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Count the total number of subscribers
$totalSubscribers = $result->num_rows;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribers List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Subscribers List</h2>

    <!-- Display Total Number of Subscribers -->
    <div class="mb-4">
        <h4>Total Subscribers: <?php echo $totalSubscribers; ?></h4>
    </div>

    <!-- Display Subscribers Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Subscription ID</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Delivery Frequency</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($totalSubscribers > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['SubscriptionID']); ?></td>
                        <td><?php echo htmlspecialchars($row['CustomerName']); ?></td>
                        <td><?php echo htmlspecialchars($row['Email']); ?></td>
                        <td><?php echo htmlspecialchars($row['StartDate']); ?></td>
                        <td><?php echo htmlspecialchars($row['EndDate']); ?></td>
                        <td><?php echo htmlspecialchars($row['DeliveryFrequency']); ?></td>
                        <td><?php echo htmlspecialchars($row['SubscriptionStatus']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No subscribers found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Free the result and close the connection
$result->free();
$conn->close();
?>
