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
    <meta charset='utf-8'>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
   <script src="js/menu.js"></script>

   <link rel="stylesheet" href="menu_style.css" type="text/css">
   <link rel="stylesheet" href="style.css" type="text/css"> 

</head>
<body>




    <div id="cssmenu" >
<ul class="align-bottom">
   <li><a href="index.php" id="logo"><img class="logo" src="img/logo.png" alt="logo picture" ></a></li>
   <?php 
    $query = $pdo->prepare('SELECT * FROM category');
    $query->execute();
    $categories = $query->fetchall();
    $cate_id = isset($_SESSION['cate_id'])?$_SESSION['cate_id']:0;

    $found = false;
    foreach ($categories as $item) {
	if($item['id'] == $cate_id) {
		echo "<li class='active'><a href='#'>".$item['name']."</a>
			<ul>";
		$found = true;
		break;
	}
    }
	
    if(!$found) {
	echo "<li class='active'><a href='#'>All</a>
			<ul>";
    } else {
	echo "<li><a onclick='update_category(0)'a>All</a></li>";
    }

    foreach ($categories as $item) {
	echo "<li><a onclick='update_category(".$item['id'].")'a>".$item['name']."</a></li>";
    }
	echo '</ul></li>';
	?>
   

<li>
   <!-- Search form -->
   <form method="get" action="index.php" class="search_form">
     <input type="text" name="search_str" id="search_input" class="align-bottom" onfocus="toLarge(this)" onblur="toSmall(this)" pattern="^[0-9a-zA-Z]{1,20}$" required/>
     <input type="hidden" name="action"value="search"/>
     <button type="submit" class="searchButton" >Search</button>
   </form>
</li>

<!--
	<button href="account_menu.php" id="signin" onclick="window.location.href='account_menu.php'">Home</button>
-->
<?php
	if(!isset($_SESSION['logged_in'])) {
?>
 	<li>
	<button href="signin.php" id="signin" onclick="window.location.href='signin.php'">Sign in</button>
	</li>
<?php 
	} else {
?>
	<li class="float_right"><a href='account_menu.php' >Hello, <?php echo $_SESSION['nick_name'];?></a></li>
	<li><a href="signout.php" id="signout" onclick="window.location.href='signout.php'">Sign out</a></li>
<?php
	}
?>
</li>
</ul>


        </div>


<script>
function update_category(sel) {
	//var id = sel.value;
	var id = sel;
	//document.write(id);
	window.location.href = "index.php?cate_id="+id+"&page_num=1";
}

function toLarge(sel) {
//	document.getElementById(sel.id).style.width="50%";
}
function toSmall(sel) {
//	document.getElementById(sel.id).style.width="10%";
}
</script>
 
<!-- The <div> will be closed in footer.php-->

