<?php 
session_start();
include('connect.php'); // Database connection

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Add employee logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_employee'])) {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $position = trim($_POST['position']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $city = trim($_POST['city']);
    $salary = floatval($_POST['salary']);
    $hireDate = $_POST['hire_date'];

    if (!empty($firstName) && !empty($lastName) && !empty($position) && !empty($email) && !empty($phone) && !empty($city) && $salary > 0) {
        $stmt = $conn->prepare("INSERT INTO Employees (FirstName, LastName, Position, Email, Phone, City, Salary, HireDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssdss", $firstName, $lastName, $position, $email, $phone, $city, $salary, $hireDate);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Employee added successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to add employee: " . $conn->error;
            $_SESSION['message_type'] = "danger";
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = "Please fill in all fields with valid data.";
        $_SESSION['message_type'] = "warning";
    }
    header("Location: admin_dashboard.php");
    exit();
}

// Delete employee logic
if (isset($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM Employees WHERE EmployeeID = ?");
    $stmt->bind_param("i", $deleteId);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Employee deleted successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Failed to delete employee: " . $conn->error;
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
    header("Location: admin_dashboard.php");
    exit();
}

// If no action is matched
header("Location: admin_dashboard.php");
exit();
?>
