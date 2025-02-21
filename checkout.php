<?php
require 'config.php';
session_start();

$grand_total = 0;
$allItems = '';
$items = array();

$sql = "SELECT CONCAT(product_name, '(',qty,')') AS ItemQty, total_price FROM cart";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $grand_total += $row['total_price'];
  $items[] = $row['ItemQty'];
}
$allItems = implode(", ", $items);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <title>Checkout</title>
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

          <li class="nav-item"><a class="nav-link active" href="checkout.php">Checkout</a></li>
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
    <div class="row justify-content-center">
      <div class="col-lg-6 px-4 pb-4" id="order">
        <h4 class="text-center text-info p-2">Complete Your Order</h4>
        <div class="order-summary">
          <h6><b>Products:</b> <?= $allItems; ?></h6>
          <h6><b>Delivery Charge:</b> Free</h6>
          <h5><b>Amount Payable:</b> <?= number_format($grand_total, 2) ?>/=</h5>
        </div>

        <form action="" method="post" id="placeOrder">
          <input type="hidden" name="products" value="<?= $allItems; ?>">
          <input type="hidden" name="grand_total" value="<?= $grand_total; ?>">

          <div class="form-group">
            <input type="text" name="name" class="form-control" placeholder="Enter your Name" required>
          </div>
          <div class="form-group">
            <input type="email" name="email" class="form-control" placeholder="Enter your Email" required>
          </div>
          <div class="form-group">
            <input type="tel" name="phone" class="form-control" placeholder="Enter your Number" required>
          </div>
          <div class="form-group">
            <textarea name="address" class="form-control" placeholder="Enter your address" required rows="3"></textarea>
          </div>

          <h6 class="text-center lead">Select Payment Method</h6>
          <div class="form-group">
            <select name="pmode" class="form-control">
              <option value="" disabled selected>Select Payment Mode</option>
              <option value="cod">Cash On Delivery</option>
              <option value="mintpay">MintPay</option>
              <option value="cards">Credit/Debit Cards</option>
            </select>
          </div>

          <div class="form-group">
            <input type="submit" name="submit" class="modern-btn" value="Place Order">
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function () {
      $("#placeOrder").submit(function (e) {
        e.preventDefault();
        $.ajax({
          url: 'action.php',
          method: 'post',
          data: $('form').serialize() + "&action=order",
          success: function (response) {
            $('#order').html(response);
          }
        });
      });

      load_cart_item_number();

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