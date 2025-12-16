<?php 
session_start();
include('connect.php'); // Include your database connection file

// Add promotion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_promotion'])) {
    $promotionName = trim($_POST['promotion_name']);
    $discountPercentage = trim($_POST['discount_percentage']);
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    if (!empty($promotionName) && is_numeric($discountPercentage) && $discountPercentage > 0) {
        $stmt = $conn->prepare("INSERT INTO Promotions (PromotionName, DiscountPercentage, StartDate, EndDate) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $promotionName, $discountPercentage, $startDate, $endDate);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Promotion added successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to add promotion: " . $conn->error;
            $_SESSION['message_type'] = "danger";
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = "Please provide valid promotion details.";
        $_SESSION['message_type'] = "warning";
    }

    header("Location: admin_dashboard.php");
    exit();
}

// Delete promotion
if (isset($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM Promotions WHERE PromotionID = ?");
    $stmt->bind_param("i", $deleteId);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Promotion deleted successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Failed to delete promotion: " . $conn->error;
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
    // Correct redirection with Location header
    header("Location: admin_dashboard.php");
    exit();
}
?>
