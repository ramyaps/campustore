<?php
session_start();
//include_once 'includes/header.php';
//include_once 'includes/footer.php';
if(isset($_SESSION['logged_in'])) {
    $user_id = $_SESSION['user_id'];
    include_once('includes/header.php');
?>

    <div class="box menu">
        <h3>Seller Account</h3>
        <p><a href="sold_history.php">View Sold History</a></p>
        <p><a href="edit_item.php">View & Edit Products listed</a></p>
        <p><a href="sell.php">Sell Product</a></p>
    </div>
    <div class="box menu">
        <h3>Buyer Account</h3>
        <p><a href="order_history.php">View Order History</a></p>
        <p><a href="feedback.php">Leave Feedback</a></p>
    </div>
    <div class="box menu">
        <h3>Settings</h3>
        <p><a href="order_history.php">Change Account Settings</a></p>
    </div>
<?php
    include_once("includes/footer.php");
} else {
    header("Location: signin.php");
}
?>
