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
    $page_title = "Webshelf-Order History";
    include('includes/header.php');
    //Get the filter for order status
    $filter = isset($_GET['filter']) ? $_GET['filter'] :'';
    $user_id = $_SESSION['user_id'];

    if(empty($filter) || $filter == 'Ordered') {
        //fetch active orders
        $query = $pdo->prepare("SELECT o.id, o.date_time, p.name FROM orders AS o JOIN product AS p ON o.product_id = p.id WHERE buyer_id = ? AND status = ? ORDER BY o.id DESC");
        $query->bindValue(1, $user_id);
        $query->bindValue(2, 'Ordered');
        $query->execute() or die(print_r($query->errorInfo()));

        $purchase = $query->fetchAll(PDO::FETCH_ASSOC);

    }
    elseif($filter == 'Delivered'){
        //fetch completed orders
        $query = $pdo->prepare("SELECT o.id, o.date_time, p.name, r.stars FROM ( orders AS o JOIN product AS p ON o.product_id = p.id ) LEFT OUTER JOIN review AS r ON o.id = r.order_id WHERE buyer_id = ? AND STATUS = ? ORDER BY o.id DESC");
        $query->bindValue(1, $user_id);
        $query->bindValue(2, 'Delivered');
        $query->execute() or die(print_r($query->errorInfo()));

        $purchase = $query->fetchAll(PDO::FETCH_ASSOC);

    }
    elseif($filter == 'Cancelled'){
        //fetch cancelled orders
        $query = $pdo->prepare("SELECT o.id, o.date_time, p.name FROM orders AS o JOIN product AS p ON o.product_id = p.id WHERE buyer_id = ? AND status = ? ORDER BY o.id DESC");
        $query->bindValue(1, $user_id);
        $query->bindValue(2, 'Cancelled');
        $query->execute() or die(print_r($query->errorInfo()));

        $purchase = $query->fetchAll(PDO::FETCH_ASSOC);
    }

    print("<br>&nbsp;&nbsp;<a href='account_menu.php' id='account'>Your Account</a>&nbsp;&gt;&nbsp;<span style='color: indianred'>Orders</span>");
    print("<div class='box' xmlns='http://www.w3.org/1999/html'>");
//    print("<div class='center_column' >");

    print("<h3>List of Purchase Orders</h3>");
    print("<a href='order_history.php'". (($filter=='' || $filter=='Ordered') ? "style='color: indianred'>" : ">")."Active</a>&nbsp;&nbsp;&nbsp;&nbsp;");
    print("<a href='order_history.php?filter=Delivered'". ($filter=='Delivered' ? "style='color: indianred'>" : ">")."Completed</a>&nbsp;&nbsp;&nbsp;&nbsp;");
    print("<a href='order_history.php?filter=Cancelled'". ($filter=='Cancelled' ? "style='color: indianred'>" : ">")."Cancelled</a>");
    if($filter == 'Delivered') {
        print("<br><br><table><tr><th>Order Id</th><th>Product</th><th>Date</th><th>Feedback</th></tr>");
        foreach($purchase AS $row){
            print("<tr>");
            print("<td>".$row['id']."</td><td><a href='order_detail.php?order_id=".$row['id']."'>".$row['name']."</a></td><td>".$row['date_time']."</td>");
            if(empty($row['stars'])){
                print("<td><a href='feedback.php?order_id=".$row['id']."'>Leave feedback</a></td>");
            }else {
                print("<td>Reviewed</td>");
            }
            print("</tr>");
        }
    }else {
        print("<br><br><table><tr><th>Order Id</th><th>Product</th><th>Date</th></tr>");
        foreach($purchase AS $row){
            print("<tr>");
            print("<td>".$row['id']."</td><td><a href='order_detail.php?order_id=".$row['id']."'>".$row['name']."</a></td><td>".$row['date_time']."</td>");
            print("</tr>");
        }
    }
    print("</table>");
//    print("</div>");
    print("</div>");
    ?>

<?php
    include('includes/footer.php');
} else {
    include("signin.php");
}
?>
