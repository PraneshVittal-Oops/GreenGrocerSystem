<?php
session_start();

// Define admin credentials
$admin_email = 'admin@greengrocer.com'; // Admin email
$admin_password = 'admin123'; // Admin password

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate credentials
    if ($email == $admin_email && $password == $admin_password) {
        $_SESSION['admin_logged_in'] = true; // Set admin session
        $_SESSION['login_message'] = 'Login successful. Welcome Admin!';
        header("Location: admin_dashboard.php"); // Redirect to the admin dashboard
        exit;
    } else {
        $_SESSION['login_message'] = 'Invalid email or password!';
        $_SESSION['alert-class'] = 'alert-danger';
        header("Location: login.php"); // Redirect back to login page
        exit;
    }
}
?>
