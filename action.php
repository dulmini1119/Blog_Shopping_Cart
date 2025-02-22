<?php
require 'config.php';
session_start();

if (isset($_POST['pid'])) {
    $pid = $_POST['pid'];
    $pname = $_POST['pname'];
    $pprice = $_POST['pprice'];
    $pimage = $_POST['pimage'];
    $pcode = $_POST['pcode'];
    $pqty = 1;

    $stmt = $conn->prepare("SELECT product_code FROM cart WHERE product_code=?");
    $stmt->bind_param("s", $pcode);
    $stmt->execute();
    $res = $stmt->get_result();
    $r = $res->fetch_assoc();


    if ($r === null) {

        $query = $conn->prepare("INSERT INTO cart (product_name, product_price, product_image, product_code, qty, total_price) VALUES (?, ?, ?, ?, ?, ?)");
        $query->bind_param("sdssii", $pname, $pprice, $pimage, $pcode, $pqty, $pprice);
        $query->execute();

        echo '
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <strong>Item added to your Cart Successfully!</strong>
        </div>';
    } else {

        echo '
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <strong>Item Already Added to your Cart!</strong>
        </div>';
    }
}

if (isset($_GET['cartItem']) && isset($_GET['cartItem']) == 'cart_item') {
    $stmt = $conn->prepare("SELECT * FROM cart");
    $stmt->execute();
    $stmt->store_result();
    $rows = $stmt->num_rows();

    echo $rows;
}

if (isset($_GET["remove"])) {
    $id = $_GET['remove'];

    $stmt = $conn->prepare("DELETE FROM cart where id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $_SESSION['showAlert'] = 'block';
    $_SESSION['message'] = 'Item removed from you cart Successfully!';
    header('location:cart.php');
}


if (isset($_GET['clear'])) {
    $stmt = $conn->prepare("DELETE FROM cart");
    $stmt->execute();
    $_SESSION['showAlert'] = 'block';
    $_SESSION['message'] = 'All Item Removed From Cart Successfully!';
    header('location:cart.php');

}

if (isset($_POST['qty'])) {
    $qty = $_POST['qty'];
    $pid = $_POST['pid'];
    $pprice = $_POST['pprice'];

    $tprice = $qty * $pprice;

    $stmt = $conn->prepare("UPDATE cart SET qty=? , total_price=? WHERE id=?");
    $stmt->bind_param("isi", $qty, $tprice, $pid);
    $stmt->execute();
}
if (isset($_GET["remove"])) {
    $id = $_GET['remove'];

    $stmt = $conn->prepare("DELETE FROM products where id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $_SESSION['showAlert'] = 'block';
    $_SESSION['message'] = 'Item removed from you table Successfully!';
    header('location:viewproduct.php');
}
if (isset($_POST['action']) && $_POST['action'] == 'order') {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $pmode = $_POST['pmode'];
    $products = $_POST['products'];
    $grand_total = $_POST['grand_total'];


    $stmt = $conn->prepare("INSERT INTO orders (name, email, phone, address, pmode, products, amount_paid) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssd", $name, $email, $phone, $address, $pmode, $products, $grand_total);
    $stmt->execute();


    $stmt = $conn->prepare("DELETE FROM cart");
    $stmt->execute();


    echo "<div class='alert alert-success text-center p-4 rounded shadow'>";
    echo "<h4 class='fw-bold text-success'>Thank you, $name!</h4>";
    echo "<p class='mb-1'><b>Email:</b> $email</p>";
    echo "<p class='mb-1'><b>Phone:</b> $phone</p>";
    echo "<p class='mb-1'><b>Address:</b> $address</p>";
    echo "<p class='mb-1'><b>Payment Mode:</b> <span class='text-primary'>$pmode</span></p>";
    echo "<p class='fs-5 fw-bold text-danger'><b>Amount Paid:</b> " . number_format($grand_total, 2) . "/=</p>";
    echo "</div>";
}


?>