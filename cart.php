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
  <title>Cart</title>
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
            <li class="nav-item"><a class="nav-link" href="index.php">Products</a></li>
            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <?php endif; ?>

          <li class="nav-item"><a class="nav-link" href="checkout.php">Checkout</a></li>
          <li class="nav-item">
            <a class="nav-link active" href="cart.php">
              <i class="fas fa-shopping-cart"></i>
              <span class="badge bg-danger " id="cart-item"></span>
            </a>
          </li>
        </ul>

      </div>
    </div>
  </nav>

  <div class="container my-4">
    <div class="row justify-content-between">
      <div class="col-lg-10">
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
        <div class="table-responsive mt-2">
          <table class="table table-bordered table-stripped text-center">
            <thead>
              <tr>
                <td colspan="7">
                  <h4 class="text-center text-info m-0">Products in your cart!</h4>

                </td>
              </tr>
              <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>
                  <a href="action.php?clear=all" class="btn btn-danger btn-sm text-decoration-none "
                    onclick="return confirm('Are you sure you want to clear your cart?');"><i
                      class="fa fa-trash"></i>&nbsp;&nbsp;Clear Cart</a>
                </th>
              </tr>
            </thead>

            <tbody>
              <?php
              require 'config.php';
              $stmt = $conn->prepare("SELECT * FROM cart");
              $stmt->execute();
              $result = $stmt->get_result();
              $grand_total = 0;
              while ($row = $result->fetch_assoc()):

                ?>
                <tr>
                  <td><?= $row['id'] ?></td>
                  <input type="hidden" class="pid" value="<?= $row['id'] ?>">
                  <td><img src="<?= $row['product_image'] ?>" width="50"></td>
                  <td><?= $row['product_name'] ?></td>
                  <td>Rs. <?= number_format($row['product_price'], 2) ?></td>

                  <input type="hidden" class="pprice" value="<?= $row['product_price'] ?>">
                  <td><input type="number" class="form-control itemQty" value="<?= $row['qty'] ?>" style="width:75px">
                  </td>

                  <td>Rs. <?= number_format($row['total_price'], 2) ?></td>
                  <td>
                    <a href="action.php?remove=<?= $row['id'] ?>" class="text-danger lead"
                      onclick="return confirm('Are you sure you want to remove this Item?');"><i
                        class="fas fa-trash-alt"></i></a>
                  </td>
                </tr>
                <?php $grand_total += $row['total_price']; ?>
              <?php endwhile; ?>
              <tr>
                <td colspan="3">
                  <a href="index.php" class="btn btn-success"><i class="fas fa-cart-plus"></i>&nbsp;&nbsp;Continue
                    Shopping</a>
                </td>
                <td colspan="2"><b>Grand Total</b></td>
                <td>Rs. <?= number_format($grand_total, 2) ?></td>
                <td>
                  <a href="checkout.php" class="btn btn-info <?= ($grand_total > 1) ? "" : "disabled"; ?>"><i
                      class="far fa-credit-card"></i>&nbsp;&nbsp;Checkout</a>
                </td>
              </tr>
            </tbody>

          </table>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function () {
      $("*.itemQty").on('change', function () {
        var $el = $(this).closest('tr');

        var pid = $el.find(".pid").val();
        var pprice = $el.find(".pprice").val();
        var qty = $el.find(".itemQty").val();

        $.ajax({
          url: 'acion.php',
          method: 'post',
          cache: false,
          data: { qty: qty, pid: pid, pprice: pprice },
          success: function (response) {

            console.log(response);
          }
        })
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