<?php
session_start();
include_once('includes/connection.php');
include_once('includes/product.php');
include_once('includes/user.php');

//instantiate Product and User class.
$product = new Product();
$user = new User();

if(isset($_SESSION['logged_in'])) {
    $page_title = "View and Edit Items";
    include("includes/header.php");

    $user_id = $_SESSION['user_id'];
    $query = $pdo->prepare("SELECT p.id, p.name, p.create_date, c.name AS cname FROM product AS p INNER JOIN category AS c ON p.category_id = c.id  WHERE user_id = ? ORDER BY id DESC");
    $query->bindValue(1, $user_id);
    $query->execute() or die(print_r($query->errorInfo()));

    $products = $query->fetchAll(PDO::FETCH_ASSOC);
    $counter = 1;

    print("<br>&nbsp;&nbsp;<a href='account_menu.php' id='account'>Your Account</a>&nbsp;&gt;&nbsp;<span style='color: indianred'>View Products</span>");
    print("<div class='box' xmlns='http://www.w3.org/1999/html'>");
    print("<h3>List of Products</h3>");

    print("<table><tr><th>SNum</th><th>Product</th><th>Uploaded on</th><th>Category</th><th>Edit Price</th></tr>");
    foreach($products as $row){
        print("<tr>");
        print("<td>$counter</td><td><a href='product_detail.php?id=".$row['id']."'>".$row['name']."</a></td><td>".$row['create_date']."</td><td>".$row['cname']."</td>");
        print("<td><a href='edit_item.php?pid=".$row['id']."'>Edit</a></td>");
        print("</tr>");
        $counter++;
    }
    print("</table>");
    print("</div>");
    include("includes/footer.php");
} else {
    include("signin.php");
}

?>          