<?php
include_once('includes/connection.php');
include_once('includes/product.php');
$page_title = "index.php";
include('includes/header.php');
$product = new Product();
$data = $product->fetch_all();


?>
	<table class="show_table">
	    <?php 
		$MAX_COLUMN = 4;
		$column_count = 0;
		foreach ($data as $item) { 
		    $icon_path = "./uploads/icons/".$item['icon']; 
		    if(!file_exists($icon_path) || is_dir($icon_path)){
			$icon_path = "./uploads/icons/"."default.png";
		    }
		    
		    if($column_count % $MAX_COLUMN == 0) {
			echo "<tr>";
		    }	
	    ?>
		    <td>
		    <div class="item">
			<img class="item_icon" src=<?php echo $icon_path?> alt="item picture">
			<br>
			<a href="product_detail.php?id=<?php echo $item['id'];?>"><?php echo $item['name']; ?></a> 
			<br>
	       		<!--  
			<small>posted in
		   	<?php 
				date_default_timezone_set('America/Detroit');
				echo date('l jS', $article['create_date']);
			?>
			
			</small>
			-->
   		    </div>
		    </td>
	    <?php
		   $column_count++;
	   	  if($column_count % $MAX_COLUMN ==0){
			echo "</tr>";
	           }
	     } 
	    ?>
	</table>
<?php 
include('includes/footer.php');
?>

