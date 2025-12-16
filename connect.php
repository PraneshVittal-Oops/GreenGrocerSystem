<?php
// connect.php

// Database connection parameters
$servername = "localhost"; // Your MySQL server (often 'localhost')
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "greengrocer_db"; // The name of the database

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
