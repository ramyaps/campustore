<?php
session_start();
include_once("includes/connection.php");
include_once("includes/product.php");
$product = new Product();

if(isset($_SESSION['logged_in'])) {
echo "start";
    if((!isset($_POST['product_id'])) or empty($_POST['product_id'])){
	header("Location: index.php");
    }
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
echo "ente";
echo $user_id."  ".$product_id;
    $product->addToWaitingList($user_id, $product_id);
 echo "end";
    $page_title = "Waiting list";
    include_once('includes/header.php');
?>

    <div class="box_center">
	<br><br><br>
        <h3>You are in the waiting list now! You will automatically order this item and receive a notification message once it is available!</h3>
	<a href="index.php">Home Page</a>
	<br><br><br>
   </div>
<?php
    include_once("includes/footer.php");
} else {
    header("Location: signin.php");
}
?>
