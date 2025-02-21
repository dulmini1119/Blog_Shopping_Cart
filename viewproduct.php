<?php include("config.php");
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
  <title>View Products</title>
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
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="index.php">Products</a></li>
            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <?php endif; ?>
          <li class="nav-item"><a class="nav-link" href="addproduct.php">Add Product</a></li>
          <li class="nav-item"><a class="nav-link active" href="viewproduct.php">View Product</a></li>
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



  <div class="card shadow-sm rounded-4 border-0">
    <div class="card-body">
      <div class="table-responsive">
        <div
          style="display : <?php if (isset($_SESSION['showAlert'])) {
            echo $_SESSION['showAlert'];
          } else {
            echo 'none';
          }
          unset($_SESSION['showAlert']); ?>"
          class="alert alert-success alert-dismissible mt-3">
          <button type="button" class="btn-close" data-bs-dismiss="alert">&times;</button>
          <strong><?php if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
          }
          unset($_SESSION['showAlert']); ?></strong>
        </div>
        <table class="table table-striped table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Product Image</th>
              <th>Product Name</th>
              <th>Product Price</th>
              <th>Product Code</th>
              <th>Product Qty</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $display_product = mysqli_query($conn, 'SELECT * FROM `products`');
            $num = 1;
            if (mysqli_num_rows($display_product) > 0) {
              while ($row = mysqli_fetch_assoc($display_product)) {
                ?>
                <tr>
                  <td><?php echo $num ?></td>
                  <td><img src="<?php echo $row['product_image']; ?>" alt="<?php echo $row['product_name']; ?>"
                      class="img-thumbnail" style="width: 70px; height: 70px; object-fit: cover;"></td>

                  <td><?php echo $row['product_name'] ?></td>
                  <td>Rs. <?= number_format($row['product_price'], 2) ?></td>
                  <td><?php echo $row['product_code'] ?></td>
                  <td><?php echo $row['product_qty'] ?></td>
                  <td>
                    <a href="action.php?remove=<?= $row['id'] ?>" class="btn btn-outline-danger btn-sm"
                      onclick="return confirm('Are you sure you want to delete this Item?');"><i
                        class="fas fa-trash"></i></a>
                    <a href="editproduct.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-primary btn-sm"><i
                        class="fas fa-edit"></i></a>
                  </td>
                </tr>
                <?php
                $num++;
              }
            } else {
              echo '<tr><td colspan="7" class="text-center text-danger">No products available</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>



  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function () {


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