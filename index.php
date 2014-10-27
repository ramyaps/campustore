<?php
include_once('includes/connection.php');
include_once('includes/product.php');
$product = new Product();
$data = $product->fetch_all();

$page_title = "index.php";
include('includes/header.php');
?>

       <ol>
	    <?php foreach ($data as $item) { ?>
	    <li>
		<a href="product_detail.php?id=<?php echo $item['id'];?>">
		<?php echo $item['name']; ?>
		</a> 
	        <small>posted in
		   <?php 
			date_default_timezone_set('America/Detroit');
			echo date('l jS', $article['create_date']);?>
		</small>
   	    </i>
	<?php } ?>
        <ol>
	<br/><br/>
        <a href="signin.php" id="login">Sign in</a>
	&nbsp;&nbsp;
        <a href="signup.php" id="signup">Sign up</a>
	<br><br>
        <a href="home_page.php" id="home_page">Home Page</a>

<?php 
include('includes/footer.php');
?>
