<?php  
// Include session, database connection, and header
include('connect.php');
session_start();

// Check if the user is trying to update the user info
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_user'])) {
    // Get form data
    $userID = $_POST['id'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $dietaryPreferences = $_POST['dietaryPreferences'];
    $loyaltyPoints = intval($_POST['loyaltyPoints']); // Make sure to cast loyaltyPoints to integer

    // Prepare the update query
    $sql = "UPDATE users SET firstName = ?, lastName = ?, email = ?, phoneNumber = ?, address = ?, dob = ?, dietaryPreferences = ?, loyaltyPoints = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $firstName, $lastName, $email, $phoneNumber, $address, $dob, $dietaryPreferences, $loyaltyPoints, $userID);

    // Execute the query and check for success
    if ($stmt->execute()) {
        $_SESSION['message'] = "User updated successfully!";
        $_SESSION['alert-class'] = "alert-success";
    } else {
        $_SESSION['message'] = "Error updating user.";
        $_SESSION['alert-class'] = "alert-danger";
    }
    header("Location: customerinfo.php");
    exit();
}

// Get user ID from the URL to fetch the user details for editing
if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $result = $conn->query("SELECT * FROM users WHERE id = '$userId'");
    $user = $result->fetch_assoc();
} else {
    // Redirect to dashboard or show error if no user ID is provided
    header("Location: usercustomer.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Button beside title */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .save-btn {
            position: relative;
            z-index: 10;
        }
    </style>
</head>
<body>
    <?php include('header3.php'); ?>

    <div class="container mt-4">
        <div class="header-container mb-4">
            <h1>Edit User</h1>
            <!-- Back Button beside the title -->
        </div>

        <!-- Edit User Form -->
        <form action="editcustomeruser.php" method="POST" class="p-4 border rounded bg-light">
            <input type="hidden" name="id" value="<?= $user['id']; ?>">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="firstName">First Name</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" value="<?= $user['firstName']; ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="lastName">Last Name</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" value="<?= $user['lastName']; ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $user['email']; ?>" required>
            </div>

            <div class="form-group">
                <label for="phoneNumber">Phone Number</label>
                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" value="<?= $user['phoneNumber']; ?>" required>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?= $user['address']; ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="dob">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob" value="<?= $user['dob']; ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="dietaryPreferences">Dietary Preferences</label>
                    <input type="text" class="form-control" id="dietaryPreferences" name="dietaryPreferences" value="<?= $user['dietaryPreferences']; ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="loyaltyPoints">Loyalty Points</label>
                <input type="number" class="form-control" id="loyaltyPoints" name="loyaltyPoints" value="<?= $user['loyaltyPoints']; ?>" required>
            </div>

            <!-- Save Button -->
            <button type="submit" name="save_user" class="btn btn-success save-btn">Save</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
