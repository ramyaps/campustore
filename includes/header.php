<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $page_title?></title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
    <div class="header">
        <a href="index.php" id="logo"><img class="logo" src="img/logo.png" alt="logo picture" ></a>
	<select name="category_list" id="category_dropdown">
	<option value="Books">Books</option>
	<option value="Electronics">Electionics</option>
	</select>
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

