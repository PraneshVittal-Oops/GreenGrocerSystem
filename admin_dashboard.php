<?php   
session_start(); // Start the session at the beginning of the file

// Check if the welcome message exists and display it
if (isset($_SESSION['login_message'])) {
    echo "<div class='container-sm content-container text-center p-3'>";
    echo "<p class='alert alert-success'>" . htmlspecialchars($_SESSION['login_message']) . "</p>";
    echo "</div>";
    
    // Clear the message from the session after displaying it
    unset($_SESSION['login_message']);
}
?>

<!DOCTYPE html> 
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GreenGrocer Market System Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

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
    <a class="navbar-brand ms-3" href="#">GreenGrocer Market</a>
    <!-- Welcome Customer Text -->
    <div class="welcome-text ms-auto" id="welcome-text">
      Welcome Admin
    </div>
  </div>
</nav>

<!-- Sidebar -->
<div id="sidebar" class="mt-4" style="height: calc(100vh - 120px); overflow-y: auto; position: fixed; left: 0; top: 56px; bottom: 64px; width: 200px; padding-right: 15px;">
  <ul class="nav flex-column pt-3">
  <li class="nav-item"><a class="nav-link" href="#" onclick="event.preventDefault(); loadContent('manage_registerd_customer.php')"><i class="bi bi-people"></i> Customer Management</a></li>
  <li class="nav-item"><a class="nav-link" href="#" onclick="event.preventDefault(); loadContent('product_management.php')"><i class="bi bi-people"></i> Product Management</a></li>
  <li class="nav-item"><a class="nav-link" href="#" onclick="event.preventDefault(); loadContent('order_management.php')"><i class="bi bi-receipt"></i> orders Management</a></li>
  <li class="nav-item"><a class="nav-link" href="#" onclick="event.preventDefault(); loadContent('cancelled_orders.php')"><i class="bi bi-arrow-return-left"></i> Returned orders</a></li>
  <li class="nav-item"><a class="nav-link" href="#" onclick="event.preventDefault(); loadContent('reviews_management.php')"><i class="bi bi-chat-left-text"></i> Reviews Management</a></li>
  <li class="nav-item"><a class="nav-link" href="#" onclick="event.preventDefault(); loadContent('warehouse_Management.php')"><i class="bi bi-house-door"></i> Warehouse Management</a>
    <li class="nav-item"><a class="nav-link" href="#" onclick="event.preventDefault(); loadContent('subscription_Management.php')"><i class="bi bi-house-door"></i> Maanage Subcription</a>
      <li class="nav-item"><a class="nav-link" href="#" onclick="event.preventDefault(); loadContent('promotion_management.php')"><i class="bi bi-gift"></i> Management Promotion</a></li>
      <li class="nav-item"><a class="nav-link" href="#" onclick="event.preventDefault(); loadContent('employee_management.php')"><i class="bi bi-person-workspace"></i> Employee Management Informstion</a></li>
  
</ul>

  <!-- User Profile & Logout -->
  <div class="sidebar-footer pt-3">
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
    </ul>
  </div>
</div>

<!-- Add Bootstrap Icons and Bootstrap CSS (for navbar and icons) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Main content area -->
<div id="main-content" class="container-fluid">
  <div class="row">
    <main class="col-md-9 content-container" id="content-area">
      <h1>Welcome to GreenGrocer Market System</h1>
      <p>Click on the sidebar to view different sections.</p>
    </main>
    <!-- Right-side content for User Profile -->
    <aside id="right-side" class="col-md-3">
      <div id="profile-content">
        <!-- User Profile content will load here -->
      </div>
    </aside>
  </div>
</div>

<?php include('footer.php'); ?>  <!-- Include footer here --> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Function to toggle the sidebar on mobile
  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
  }

  // Function to load content dynamically and store the page path in localStorage
  function loadContent(page) {
    fetch(page)
      .then(response => response.text())
      .then(data => {
        document.getElementById('content-area').innerHTML = data;
        // Store the last loaded page in localStorage after it loads
        localStorage.setItem("lastPage", page);
        // Ensure the sidebar is hidden on mobile after content is loaded
        if (window.innerWidth <= 767) {
          document.getElementById('sidebar').classList.remove('show');
        }
      })
      .catch(error => console.log('Error loading content:', error));
  }

  // Load the last viewed content on page load or load "welcome.html" if no history exists
  document.addEventListener("DOMContentLoaded", function() {
    const lastPage = localStorage.getItem("lastPage");
    if (lastPage && lastPage !== "welcome.html") {
      loadContent(lastPage);  // If last page is not "welcome.html", load it
    } else {
      loadContent("welcome.html");  // Set "welcome.html" as the default first page after login
    }
  });
</script>
</body>
</html>


