<?php    
session_start();
include('connect.php'); // Database connection

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    $_SESSION['message'] = "Please log in to view delivery information.";
    $_SESSION['alert-class'] = "alert-danger";
    header("Location: login.php");
    exit;
}

// Fetch delivery information
$sql = "SELECT d.OrderID, d.DeliveryAddress, d.DeliveryDate, d.DeliveryStatus, dp.FullName AS DeliveryPerson 
        FROM Deliveries d 
        LEFT JOIN DeliveryPersonnel dp ON d.DeliveryPerson = dp.DeliveryPersonID 
        WHERE d.OrderID IN (SELECT OrderID FROM Orders WHERE CustomerID = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['customer_id']);
$stmt->execute();
$result = $stmt->get_result();
$deliveries = $result->fetch_all(MYSQLI_ASSOC);

// Include header
include('header2.php');
?>

<div class="container mt-5">
    <h2 class="text-center">Delivery Information</h2>

    <!-- Session Message -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert <?= $_SESSION['alert-class']; ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['alert-class']); ?>
    <?php endif; ?>

    <!-- Delivery Table -->
    <?php if (count($deliveries) > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Delivery Address</th>
                        <th>Delivery Date</th>
                        <th>Status</th>
                        <th>Assigned Person</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($deliveries as $delivery): ?>
                        <tr>
                            <td><?= htmlspecialchars($delivery['OrderID']); ?></td>
                            <td><?= htmlspecialchars($delivery['DeliveryAddress']); ?></td>
                            <td><?= htmlspecialchars($delivery['DeliveryDate']); ?></td>
                            <td><?= htmlspecialchars($delivery['DeliveryStatus']); ?></td>
                            <td><?= htmlspecialchars($delivery['DeliveryPerson'] ?? 'Unassigned'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-center">No delivery information available.</p>
    <?php endif; ?>
</div>

<?php
// Include footer
include('footer2.php');
?>
