<?php
session_start();
include('connect.php');

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Retrieve form data
$fullName = trim($_POST['fullName']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$confirmPassword = trim($_POST['confirmPassword']);

// Validate form data
if ($password !== $confirmPassword) {
    $_SESSION['message'] = "Passwords do not match!";
    header("Location: userpage.php");
    exit();
}

// Hash the new password if provided
$hashedPassword = null;
if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
}

// SQL query to update based on provided inputs
try {
    if (!empty($hashedPassword)) {
        // Update name, email, and password
        $query = "UPDATE Customer SET name = ?, email = ?, password = ? WHERE customerID = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("sssi", $fullName, $email, $hashedPassword, $customer_id);
    } else {
        // Update name and email only
        $query = "UPDATE Customer SET name = ?, email = ? WHERE customerID = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("ssi", $fullName, $email, $customer_id);
    }

    // Execute the query
    if (!$stmt->execute()) {
        throw new Exception("Error executing statement: " . $stmt->error);
    }

    $_SESSION['message'] = "Profile updated successfully!";
} catch (Exception $e) {
    // Log the error and notify the user
    error_log($e->getMessage());
    $_SESSION['message'] = "An error occurred while updating your profile. Please try again.";
}

$stmt->close();
$conn->close();

// Redirect back to the profile page
header("Location: userpage.php");
exit();
?>
