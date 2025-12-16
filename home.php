<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome - GreenGrocer Market System</title>
  <!-- Bootstrap CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Custom CSS (Your Own Styles) -->
  <link href="css/styles.css" rel="stylesheet">
  <style>
    /* Welcome header with animation */
    .welcome-header {
      font-size: 2.8rem;
      font-weight: 700;
      color: #28a745;
      text-align: center;
      animation: pulse 1.5s infinite;
      margin-top: 20px;
      font-family: 'Arial', sans-serif;
    }

    /* Dancing icon animation */
    .dancing-icon {
      font-size: 3rem;
      animation: dance 1s infinite alternate;
      color: #ff8800;
      margin-top: 10px;
    }

    /* Animated pulse effect for the header */
    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.1); }
    }

    /* Dancing animation for the icon */
    @keyframes dance {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(10deg); }
    }

    /* Custom styles for main content */
    .main-title {
      color: #343a40;
      font-size: 2.2rem;
      font-weight: bold;
      margin-bottom: 15px;
      font-family: 'Georgia', serif;
    }

    .content-text {
      font-size: 1.1rem;
      color: #555;
      line-height: 1.6;
    }

    .highlight {
      color: #28a745;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <?php include('header.php'); ?>

  <!-- Welcome Section -->
  <div class="container my-5">
    <div class="welcome-header">Welcome to GreenGrocer Market System!</div>
    <div class="text-center dancing-icon">
      <i class="bi bi-star-fill"></i> <!-- Dancing star icon from Bootstrap Icons -->
    </div>
  </div>

 <!-- Content Section --> 
<div class="container my-5">
  <div class="row align-items-center">
    <!-- Text content on the left with responsive behavior -->
    <div class="col-lg-6 col-md-6 col-sm-12 mb-4 mb-lg-0">
      <h2 class="main-title text-center text-md-start">Our Vision & Mission</h2>
      <p class="content-text text-center text-md-start">
        At <span class="highlight">GreenGrocer Market</span>, we are committed to bringing the finest quality organic products to our customers in ABC and beyond. To enhance our operations and meet customer needs, we’re introducing a <span class="highlight">state-of-the-art information system</span> designed to streamline our services.
      </p>
      <p class="content-text text-center text-md-start">
        With this system, we can better manage customer information, buying behaviors, subscription services, supplier data, and stock levels. This will ensure a <span class="highlight">seamless shopping experience</span> for our customers, whether they shop online or in-store.
      </p>
      <p class="content-text text-center text-md-start">
        Dedicated to <span class="highlight">sustainability</span>, GreenGrocer supports local farmers by sourcing the majority of our products from within the community. This new system will empower us to minimize waste, optimize our supply chain, and ensure that only the <span class="highlight">freshest organic products</span> make it to our shelves.
      </p>
    </div>

    <!-- Image content on the right with responsive behavior -->
    <div class="col-lg-6 col-md-6 col-sm-12 text-center">
      <img src="image/organic.webp" alt="GreenGrocer Organic Store" class="img-fluid rounded shadow" style="max-width: 100%; height: auto;">
    </div>
  </div>
</div>

  <?php include('footer.php'); ?>  <!-- Include footer here -->
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>

