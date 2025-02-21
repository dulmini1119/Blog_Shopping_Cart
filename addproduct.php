<?php include("config.php");
session_start();

if (isset($_POST["add_product"])) {
    $product_name = $_POST["product_name"];
    $product_price = $_POST["product_price"];
    $product_image = $_FILES["product_image"]['name'];
    $product_image_temp_name = $_FILES["product_image"]['tmp_name'];
    $product_image_folder = 'images/' . $product_image;
    $product_code = $_POST['product_code'];
    $product_qty = $_POST['product_qty'];

    $insert_query = mysqli_query($conn, "INSERT INTO `products` (product_name, product_price, product_image, product_code, product_qty) VALUES('$product_name', '$product_price', '$product_image', '$product_code', '$product_qty')") or die(
        "Insert query failed"
    );
    if ($insert_query) {
        move_uploaded_file($product_image_temp_name, $product_image_folder);
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Product inserted successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            There is some error in inserting the product.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Add Product</title>
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

                        <li class="nav-item"><a class="nav-link" href="profile.php">Welcome, <?= $_SESSION['name'] ?></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="index.php">Products</a></li>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link active" href="addproduct.php">Add Product</a></li>
                    <li class="nav-item"><a class="nav-link" href="viewproduct.php">View Product</a></li>
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



    <div class="container my-5">
        <div class="card p-4 shadow border-0 rounded-4">
            <h3 class="text-center mb-4 fw-bold text-primary">Add Product</h3>
            <form action="" class="add_product" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="productName" class="form-label">Product Name</label>
                    <input type="text" class="form-control rounded-3 p-2" id="productName"
                        placeholder="Enter Product Name" required name="product_name">
                </div>

                <div class="mb-3">
                    <label for="productPrice" class="form-label">Product Price</label>
                    <input type="text" class="form-control rounded-3 p-2" id="productPrice"
                        placeholder="Enter Product Price" required name="product_price">
                </div>

                <div class="mb-3">
                    <label for="productImage" class="form-label">Product Image</label>
                    <input type="file" class="form-control rounded-3 p-2" id="productImage" required
                        accept="images/png, images/jpg, images/jpeg, images/webp" name="product_image">
                </div>

                <div class="mb-3">
                    <label for="productCode" class="form-label">Product Code</label>
                    <input type="text" class="form-control rounded-3 p-2" id="productCode"
                        placeholder="Enter Product Code" required name="product_code">
                </div>

                <div class="mb-3">
                    <label for="productQty" class="form-label">Product Quantity</label>
                    <input type="text" class="form-control rounded-3 p-2" id="productQty"
                        placeholder="Enter Product Quantity" required name="product_qty">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg rounded-3" name="add_product">Add
                        Product</button>
                </div>
            </form>
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