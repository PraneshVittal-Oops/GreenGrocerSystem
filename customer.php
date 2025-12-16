<?php 
session_start(); // Start the session

// Check for a session message and display it
if (isset($_SESSION['message'])) {
    echo "<div class='alert " . $_SESSION['alert-class'] . " text-center'>" . $_SESSION['message'] . "</div>";
    // Unset the message after displaying it
    unset($_SESSION['message']);
    unset($_SESSION['alert-class']);
}
?>

<!DOCTYPE html> 
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>Customer Profile</h2>
  <form action="save-customer.php" method="POST" class="mt-4">
    <!-- First Name -->
    <div class="mb-3">
      <label for="firstName" class="form-label">First Name</label>
      <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter First Name" required>
    </div>

    <!-- Last Name -->
    <div class="mb-3">
      <label for="lastName" class="form-label">Last Name</label>
      <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter Last Name" required>
    </div>

    <!-- Email -->
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email Address" required>
    </div>

    <!-- Phone Number -->
    <div class="mb-3">
      <label for="phoneNumber" class="form-label">Phone Number</label>
      <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Enter Phone Number" required>
    </div>

    <!-- Address -->
    <div class="mb-3">
      <label for="address" class="form-label">Address</label>
      <input type="text" class="form-control" id="address" name="address" placeholder="Enter Home Address" required>
    </div>

    <!-- Date of Birth -->
    <div class="mb-3">
      <label for="dob" class="form-label">Date of Birth</label>
      <input type="date" class="form-control" id="dob" name="dob" required>
    </div>

    <!-- Dietary Preferences -->
    <div class="mb-3">
      <label for="dietaryPreferences" class="form-label">Dietary Preferences</label>
      <textarea class="form-control" id="dietaryPreferences" name="dietaryPreferences" rows="3" placeholder="Enter any dietary preferences (optional)"></textarea>
    </div>

    <!-- Loyalty Points -->
    <div class="mb-3">
      <label for="loyaltyPoints" class="form-label">Loyalty Points</label>
      <input type="number" class="form-control" id="loyaltyPoints" name="loyaltyPoints" placeholder="Enter Loyalty Points" min="0">
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary">Submit details</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
