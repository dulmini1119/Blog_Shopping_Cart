<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <title>Blog & Shopping Cart System</title>
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
          <?php if (isset($_SESSION['user_id'])): ?>

            <li class="nav-item"><a class="nav-link" href="profile.php">Welcome, <?= $_SESSION['name'] ?></a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            <li class="nav-item"><a class="nav-link" href="addproduct.php">Add Product</a></li>
            <li class="nav-item"><a class="nav-link" href="viewproduct.php">View Product</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link active" href="index.php">Products</a></li>
            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <?php endif; ?>
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



  <div class="container my-4">
    <div id="message"></div>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
      <?php
      include 'config.php';
      $stmt = $conn->prepare("SELECT * FROM products");
      $stmt->execute();
      $result = $stmt->get_result();
      while ($row = $result->fetch_assoc()):
        ?>

        <div class="col">
          <div class="card h-100 border rounded-4 shadow-sm overflow-hidden product-card">
            <div class="ratio ratio-1x1 image-container">
              <img src="images/<?= $row['product_image'] ?>" class="product-image" alt="Product Image">
            </div>
            <div class="card-body text-center d-flex flex-column justify-content-between p-4">
              <h5 class="card-title mb-1"><?= $row['product_name'] ?></h5>
              <h4 class="card-text mb-3 text-success fw-bold">Rs. <?= number_format($row['product_price'], 2) ?></h4>
              <form class="form-submit">
                <input type="hidden" class="pid" value="<?= $row['id'] ?>">
                <input type="hidden" class="pname" value="<?= $row['product_name'] ?>">
                <input type="hidden" class="pprice" value="<?= $row['product_price'] ?>">
                <input type="hidden" class="pimage" value="<?= $row['product_image'] ?>">
                <input type="hidden" class="pcode" value="<?= $row['product_code'] ?>">
                <button class="btn btn-outline-light btn-sm fw-semibold px-4 py-2 rounded-pill glass-button"
                  type="button">
                  <i class="fas fa-cart-plus"></i>&nbsp;&nbsp;Add to Cart
                </button>
              </form>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function () {
      $(".glass-button").click(function (e) {
        e.preventDefault();
        var $form = $(this).closest(".form-submit");
        var pid = $form.find(".pid").val();
        var pname = $form.find(".pname").val();
        var pprice = $form.find(".pprice").val();
        var pimage = $form.find(".pimage").val();
        var pcode = $form.find(".pcode").val();

        $.ajax({
          url: 'action.php',
          method: 'post',
          data: { pid: pid, pname: pname, pprice: pprice, pimage: pimage, pcode: pcode },
          success: function (response) {
            $("#message").html(response);
            $('html, body').animate({ scrollTop: 0 }, 'slow');
            load_cart_item_number()
          },
          error: function () {
            $("#message").html('<div class="alert alert-danger">Something went wrong!</div>');
            $('html, body').animate({ scrollTop: 0 }, 'slow');
          }
        });
      });

      load_cart_item_number()

      function load_cart_item_number() {
        $.ajax({
          url: 'action.php',
          method: 'get',
          data: { cartItem: 'cart_item' },
          success: function (response) {
            $('#cart-item').html(response);
          }
        });
      }

    });
  </script>
</body>

</html>