<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GreenGrocer Market System Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    <style>

    .content-container {
      margin-top: 100px; /* Adjust this value to control spacing */
    }
    /* Sidebar styling */
    #sidebar {
      height: 100%;
       background-color: #f7f7f7; 
      position: fixed;
      top: 0;
      left: 0;
      width: 20px;
      z-index: 1040;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      transform: translateX(-100%); /* Hidden by default */
      transition: transform 0.3s ease;
    }

    #sidebar.show {
      transform: translateX(0); /* Show on toggle */
    }

    #sidebar .nav-link {
      color: #333;
      font-size: 1rem;
    }

    #sidebar .nav-link:hover {
      background-color: #e9ecef;
      color: #007bff;
    }

    #sidebar .bi {
      margin-right: 8px;
    }

    /* Sidebar bottom links (Profile and Logout) */
    #sidebar .sidebar-footer {
      margin-top: auto;
      padding: 10px 0;
      border-top: 1px solid #ddd;
    }

    /* Main content adjustment */
    #main-content {
      margin-left: 250px;
      padding-top: 70px;
      margin-bottom: 60px;
      transition: margin-left 0.3s ease;
    }

    /* Profile on the right side */
    .profile-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 1050;
    }

    .profile-container a {
      font-size: 1.1rem;
      color: #333;
      background-color: #f8f9fa;
      padding: 10px;
      border-radius: 5px;
      text-decoration: none;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .profile-container a:hover {
      background-color: #e9ecef;
      color: #007bff;
    }

    /* Profile content styling */
    #profile-content {
      display: none;
      position: fixed;
      top: 20px;
      right: 0;
      width: 300px;
      background-color: #fff;
      padding: 20px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      z-index: 1060;
      border-radius: 8px;
    }

    /* Footer styling */
    footer {
      background-color: #f8f9fa;
      text-align: center;
      padding: 1rem;
      position: fixed;
      bottom: 0;
      width: 100%;
      z-index: 1030;
    }
    .navbar.navbar-light.bg-light {
  background-color: #4CAF50 !important; /* Set a custom background color */
  color: #FFFFFF; /* Set text color */
}

/* Animation for welcome text */
.welcome-text {
  color: #FFFFFF; /* Set text color to white */
  font-size: 18px;
  font-weight: bold;
  white-space: nowrap;
  animation: moveText 10s linear infinite; /* Animation applied */
}

/* Keyframe for moving text from right to left */
@keyframes moveText {
  0% {
    transform: translateX(100%); /* Start from right */
  }
  50% {
    transform: translateX(-100%); /* Move across to the left */
  }
  100% {
    transform: translateX(100%); /* End back to the right */
  }
}

    /* Mobile adjustments */
    @media (max-width: 767px) {
      #sidebar {
        transform: translateX(-100%); /* Hidden on mobile by default */
        position: fixed;
        left: 0;
        top: 0;
        z-index: 1040;
        width: 100%;
        height: 100vh;
      }

      #sidebar.show {
        transform: translateX(0); /* Show on toggle */
      }

      #main-content {
        margin-left: 0;
        padding-top: 70px; /* Ensures header visibility */
        padding-bottom: 60px; /* Ensures footer visibility */
      }

      /* Ensuring mobile-friendly content visibility */
      .content-container {
        padding: 15px;
      }
    }

    /* Larger screen adjustments */
    @media (min-width: 768px) {
      #sidebar {
        transform: translateX(0); /* Sidebar is always visible on larger screens */
      }
      #main-content {
        margin-left: 250px;
      }
    }

  </style>
</head>
<body>

<!-- Top Navbar with Toggle Button for Mobile View -->
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
  <div class="container-fluid">
    <button class="navbar-toggler d-lg-none" type="button" onclick="toggleSidebar()">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand ms-3" href="userpage.php">GreenGrocer Market</a>
    
    <!-- Welcome Customer Text -->
   
    <!-- Go Back to User Page link -->
    <ul class="navbar-nav ms-auto">
  <li class="nav-item">
    <a class="nav-link text-white text-decoration-none" href="userpage.php" 
       onmouseover="this.style.color='#28a745'" onmouseout="this.style.color='white'">
       Go Back to User Page
    </a>
  </li>
</ul>
  </div>
</nav>

<!-- Bootstrap JS Bundle (includes Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  document.getElementById("navbarToggleButton").addEventListener("click", function () {
    var navbarContent = document.getElementById("navbarSupportedContent");
    navbarContent.classList.toggle("show");
  });
</script>

</body>
</html>
