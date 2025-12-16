<?php
// Include database connection
include('connect.php');

// Start the session
session_start();

// Retrieve form data
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$phoneNumber = $_POST['phoneNumber'];
$address = $_POST['address'];
$dob = $_POST['dob'];
$dietaryPreferences = $_POST['dietaryPreferences'];
$loyaltyPoints = $_POST['loyaltyPoints'];

// SQL query to insert customer details into the database
$sql = "INSERT INTO users (firstName, lastName, email, phoneNumber, address, dob, dietaryPreferences, loyaltyPoints) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssi", $firstName, $lastName, $email, $phoneNumber, $address, $dob, $dietaryPreferences, $loyaltyPoints);

if ($stmt->execute()) {
    // Retrieve the last inserted ID (customer ID)
    $customerID = $conn->insert_id;

    // Store customer ID in the session
    $_SESSION['userID'] = $userID;

    // Set a success message
    $_SESSION['message'] = "Customer details added successfully! Your Customer ID is: $customerID";
    $_SESSION['alert-class'] = "alert-success";

    // Redirect back to the customer profile page or user page
    header("Location: userpage.php");
    exit();
} else {
    // Set an error message if insertion fails
    $_SESSION['message'] = "Error saving customer details. Please try again.";
    $_SESSION['alert-class'] = "alert-danger";

    // Redirect back to the customer profile page
    header("Location: userpage.php");
    exit();
}
?>
