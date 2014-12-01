<?php
session_start();
include_once('includes/connection.php');
include_once('includes/product.php');
$page_title = "index.php";
include('includes/header.php');
$product = new Product();
//$data = $product->fetch_all();
$category_id = isset($_GET['cate_id']) ? $_GET['cate_id'] : 0;
$page_num = isset($_GET['page_num']) ? $_GET['page_num'] : 1;
if(isset($_GET['action']) and $_GET['action'] == 'search') {
	$keywords = $_GET['search_str'];
	$data = $product->search($keywords, $page_num);
} else {
	$data = $product->fetch_by_category($category_id, $page_num);
}

?>
<div class="box">
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

<div class="page_nav">
<?php
if($page_num > 1) {
	echo "<a href='index.php?cate_id=".$category_id."&page_num=".($page_num-1)."'>prev&nbsp;&nbsp;</a>";
}

if(isset($_GET['action']) and $_GET['action'] == 'search') {
	$next_data = $product->search($keywords, $page_num+1);
} else {
	$next_data = $product->fetch_by_category($category_id, $page_num+1);
}
if(count($next_data) > 0) {
	echo "<a href='index.php?cate_id=".$category_id."&page_num=".($page_num+1)."'>next</a>";
}

?>
</div>

</div>
<?php 
include('includes/footer.php');
?>

