<?php
session_start();
include('connect.php'); // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the updated data from the form
    $customerID = $_POST['customerID'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Validate the input (you may want more validation here)
    if (empty($name) || empty($email)) {
        $_SESSION['message'] = "Name and email cannot be empty.";
        header("Location: edit_customer.php?customerID=" . $customerID);
        exit();
    }

    // Prepare and execute the update query
    $sql = "UPDATE Customer SET name = ?, email = ? WHERE customerID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $name, $email, $customerID);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Customer details updated successfully.";
    } else {
        $_SESSION['message'] = "Failed to update customer details.";
    }

    // Redirect back to the admin dashboard
    header("Location: admin_dashboard.php");
    exit();
} else {
    // If the request is not POST, redirect to the dashboard
    $_SESSION['message'] = "Invalid request method.";
    header("Location: admin_dashboard.php");
    exit();
}
