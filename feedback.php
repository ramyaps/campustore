<?php
session_start();
include_once('includes/connection.php');
include_once('includes/product.php');
include_once('includes/user.php');

//instantiate Product and User class.
$product = new Product();
$user = new User();

if(isset($_SESSION['logged_in'])) {
    $page_title = "Leave Feedback for order";
    include('includes/header.php');

    $order_id = isset($_GET['order_id']) ? $_GET['order_id'] : "";
    $reviewer_id = $_SESSION['user_id'];
    $action = isset($_GET['action']) ? $_GET['action'] : "";

    if(!empty($action) && $action == 'review'){
        $seller_id = $_POST['seller'];
        $reviewer_id = $_POST['reviewer'];
        $order_id = $_GET['order_id'];
        $content = $_POST['content'];
        $stars = $_POST['stars'];

        $query = $pdo->prepare("INSERT INTO review (user_id, reviewer_id, order_id, content, stars) VALUES (?,?,?,?,?)");
        $query->bindValue(1, $seller_id);
        $query->bindValue(2, $reviewer_id);
        $query->bindValue(3, $order_id);
        $query->bindValue(4, $content);
        $query->bindValue(5, $stars);
        $query->execute() or die(print_r($query->errorInfo()));

        print("<br>&nbsp;&nbsp;<a href='account_menu.php' id='account'>Your Account</a>&nbsp;&gt;&nbsp;");
        print("<a href='order_history.php?filter=Delivered'>Orders</a>&nbsp;&gt;&nbsp;<span style='color: indianred'>Leave Feedback </span>");
        print("<div class='box'>");
        print("<div class='center_column'>");
            print("<br><h3 class='center_align'>Feedback Updated Successfully!! </h3>");
            print("<p class='center_align'><a href='order_detail.php?order_id=$order_id'><input type='button' value='View Feedback'></a></p>");
        print("</div></div>");
        include_once('includes/footer.php');
        die();
    }
//Get the details of the given order id
    $query = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $query->bindValue(1, $order_id);
    $query->execute() or die(print_r($query->errorInfo()));
    $order_data = $query->fetch(PDO::FETCH_ASSOC);

    $product_data = $product->fetch_data($order_data['product_id']);

    $reviewer = $user->fetch_user($reviewer_id);
    $seller = $user->fetch_user($product_data['user_id']);

    print("<br>&nbsp;&nbsp;<a href='account_menu.php' id='account'>Your Account</a>&nbsp;&gt;&nbsp;");
    print("<a href='order_history.php?filter=Delivered'>Orders</a>&nbsp;&gt;&nbsp;<span style='color: indianred'>Leave Feedback </span>");
    print("<div class='box'>");
        print("<div class='center_column'>");
        print("<form method='post' action='feedback.php?order_id=$order_id&action=review'>");
            print("<p><span style='color: coral;font-weight: bold'> Order Being reviewed:</span></p>");
            print("<div><label class='input_label'>Order Number: </label> <span>$order_id</span></div><br>");
            print("<div><label class='input_label'>Product Name: </label><span>".$product_data['name']."</span></div><br>");
            print("<div><label class='input_label'>Seller Name: </label><span>".$seller['first_name']." ".$seller['last_name']."</span></div><br>");
            print("<div><input type='hidden' name='seller' value='".$seller['id']."'></div> <br>");
            print("<div><input type='hidden' name='reviewer' value='".$reviewer_id."'></div>");
?>
            <div>
<!--<label>Rating:</label> -->

<!--
<input type='number' name='stars' min='1' max='5' pattern='[1-5]' required></div><br>
-->
<strong class="choice">Choose a rating</strong>
<span class="star-rating">
  <input type="radio" name="stars" value="1"><i></i>
  <input type="radio" name="stars" value="2"><i></i>
  <input type="radio" name="stars" value="3"><i></i>
  <input type="radio" name="stars" value="4"><i></i>
  <input type="radio" name="stars" value="5"><i></i>
</span>
<script>
$(window).load(function(){
$(':radio').change(
function(){
console.log(this.value);
$('.choice').text( this.value + ' stars' );
}
)
});
</script>
	    <br><br>

            <div><label class='input_label'>Comments:</label></div><br>
            <div><textarea rows='8' cols='50' name='content' maxlength='5000' required></textarea></div>
            <div><input type='submit' name='review' value='Submit feedback'><input type='reset' value='Clear'><input type='button' value='Back' onclick='history.go(-1)'></div>
        </form>
    </div></div>

<?php
include('includes/footer.php');
} else {
    include("signin.php");
}
?>
