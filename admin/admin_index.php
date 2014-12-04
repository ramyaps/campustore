<?php

session_start();
include_once('../includes/connection.php');

if (isset($_SESSION['logged_in'])) {
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
	<ol>
	    <li><a href="add.php">Add Product</a></li>
	    <li><a href="delete.php">Delete Product</a></li>
	    <li><a href="../signout.php">Sign out</a></li>

	</ol>
    </div>
</body>
</html>

<?php
} else {
    include("../signin.php");
}
?>
