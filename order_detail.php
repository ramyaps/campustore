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
    $user_id = $_SESSION['user_id'];
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

        //fetch the image file-path for the given product id from picture table
        $image = "./uploads/icons/".$product_data['icon'];

        //fetch user's review if exists
        $review = "";
        if($order_data['status'] == 'Delivered'){
            $query = $pdo->prepare("SELECT * FROM review WHERE order_id = ?");
            $query->bindValue(1, $order_id);
            $query->execute() or die(print_r($query->errorInfo()));

            $review = $query->fetch(PDO::FETCH_ASSOC);
        }

        //set filter to order status - to be used in navigating back to order history page
        $filter = $order_data['status'];

        //set the user profile to buyer or seller - to be used in modifying data displayed in order details
        //also for navigating back to order history or sold history based on user profile.
        if($user_id == $order_data['buyer_id']){
            $profile = 'buyer';
        } elseif($user_id == $product_data['user_id']){
            $profile = 'seller';
        }

        $page_title = "Webshelf- Order Detail";
        include_once('includes/header.php');

?>

    <br xmlns="http://www.w3.org/1999/html">
    &nbsp;&nbsp;<a href="account_menu.php" id="account">Your Account</a>&nbsp;&gt;&nbsp;
    <?php
        if($profile == 'buyer') {
            print("<a href='order_history.php'>Orders</a>&nbsp;&gt;&nbsp;");
        }elseif($profile == 'seller'){
            print("<a href='sold_history.php'>Sold History</a>&nbsp;&gt;&nbsp;");
        }
    ?>
    <span style="color: indianred">Order Details </span>
    <br>
    <ul class="nostyle_horizontal box">
        <li>
            <span class="bold">Order placed on</span><br>
            <?php print($order_data['date_time']); ?>
        </li>
        <li>
            <span class="bold">Order number</span><br>
            <?php print($order_data['id']); ?>
        </li>
        <li>
            <span class="bold">Quantity</span><br>
            <?php print($order_data['quantity']); ?>
        </li>
        <li>
            <span class="bold">Total Price</span><br>
            <?php print("$ ".$order_data['total_price']); ?>
        </li>
        <li>
            <span class="bold">Ordered by</span><br>
            <?php print($buyer_data['first_name']." ".$buyer_data['last_name'] ); ?>
        </li>
    </ul>
    <div class='box'>
        <div class="left_column display_inline">
            <p><span style="color: coral;font-weight: bold">Order Status:</span> <?php echo $order_data['status']?></p>
            <img height="auto" width="100px" src="<?php echo $image?>" alt="product image" class="product">
            <p>
                <?php if($profile == 'buyer'){ ?>
                    <a href="order_history.php?filter=<?php echo $filter?>">
                <?php }elseif($profile == 'seller'){ ?>
                    <a href="sold_history.php">
                <?php } ?>
                    <input type='button' value='Back'>
                </a>
            </p>
        </div>

    <?php
        if($profile == 'buyer') {
    ?>
        <div class="display_inline center_column text_wrap">
            <h3><?php echo $product_data['name'] ?></h3>

            <p>Seller: &nbsp;<?php echo $seller_data['first_name'] . " " . $seller_data['last_name'] ?></p>

            <p>Email: <?php echo $seller_data['email'] ?></p>

            <form method="post" action="message.php">
                <input type="hidden" name="receiver_id" value="<?php echo $seller_data['id'] ?>">
                Contact Seller <input type="submit" name="msg_submit" value="Send Message">
            </form>
        </div>
    <?php
        }elseif($profile =='seller') {
    ?>
        <div class="display_inline center_column text_wrap">
            <h3><?php echo $product_data['name'] ?></h3>

            <p>Buyer: &nbsp;<?php echo $buyer_data['first_name'] . " " . $buyer_data['last_name'] ?></p>

            <p>Email: <?php echo $buyer_data['email'] ?></p>

            <form method="post" action="message.php">
                <input type="hidden" name="receiver_id" value="<?php echo $buyer_data['id'] ?>">
                Contact Buyer <input type="submit" name="msg_submit" value="Send Message">
            </form>
        </div>
    <?php
        }
     ?>
    <?php
        print("<div class='right_column display_inline text_wrap'>");
            print("<p><span style='color: coral;font-weight: bold'>Review:</span></p>");
            if(!empty($review)){
                print("<p><em>".$review['content']."</em></p>");
                print("<p>Rating:<em> <meter value='".$review['stars']."' min='1' max='5'></meter>".$review['stars']."/5</em> </p>");
            }else{
                print("<p><em>Feedback not available</em></p>");
            }

            /*Users can cancel those orders that are not completed yet. And they can provide feedback for only those orders
            that are delivered and completed.
            If the order status is still "ORDERED", provide user a link to cancel it.*/
            if($profile == 'buyer'){
                if($order_data['status'] == 'Ordered'){
                    print("<input type='button' onclick='toggleCancel(1);' value='Cancel Order'>");
                }

                //if the order is already delivered & completed, provide user link to give feedback if not already given
                elseif($order_data['status'] == 'Delivered') {
                    if(empty($review)) {
                        print("<a href='feedback.php?order_id=$order_id'>Leave feedback</a><br>");
                    }
                }
            }
        print("</div>");

        print("<div class='left_column display_inline'> </div>");
        print("<div class='center_column display_inline'>");
            print("<p id='confirm' class='box' style='display: none'>Are you sure you want to cancel this order?<br><br>");
            print("<a href='order.php?action=cancel&order_id=".$order_data['id']."'>Yes</a>&nbsp;&nbsp;&nbsp;&nbsp;");
            print("<a href='#' onclick='toggleCancel(0);'>No</a></p>");
        print("</div>");
    ?>
    </div>
        <!-- Script to confirm order cancellation -->
        <script>
            function toggleCancel(i){
                var node = document.getElementById("confirm");
                if(i == 0){
                    node.style.display = 'none';
                }else if(i == 1) {
                    node.style.display = 'inline-block';
                }
            }
            function hide(){
                alert("onload");
                this.style.visibility = 'hidden';
            }
        </script>

        <?php
    include('includes/footer.php');
    }
} else {                                                      
    include("signin.php");                                    
}                                                             
?>

