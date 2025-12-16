<?php  
include('connect.php'); // Include database connection
session_start(); // Start the session

try {
    // Check if the order_id is provided in the URL and is valid
    if (isset($_GET['order_id']) && is_numeric($_GET['order_id'])) {
        $orderID = intval($_GET['order_id']); // Sanitize input

        // Prepare SQL query to delete the order
        $sql = "DELETE FROM Orders WHERE OrderID = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Failed to prepare SQL statement: " . $conn->error);
        }

        // Bind the order ID parameter and execute the query
        $stmt->bind_param("i", $orderID);

        if ($stmt->execute()) {
            // If the deletion was successful, set a session success message
            $_SESSION['message'] = "Order ID $orderID has been successfully deleted.";
            $_SESSION['alert-class'] = "alert-success";
        } else {
            // If there was an issue with the deletion, set an error message
            throw new Exception("Error deleting order ID $orderID.");
        }

        // Close the statement
        $stmt->close();
    } else {
        // If no valid order ID is provided, set an error message
        $_SESSION['message'] = "Invalid order ID provided for deletion.";
        $_SESSION['alert-class'] = "alert-danger";
    }

    // Redirect to the order management page after deletion (to display the message on top)
    header("Location: admin_dashboard.php");
    exit;

} catch (Exception $e) {
    // Log the error and set a session message
    error_log($e->getMessage());
    $_SESSION['message'] = "An error occurred while deleting the order. Please try again later.";
    $_SESSION['alert-class'] = "alert-danger";

    // Redirect to the order management page on error (to display the message on top)
    header("Location: order_management.php");
    exit;
}
