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
    $page_title = "View Sold History";
    include_once('includes/header.php');
    //Get the filter for order status
    $filter = isset($_GET['filter']) ? $_GET['filter'] :'';
    $user_id = $_SESSION['user_id'];
    if($filter == 'Delivered' || empty($filter)){
        $query = $pdo->prepare("select o.id id, o.date_time datetime, pd.name productName from campustore.orders o, campustore.product pd where o.product_id=pd.id and pd.user_id = ? and o.status = ? ORDER BY o.id DESC ");
        $query->bindValue(1, $user_id);
        $query->bindValue(2, 'Delivered');
        $query->execute() or die(print_r($query->errorInfo()));

        $orders = $query->fetchAll(PDO::FETCH_ASSOC);
    }elseif($filter == 'Ordered'){
        $query = $pdo->prepare("select o.id id, o.date_time datetime, pd.name productName from campustore.orders o, campustore.product pd where o.product_id=pd.id and pd.user_id = ? and o.status = ? ORDER BY o.id DESC ");
        $query->bindValue(1, $user_id);
        $query->bindValue(2, 'Ordered');
        $query->execute() or die(print_r($query->errorInfo()));

        $orders = $query->fetchAll(PDO::FETCH_ASSOC);
    }

    print("<br>&nbsp;&nbsp;<a href='account_menu.php' id='account'>Your Account</a>&nbsp;&gt;&nbsp;<span style='color: indianred'>Sold History</span>");
    print("<div class='box' xmlns='http://www.w3.org/1999/html'>");
        print("<h3>List of products sold</h3>");
        print("<a href='sold_history.php'". (($filter=='' || $filter=='Delivered') ? "style='color: indianred'>" : ">")."Delivered</a>&nbsp;&nbsp;&nbsp;&nbsp;");
        print("<a href='sold_history.php?filter=Ordered'". ($filter=='Ordered' ? "style='color: indianred'>" : ">")."Pending</a>&nbsp;&nbsp;&nbsp;&nbsp;");
	print("<br><br>");
        print("<table><tr><th>Order Id</th><th>Product</th><th>Date</th></tr>");
        foreach($orders as $row) {
            print("<tr>");
            print("<td>".$row['id']."</td><td><a href='order_detail.php?order_id=".$row['id']."'>".$row['productName']."</a></td><td>".$row['datetime']."</td>");
            print("</tr>");
        }
        print("</table>");
    print("</div>");
    include('includes/footer.php');
}else {
    include('includes/signin.php');
}
?>
