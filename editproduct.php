<?php
include("config.php");
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
}

if (isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];


    if (!empty($_FILES['product_image']['name'])) {
        $product_image = $_FILES['product_image']['name'];
        $product_image_temp_name = $_FILES['product_image']['tmp_name'];
        $product_image_folder = 'images/' . $product_image;

        move_uploaded_file($product_image_temp_name, $product_image_folder);
    } else {
        $product_image = $_POST['old_image'];
    }

    $stmt = $conn->prepare("UPDATE products SET product_name=?, product_price=?, product_image=? WHERE id=?");
    $stmt->bind_param("sssi", $product_name, $product_price, $product_image, $id);

    if ($stmt->execute()) {
        $_SESSION['showAlert'] = 'block';
        $_SESSION['message'] = 'Product updated successfully!';
        header('Location: viewproduct.php');
        exit();
    } else {
        echo "Failed to update product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2 class="text-center text-primary">Edit Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
            <input type="hidden" name="old_image" value="<?= $product['product_image'] ?>">

            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" class="form-control" name="product_name" value="<?= $product['product_name'] ?>"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Product Price</label>
                <input type="text" class="form-control" name="product_price" value="<?= $product['product_price'] ?>"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Product Image</label>
                <input type="file" class="form-control" name="product_image">
                <img src="<?= $product['product_image'] ?>" alt="Product Image" width="100" class="mt-2">
            </div>

            <div class="mb-3">
                <label class="form-label">Product Code</label>
                <input type="text" class="form-control" name="product_code" value="<?= $product['product_code'] ?>"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Product Qty</label>
                <input type="text" class="form-control" name="product_qty" value="<?= $product['product_qty'] ?>"
                    required>
            </div>

            <button type="submit" name="update_product" class="btn btn-success">Update Product</button>
            <a href="viewproduct.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>