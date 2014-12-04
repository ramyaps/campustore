<?php
session_start();
include_once('includes/connection.php');
include_once('includes/product.php');
include_once('includes/user.php');

//instantiate Product and User class.
$product = new Product();
$user = new User();

if(isset($_SESSION['logged_in'])) {
    $page_title = "Edit Price & Quantity";
    include("includes/header.php");
    $product_id = isset($_GET['pid']) ? $_GET['pid'] : "";
    $action = isset($_GET['action']) ? $_GET['action'] : "";

    $user_id = $_SESSION['user_id'];
    $heading = "";
    if($action == 'edit'){
        $product_id = isset($_POST['pid']) ? $_POST['pid'] : "";
//        $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : "";
        $price = isset($_POST['price']) ? $_POST['price'] : "";
        $name = isset($_POST['name']) ? $_POST['name'] : "" ;
	$desc = isset($_POST['descr']) ? $_POST['descr'] : "";
        $query = $pdo->prepare("UPDATE product SET name = ?, price = ?, description= ?  WHERE id = ?");
        $query->bindValue(1, $name);
        $query->bindValue(2, $price);
	$query->bindValue(3, $desc);
//        $query->bindValue(3, $quantity);
        $query->bindValue(4, $product_id);
        $query->execute() or die(print_r($query->errorInfo()));

        $heading = "Updated Successfully";

    }elseif(empty($product_id)){
        print("<div class='box'>");
            print("<h3>Oops! Requested URL does not exist</h3>");
            print("Please click to go to <a href='index.php'>Index</a> page");
        print("</div>");
        include("includes/footer.php");
        die();
    }
        $product_data = $product->fetch_data($product_id);
        $query = $pdo->prepare("SELECT name FROM category WHERE id = ?");
        $query->bindValue(1, $product_data['category_id']);
        $query->execute() or die(print_r($query->errorInfo()));
        $category = $query->fetch(PDO::FETCH_ASSOC);

        print("<br>&nbsp;&nbsp;<a href='account_menu.php' id='account'>Your Account</a>&nbsp;&gt;&nbsp;");
        print("<a href='view_products.php'>View Products</a>&nbsp;&gt;&nbsp;");
        print("<span style='color: indianred'>Edit Item</span>");
        print("<div class='box' xmlns='http://www.w3.org/1999/html'>");
        print("<h3>$heading</h3>");
            print("<form  method='POST' action='edit_Item.php?action=edit'>");
                print("<input type='hidden' name='pid' value='".$product_data['id']."'>");
                print("<p><label class='input_label'>Category</label><input type='text' value='".$category['name']."' readonly></p>");
                print("<p><label class='input_label'>Name</label><input type='text' name='name' value='".$product_data['name']."' required></p>");
//                if($category == 'Books'){
//                    print("<div id='book_details'>");
//                    print("<label class='input_label'>Author</label><input type='text' name='author' value='' pattern='^[A-Za-z][A-Za-z\s]*[A-Za-z]$'>");
//                    print("<label class='input_label'>Edition</label><input type='text' name='edition'  pattern='^\w[\w\s]*[\w]$'/>");
//                    print("<label class='input_label'>Year</label><input type='text' name='year' pattern='^[1-9][0-9]{3}$'/>");
//                    print("<em class='comments'>*YYYY</em>");
//                    print("</div>");
//                }
                print("<p><label class='input_label'>Description</label><br><textarea rows='8' cols='50' name='descr'>".$product_data['description']."</textarea></p>");
//                print("<p><label class='input_label'>Quantity</label><input type='text' name='quantity' pattern='^[1-9]\d*$'  value='".$product_data['quantity']."' required>");
//                print("<em class='comments'>*Numbers only</em></p>");
                print("<p><label class='input_label'>Price</label><input type='text' name='price' pattern='^[1-9]\d*[.]\d{2}$' value='".number_format($product_data['price'],2)."' required>");
                print("<em class='comments'>*##.## (Numbers only) </em></p>");
                print("<p><input type='submit' value='Update' name='edit'>");

            print("</form>");
        print("</div>");

    include("includes/footer.php");
} else {                                                      
    include("signin.php");                                    
}                                                             

?>                                                            
     
