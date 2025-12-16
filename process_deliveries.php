<?php   
session_start();
include('connect.php'); // Database connection

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    $_SESSION['message'] = "Please log in to enter delivery information.";
    $_SESSION['alert-class'] = "alert-danger";
    header("Location: login.php");
    exit;
}

// Fetch available delivery person
$deliveryPerson = null;
$sql = "SELECT DeliveryPersonID, FullName FROM DeliveryPersonnel WHERE Status = 'Active' LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $deliveryPerson = $result->fetch_assoc();
} else {
    // If no delivery person found
    $_SESSION['message'] = "No active delivery personnel available.";
    $_SESSION['alert-class'] = "alert-danger";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture form data
    $orderID = $_POST['order_id'];
    $address = $_POST['delivery_address'];
    $date = $_POST['delivery_date'];
    $status = "Pending";
    
    if ($deliveryPerson) {
        $personID = $deliveryPerson['DeliveryPersonID'];
        $personName = $deliveryPerson['FullName'];

        // Insert delivery info along with assigned delivery person
        $sql = "INSERT INTO Deliveries (OrderID, DeliveryAddress, DeliveryDate, DeliveryStatus, DeliveryPerson) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssi", $orderID, $address, $date, $status, $personID);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Delivery information saved successfully. Assigned to $personName.";
            $_SESSION['alert-class'] = "alert-success";
            // Redirect to userpage.php after successful submission
            header("Location: userpage.php");
            exit;
        } else {
            $_SESSION['message'] = "Failed to save delivery information: " . $stmt->error;
            $_SESSION['alert-class'] = "alert-danger";
        }
    } else {
        $_SESSION['message'] = "No available delivery person.";
        $_SESSION['alert-class'] = "alert-danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Delivery Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Processing Delivery Information</h2>

        <!-- Session Message -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?= $_SESSION['alert-class']; ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['alert-class']); ?>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
