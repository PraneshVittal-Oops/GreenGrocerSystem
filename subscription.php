<?php
session_start();
$success_message = "";

if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Clear the message after displaying it
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribe to Our Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($success_message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <h2 class="text-center">Subscribe to Our Service</h2>
        <form action="submit_subscription.php" method="POST">
            <!-- Customer Information -->
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <!-- Subscription Plan -->
            <div class="mb-3">
                <label for="subscription_plan" class="form-label">Choose a Subscription Plan</label>
                <select class="form-select" id="subscription_plan" name="subscription_plan" required>
                    <option value="weekly">Weekly Delivery</option>
                    <option value="bi-weekly">Bi-Weekly Delivery</option>
                    <option value="monthly">Monthly Delivery</option>
                </select>
            </div>

            <!-- Delivery Frequency -->
            <div class="mb-3">
                <label for="delivery_frequency" class="form-label">Delivery Frequency</label>
                <select class="form-select" id="delivery_frequency" name="delivery_frequency" required>
                    <option value="weekly">Weekly</option>
                    <option value="bi-weekly">Bi-Weekly</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>

            <!-- Subscription Status -->
            <div class="mb-3">
                <label for="subscription_status" class="form-label">Subscription Status</label>
                <select class="form-select" id="subscription_status" name="subscription_status" required>
                    <option value="active">Active</option>
                    <option value="paused">Paused</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <!-- Payment Information -->
            <div class="mb-3">
                <label for="payment_method" class="form-label">Payment Method</label>
                <select class="form-select" id="payment_method" name="payment_method" required>
                    <option value="credit_card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="card_number" class="form-label">Card Number (if paying by Credit Card)</label>
                <input type="text" class="form-control" id="card_number" name="card_number" placeholder="Card Number" required>
            </div>

            <div class="mb-3">
                <label for="expiration_date" class="form-label">Expiration Date</label>
                <input type="text" class="form-control" id="expiration_date" name="expiration_date" placeholder="MM/YY" required>
            </div>

            <div class="mb-3">
                <label for="cvv" class="form-label">CVV</label>
                <input type="text" class="form-control" id="cvv" name="cvv" placeholder="CVV" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Subscribe Now</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
