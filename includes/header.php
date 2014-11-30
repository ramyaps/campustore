<?php
include_once('includes/connection.php');
if(isset($_GET['cate_id'])) {
	$_SESSION['cate_id'] = $_GET['cate_id'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $page_title?></title>
    <link rel="stylesheet" href="style.css" type="text/css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

</head>
<body>
    <div class="header">
        <a href="index.php" id="logo"><img class="logo" src="img/logo.png" alt="logo picture" ></a>
	<select name="category_list" id="category_dropdown" onchange="update_category(this)">
	<?php 
	    $query = $pdo->prepare('SELECT * FROM category');
	    $query->execute();
	    $categories = $query->fetchall();
	    $cate_id = isset($_SESSION['cate_id'])?$_SESSION['cate_id']:1;
	    foreach ($categories as $item) {
		if($item['id'] == $cate_id) {
			echo "<option value='".$item['id']."' selected='selected'>".$item['name']."</option>";
		} else {
			echo "<option value='".$item['id']."'>".$item['name']."</option>";
		}
	    }
	?>
	</select>
<?php 
	
?>
<script>
function update_category(sel) {
	var id = sel.value;
	//document.write(id);
	window.location.href = "index.php?cate_id="+id;
}
</script>
        <input type="text" name="searchStr" id="search_input">
	<button name="searchBtn" id="searchButton">Search</button>
	<button href="account_menu.php" id="signin" onclick="window.location.href='account_menu.php'">Home</button>
<?php
	if(!isset($_SESSION['logged_in'])) {
?>

	<button href="signin.php" id="signin" onclick="window.location.href='signin.php'">Sign in</button>
<?php 
	} else {
?>
	<button href="signout.php" id="signout" onclick="window.location.href='signout.php'">Sign out</button>
<?php
	}
?>
    </div>

    <div class="container">

<!-- The <div> will be closed in footer.php-->

