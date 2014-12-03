<?php
session_start();
include_once("includes/connection.php");

if (isset($_POST['email'], $_POST['password'])) {
	$username = $_POST['email'];
	$password = md5($_POST['password']);
	
	if (empty($username)) {
	    $error = "email is empty!";
	} else if(empty($password)) {
	    $error = "password is empty!";
	} else {
	    $query = $pdo->prepare("SELECT * FROM user WHERE email = ? and password = ?");
	    $query->bindValue(1, $username);
	    $query->bindValue(2, $password);
	    $query->execute();
	    $num = $query->rowCount();

	    if ($num == 1) {
		$_SESSION['logged_in'] = true;
		$users = $query->fetchall();
		$user_type = $users[0]['type'];
            /* Added user id to session variable  - Change by ramyaps*/
        $_SESSION['user_id'] = $users[0]['id'];
	$_SESSION['nick_name'] = $users[0]['nick_name'];
            /* End of change by ramyaps*/

		if($user_type == "admin") {
		    //echo "admin";
		    header("Location: admin_index.php");
		} else {
		    //echo "regular";
		    //It used to be home_page.php,
		    //However, account_menu seems to have all we need in home_page.php
		    header("Location: account_menu.php");
		}
		
	    } else {
		$error = "username and password don't match!";
	    }
	}
    }
    
    include('includes/header.php');
?>
	<div class="box">
	<br /><br />
	<h2 class="center_align">Sign in</h2>
	<?php if (isset($error)) {?>
	    <p class="error_info"><?php echo $error;?></p>
	<?php } ?>
     <div class="form">
	<form action="signin.php" method="post" autocomplete="on">
	     <label>Email &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
	     <input type="text" name="email" placeholder="xxx@xxx.edu" value="<?php echo $_POST['email'];?>"> 
<!--pattern="^[a-z0-9A-Z._%+-]+@[a-z0-9A-Z.-]+\.edu$" -->

	     <br><br>
	     <label>Password</label>
	     <input type="password" name="password" placeholder="Password" value= "<?php echo $_POST['password'];?>">
	     <br><br>
	     <input type="submit" value="Sign in" id="signInSubmitBtn">
	     &nbsp;&nbsp;
	     <a href="signup.php">Sign up</a>
	</form>
  </div>
</div>
<?php
    include('includes/footer.php');
?>
