<?php
session_start();
include_once('includes/connection.php');
include_once('includes/product.php');
include_once('includes/user.php');

//instantiate Product and User class.
$product = new Product();
$user = new User();

//Check if the user has logged in
if(isset($_SESSION['logged_in'])) {
    if(isset($_GET['order_id'])) {
        $order_id = $_GET['order_id'];

        //Get the details of the given order id
        $query = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $query->bindValue(1, $order_id);
        $query->execute() or die(print_r($query->errorInfo()));

        $order_data = $query->fetch(PDO::FETCH_ASSOC);

        $buyer_data = $user->fetch_user($order_data['buyer_id']);          //fetch buyer details
        $product_data = $product->fetch_data($order_data['product_id']);   //fetch product details
        $seller_data = $user->fetch_user($product_data['user_id']);        //fetch seller details
        $feedback = $user->fetch_feedback($product_data['user_id']);       // fetch seller feedback

        $page_title = "Webshelf- Order Detail";
        include_once('includes/header.php');

?>

    <div id="order" class="container">
        <br>&nbsp;&nbsp;<a href="account_menu.php" id="account">Your Account</a>&nbsp;&gt;&nbsp;
        <a href="order_history.php">Orders</a>&nbsp;&gt;&nbsp;<span style="color: indianred">Order Details </span>
        <br>
        <ul class="nostyle_horizontal box">
            <li>
                Order placed on<br>
                <?php print($order_data['date_time']); ?>
            </li>
            <li>
                Order number<br>
                <?php print($order_data['id']); ?>
            </li>
            <li>
                Quantity<br>
                <?php print($order_data['quantity']); ?>
            </li>
            <li>
                Total Price<br>
                <?php print("$ ".$order_data['total_price']); ?>
            </li>
            <li>
                Ordered by<br>
                <?php print($buyer_data['first_name']." ".$buyer_data['last_name'] ); ?>
            </li>
        </ul>

        <br>

        <div class="left_column display_inline">
            <p>Order Status: <span style="color: coral;font-weight: bold"><?php echo $order_data['status']?></span></p>
            <img height="150px" width="100px" src="<?php echo $image?>" alt="product image" class="product">
        </div>
        <div class="display_inline center_column text_wrap">
            <h3><?php echo $product_data['name']?></h3>
            <p>Seller: &nbsp;<?php echo $seller_data['first_name']." ".$seller_data['last_name'] ?></p>
<!--            <p>Contact: --><?php //echo $seller_data['phone'] ?><!--</p>-->
            <p>Email: <?php echo $seller_data['email']?></p>
            <form method="post" action="message.php">
                <input type="hidden" name="receiver_id" value="<?php echo $seller_data['id']?>">
                Contact Seller <input type="submit" name="msg_submit" value="Send Message">
            </form>
        </div>
        <br><br>
    </div><br>

    <?php
        print("<div class='left_column display_inline'>");
        //Users can cancel those orders that are not completed yet. And they can provide feedback for those orders only
        //that are delivered and completed.
        //If the order status is still "ORDERED", provide user a link to cancel it.
        if($order_data['status'] == 'Ordered'){
        print("&nbsp;&nbsp;&nbsp;&nbsp;<a href='order.php?action=cancel&order_id=".$order_data['id']."'>Cancel order</a>");
        }
        //if the order is already delivered & completed, provide user link to give feedback
        elseif($order_data['status'] == 'Delivered') {
        print("&nbsp;&nbsp;&nbsp;&nbsp;<a href='feedback.php'>Leave feedback</a><br>");
        }
        print("</div>");
    include('includes/footer.php');
    }
} else {                                                      
    include("signin.php");                                    
}                                                             
?>                                                            
     
