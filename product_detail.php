<?php
session_start();
include_once('includes/connection.php');
include_once('includes/product.php');
include_once('includes/user.php');

$product = new Product();
$user = new User();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $data = $product->fetch_data($id); //fetch product data

    //fetch number of items bought so far out of the original stock posted by the user
    $query = $pdo->prepare("SELECT SUM(quantity) AS bought FROM orders WHERE product_id = ? AND (`status` = ? OR `STATUS` = ?) GROUP BY product_id;");
    $query->bindValue(1, $id);
    $query->bindValue(2, 'Ordered');
    $query->bindValue(3, 'Delivered');
    $query->execute() or die(print_r($query->errorInfo()));

    $quantity_bought = $query->fetch(PDO::FETCH_ASSOC);

    //find the remaining quantity now available
    $quantity_available = $data['quantity'] - $quantity_bought['bought'];

    //fetch the image file-path for the given product id from picture table
    $image = "./uploads/icons/".$data['icon'];
    //fetch seller data
    $seller = $user->fetch_user( $data['user_id'] );

    //fetch seller feedback
    $feedback = $user->fetch_feedback( $data['user_id'] );

    $page_title = "Webshelf-Product details: ".$data['name'];
    include_once('includes/header.php');
?>
<div class="box">
<div id="display_product" xmlns="http://www.w3.org/1999/html">
    <div class="left_column display_inline">
        <img height=auto width=220px src="<?php echo $image ?>" alt="product image" class="product">
        <p><input type='button' value='Back' onclick='history.go(-1)'></p>
    </div>

    <div class="display_inline center_column text_wrap">
        <h3><?php echo $data['name']?></h3>
        <p><?php echo $data['description']?></p>
        <p>Sold by: &nbsp;<span style="color: indianred"><?php echo $seller['first_name']." ".$seller['last_name'] ?></span></p>
        <em>User Feedback&nbsp;<meter value="<?php echo $feedback ?>" min="0" max="5"></meter><?php echo " ".$feedback."/5.0" ?></em><br><br>
        <p>Price: &nbsp;&dollar; <?php echo number_format($data['price'],2) ?> </p>
        <p>Quantity Available: &nbsp;<?php echo $quantity_available?> </p><br><br>

    </div>

    <div class="right_column display_inline text_wrap">
        <br><br>
        <?php
        if($data['order_status'] === 'Available' && ($quantity_available > 0)) { ?>
            <form action="order.php?action=buy" method="post">
                <input type="hidden" name="id" value=<?php echo $data['id']; ?>>
                Enter Quantity: <input type="number" name="quantity" min="1" max= <?php echo $quantity_available ?> required>&nbsp;&nbsp;
                <input type="submit" name="ord_submit" value="Order">
            </form>
        <?php
        } else { ?>
            <p class="comments">Not available.</p>
        <?php
        }
        ?>
        <br><form method="post" action="message.php">
            <input type="hidden" name="receiver_id" value="<?php echo $seller['id']?>">
            Contact Seller <input type="submit" name="msg_submit" value="Send Message">
        </form>
    </div>
    <br>
</div>
</div>
<?php
    include('includes/footer.php');
} else {
    header('Location: index.php');
    exit();
}

?>
