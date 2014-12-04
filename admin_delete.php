<?php
session_start();
include_once("includes/connection.php");
include_once("includes/product.php");
$product = new Product();

if(isset($_SESSION['logged_in'])) {
    include('includes/header.php');

    if(!isset($_GET['id'])) {
	header("Location: index.php");
    }
    $id = $_GET['id'];

    if(isset($_GET['confirm_state'])) {
	if($_SESSION['type']=='admin'){
		$product->delete_data($id);
		echo "<div class='box_center'><br><br><br>";
		echo "<p>Delete complete!</p>";
		echo "<a href='index.php'>Home Page</a><br><br><br></div>";
	} else {
		header("Location: index.php");
	}
   
     } else {
	
?>

<div class="box_center">
<br><br><br>
<form method="get" action="admin_delete.php">
<p>Are you sure to delete this item permanently ? </p>
<input type="hidden" name="confirm_state" value="true">
<input type="hidden" name="id" value="<?php echo $id;?>">
<input type="submit" value="Yes" class="myButton">
<input type="button" value="No" onclick="javascript:window.location='index.php'" class="myButton">
</form>
<br><br><br>
</div>

<?php
    include('includes/footer.php');
 }

} else {
    header("Location: signin.php");
}
?>

