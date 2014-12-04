<?php
SESSION_START();

include_once('../includes/connection.php');
include_once('../includes/product.php');

$product = new Product();
$data = $product->fetch_all();

if (isset($_SESSION['logged_in'])) {
    if (isset($_POST['delete_ids'])) {
	foreach($_POST['delete_ids'] as $delete_id) {
	    $product->delete_data($delete_id);
	    echo "delete ";
	    echo $delete_id;
	    echo "<br/>";
	}
	$data = $product->fetch_all();
    }
?>
    
<!DOCTYPE html>
<head>
    <title>WebShelf</title>
    <link rel="stylesheet" href="../style.css" type="text/css">
</head>

<body>
    <div class = "container">
	<a href="../index.php" id="logo">WebShelf</a>

	<br /><br />
    <form action="delete.php" method="POST">
	<?php foreach ($data as $item) {?>
	    <input type="checkbox" name="delete_ids[]" value=<?php echo $item["id"]?>>
		<?php echo $item["name"]?></option>
	<?php }?>
	<input type="submit" value="Submit">
    </form>	
    </div>
</body>
</html>



<?php
} else {
    header('Location: index.php');
}

?>
