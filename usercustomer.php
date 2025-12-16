<?php
// Include database connection and session handling
include('connect.php');
session_start();

// Add new user (INSERT)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $dietaryPreferences = $_POST['dietaryPreferences'];
    $loyaltyPoints = intval($_POST['loyaltyPoints']);

    $sql = "INSERT INTO users (firstName, lastName, email, phoneNumber, address, dob, dietaryPreferences, loyaltyPoints) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $firstName, $lastName, $email, $phoneNumber, $address, $dob, $dietaryPreferences, $loyaltyPoints);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User added successfully!";
        $_SESSION['alert-class'] = "alert-success";
    } else {
        $_SESSION['message'] = "Error adding user. Please try again.";
        $_SESSION['alert-class'] = "alert-danger";
    }
    header("Location: customerinfo.php");
    exit();
}

// Delete user (DELETE)
if (isset($_GET['delete'])) {
    $userID = intval($_GET['delete']);
    $deleteQuery = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $userID);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User deleted successfully!";
        $_SESSION['alert-class'] = "alert-success";
    } else {
        $_SESSION['message'] = "Error deleting user.";
        $_SESSION['alert-class'] = "alert-danger";
    }
    header("Location: customerinfo.php");
    exit();
}

?>
