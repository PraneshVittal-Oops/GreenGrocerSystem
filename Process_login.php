<?php
session_start();
include 'connect.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        // Prepare and execute SQL to find user by email
        $stmt = $conn->prepare("SELECT customerid, name, password FROM customer WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Check if the user exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($customerId, $userName, $hashedPassword);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashedPassword)) {
                // Password is correct, regenerate session ID
                session_regenerate_id(true);
                
                // Store user info in session
                $_SESSION['customer_id'] = $customerId;
                $_SESSION['user_name'] = $userName;
                $_SESSION['login_message'] = "Welcome, $userName!";
                
                // Redirect to user page
                header("Location: userpage.php");
                exit();
            } else {
                $_SESSION['login_message'] = "Invalid email or password.";
            }
        } else {
            $_SESSION['login_message'] = "No account found with that email.";
        }
        $stmt->close();
    } else {
        $_SESSION['login_message'] = "Please fill in both email and password.";
    }
    
    // Redirect back to the login page
    header("Location: login.php");
    exit();
} else {
    // Redirect if accessed directly
    header("Location: login.php");
    exit();
}

// Close the database connection
$conn->close();
?>

