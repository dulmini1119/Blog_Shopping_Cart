<?php require 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - ZC Mobiles</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php"><i class="fas fa-mobile-alt"></i>&nbsp;&nbsp;ZC Mobiles</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar"
        aria-controls="mynavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mynavbar">

        <form class="d-flex mx-auto my-2 my-lg-0">
          <div class="input-group">
            <input type="search" class="form-control custom-search-bar" placeholder="Search for products..."
              aria-label="Search">
            <button class="btn custom-search-button" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </form>

        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Products</a></li>
          <li class="nav-item"><a class="nav-link active" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="checkout.php">Checkout</a></li>

          <li class="nav-item">
            <a class="nav-link" href="cart.php">
              <i class="fas fa-shopping-cart"></i>
              <span class="badge bg-danger " id="cart-item"></span>
            </a>
          </li>
        </ul>

      </div>
    </div>
  </nav>


  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="register-card p-4 rounded shadow-lg">
      <h3 class="text-center mb-4">Create an Account</h3>

      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
      <?php endif; ?>

      <form action="register_process.php" method="post">
        <div class="mb-3">
          <input type="text" name="name" class="form-control" placeholder="Enter Name" required>
        </div>
        <div class="mb-3">
          <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
        </div>
        <div class="mb-3">
          <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
      </form>

      <p class="mt-3 text-center">Already have an account? <a href="login.php">Login Here</a></p>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>