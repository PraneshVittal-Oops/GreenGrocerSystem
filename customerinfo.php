<?php 
// Include session and database connection
include('connect.php');
session_start();

// Fetch users from the database
$result = $conn->query("SELECT * FROM users");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Button at the top-right corner */
        .add-user-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10;
        }
    </style>
</head>
<body>
    <?php include('header3.php'); ?>

    <!-- Add New User Button -->
    <a href="#addUserForm" class="btn btn-success add-user-btn">Add User</a>

    <div class="container mt-4">
        <h1 class="text-center mb-4">Admin Dashboard</h1>

        <!-- Display Messages -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?= $_SESSION['alert-class']; ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['message']; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['message']); unset($_SESSION['alert-class']); ?>
        <?php endif; ?>

        <!-- User Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Date of Birth</th>
                        <th>Dietary Preferences</th>
                        <th>Loyalty Points</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= $row['firstName']; ?></td>
                            <td><?= $row['lastName']; ?></td>
                            <td><?= $row['email']; ?></td>
                            <td><?= $row['phoneNumber']; ?></td>
                            <td><?= $row['address']; ?></td>
                            <td><?= $row['dob']; ?></td>
                            <td><?= $row['dietaryPreferences']; ?></td>
                            <td><?= $row['loyaltyPoints']; ?></td>
                            <td>
                                <a href="editcustomeruser.php?id=<?= $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="usercustomer.php?delete=<?= $row['id']; ?>" class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Add New User Form -->
        <h2 class="text-center mt-5" id="addUserForm">Add New User</h2>
        <form action="usercustomer.php" method="POST" class="p-4 border rounded bg-light">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="firstName">First Name</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="lastName">Last Name</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name" required>
                </div>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <label for="phoneNumber">Phone Number</label>
                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Phone Number" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="dob">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="dietaryPreferences">Dietary Preferences</label>
                    <input type="text" class="form-control" id="dietaryPreferences" name="dietaryPreferences" placeholder="Dietary Preferences">
                </div>
            </div>
            <div class="form-group">
                <label for="loyaltyPoints">Loyalty Points</label>
                <input type="number" class="form-control" id="loyaltyPoints" name="loyaltyPoints" required>
            </div>
            <button type="submit" name="add_user" class="btn btn-success">Add User</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

