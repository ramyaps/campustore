<?php
session_start();
//include_once 'includes/header.php';
//include_once 'includes/footer.php';
if(isset($_SESSION['logged_in'])) {
    $user_id = $_SESSION['user_id'];
    $page_title = "Account Menu";
    include_once('includes/header.php');
?>

    <div class="box menu">
        <h3>Seller Account</h3>
        <p><a href="sold_history.php">Sold History</a></p>
        <p><a href="view_products.php">Edit Products</a></p>
        <p><a href="sell.php">Sell Product</a></p>
    </div>
    <div class="box menu">
        <h3>Buyer Account</h3>
        <p><a href="order_history.php">Order History</a></p>
        <p><a href="order_history.php?filter=Delivered">Feedback</a></p>
    </div>
    <div class="box menu">
        <h3>Settings</h3>
        <p><a href="update_personal_info.php">Update profile</a></p>
        <p><a href="change_password.php">Change Password</a></p>
        <p><a href="message_box.php">Mailbox</a></p>
    </div>
<?php
    include_once("includes/footer.php");
} else {
    header("Location: signin.php");
}
?>
