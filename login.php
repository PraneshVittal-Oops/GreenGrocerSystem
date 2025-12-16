<!DOCTYPE html>  
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - GreenGrocer Market System</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
   <link href="css/styles.css" rel="stylesheet">
</head>
<body>

<?php 
include 'header.php'; 
session_start();
if (isset($_SESSION['login_message'])) {
    echo '<div class="alert alert-info text-center mx-auto mt-3" style="max-width: 600px;">' . $_SESSION['login_message'] . '</div>';
    unset($_SESSION['login_message']);
}
?>
<section class="vh-100" style="background-color: #eee;">
  <div class="container h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-lg-12 col-xl-11">
        <div class="card text-black" style="border-radius: 25px;">
          <div class="card-body p-md-5">
            <div class="row justify-content-center">
              <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                <h2 class="text-center fw-bold mb-5 mx-1 mx-md-4 mt-4">Login to GreenGrocer Market System</h2>
                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign In</p>

                <form class="mx-1 mx-md-4" action="Process_login.php" method="post">

                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="bi bi-envelope-fill me-3 fa-lg"></i>
                    <div class="form-outline flex-fill mb-0">
                      <input type="email" id="loginEmail" class="form-control" name="email" required />
                      <label class="form-label" for="loginEmail">Your Email</label>
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="bi bi-lock-fill me-3 fa-lg"></i>
                    <div class="form-outline flex-fill mb-0">
                      <input type="password" id="loginPassword" class="form-control" name="password" required />
                      <label class="form-label" for="loginPassword">Password</label>
                    </div>
                  </div>

                  <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                    <button type="submit" class="btn btn-primary btn-lg">Login</button>
                  </div>

                </form>
                
                <p class="text-center">Don’t have an account? <a href="register.php">Register here</a>.</p>

              </div>
              <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">
                <img src="image/grocer.jpg" class="img-fluid" alt="Sample image">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
