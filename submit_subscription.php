<?php  
// Start session and include database connection
session_start();
include('connect.php');

// Check if the customer is logged in
if (!isset($_SESSION['customer_id'])) {
    die(json_encode([
        "status" => "error",
        "message" => "Unauthorized access. Please log in to proceed."
    ]));
}

// Validate and sanitize inputs
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$subscription_plan = filter_input(INPUT_POST, 'subscription_plan', FILTER_SANITIZE_STRING);
$delivery_frequency = filter_input(INPUT_POST, 'delivery_frequency', FILTER_SANITIZE_STRING);
$subscription_status = filter_input(INPUT_POST, 'subscription_status', FILTER_SANITIZE_STRING);
$payment_method = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_STRING);
$card_number = filter_input(INPUT_POST, 'card_number', FILTER_SANITIZE_STRING);
$expiration_date = filter_input(INPUT_POST, 'expiration_date', FILTER_SANITIZE_STRING);
$cvv = filter_input(INPUT_POST, 'cvv', FILTER_SANITIZE_STRING);

// Check required fields
if (!$name || !$email || !$subscription_plan || !$delivery_frequency || !$subscription_status || !$payment_method || !$card_number || !$expiration_date || !$cvv) {
    die(json_encode([
        "status" => "error",
        "message" => "All fields are required. Please check your inputs."
    ]));
}

// Encrypt sensitive payment information (optional)
$encrypted_card_number = password_hash($card_number, PASSWORD_DEFAULT);
$encrypted_cvv = password_hash($cvv, PASSWORD_DEFAULT);

// Check if the customer ID exists
$customerID = $_SESSION['customer_id'];
$sql = "SELECT customerID FROM Customer WHERE customerID = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die(json_encode([
        "status" => "error",
        "message" => "Database error: " . $conn->error
    ]));
}

$stmt->bind_param("i", $customerID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die(json_encode([
        "status" => "error",
        "message" => "Customer not found. Please check your session or contact support."
    ]));
}

// Insert the subscription into the database
$sql = "INSERT INTO Subscription (CustomerID, StartDate, EndDate, DeliveryFrequency, SubscriptionStatus)
        VALUES (?, NOW(), DATE_ADD(NOW(), INTERVAL 1 MONTH), ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die(json_encode([
        "status" => "error",
        "message" => "Failed to prepare subscription query: " . $conn->error
    ]));
}

$stmt->bind_param("iss", $customerID, $delivery_frequency, $subscription_status);

if (!$stmt->execute()) {
    die(json_encode([
        "status" => "error",
        "message" => "Error creating subscription: " . $stmt->error
    ]));
}

// Set success message in session and redirect
$_SESSION['success_message'] = "Subscription successfully created! Enjoy our service.";
header("Location: userpage.php"); // Redirect to user page
exit;

// Close database connection
$stmt->close();
$conn->close();
?>
