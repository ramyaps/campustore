<?php
session_start();
include_once("includes/connection.php");
include_once("includes/user.php");
$user = new User();
if (isset($_POST['email'], $_POST['nick_name'], $_POST['first_name'], $_POST['last_name'], $_POST['password'])) {
	$email = $_POST['email'];
	$password = md5($_POST['password']);
	$confirm_password = md5($_POST['confirm_password']);
	$nick_name = $_POST['nick_name'];
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];	
        $phone = $_POST['phone'];
        $address = $_POST['address'];

	include_once('verify_email.php');
	if (empty($email) or empty($password) or empty($confirm_password) or empty($nick_name) or empty($first_name) or empty($last_name) or empty($phone) or empty($address)) {
	    $error = "You must fill all the fields!";
	} else if ($password != $confirm_password){
	    $error = "password and confirm password don't match!";
	} else if(verifyEmail($email) == 'invalid') {
	    $error = "email address doesn't exist!";
	} else if($user->isEmailExist($email)) {
	    $error = "This email is already registered. Please sign in.";
	} 
	else {
	    $query = $pdo->prepare("INSERT INTO user (email, password,nick_name, first_name, last_name, phone, address,type, status, banned_util) values (?,?,?,?,?,?,?,?,?,?)");
	    $query->bindValue(1, $email);
	    $query->bindValue(2, $password);
	    $query->bindValue(3, $nick_name);
	    $query->bindValue(4, $first_name);
	    $query->bindValue(5, $last_name);
	    $query->bindValue(6, $phone);
	    $query->bindValue(7, $address);
	    $query->bindValue(8, 'regular');
	    $query->bindValue(9, 'normal');
	    $query->bindValue(10, '2050-07-24');
	    $query->execute();
	    
	    header("Location: signin.php");
	}
    }

     include('includes/header.php');
?>
	<br /><br />
	
	<div class="box_center">
	<h3 class="center_align">Sign up</h3>
	<?php if (isset($error)) {?>
	    <p class="error_info"><?php echo $error;?></p>
	<?php } ?>

	<div class="form">
	<form action="signup.php" method="post" autocomplete="off">
	     <label>Email</label>
	     &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
	     <input type="email" name="email" placeholder="xxx@xxx.edu" value="<?php echo $_POST['email'];?>" pattern="^[a-z0-9A-Z._%+-]+@[a-z0-9A-Z.-]+\.edu$">
	     <br><br>
	     <label>Password</label>
	     
	     <input type="password" name="password" placeholder="Password" value="<?php echo $_POST['password'];?>">
	     <br><br>
	     <label>Confirm</label>
	     &nbsp;&nbsp;&nbsp;
	     <input type="password" name="confirm_password" placeholder="Confirm Password" value="<?php echo $_POST['confirm_password'];?>">
	     <br><br>
	     <label>Nickname</label>
	    
	     <input type="text" name="nick_name" placeholder="Big Jim" pattern="^[A-Za-z ]+$" value="<?php echo $_POST['nick_name']; ?>">
	     <br><br>
	     <label>First name</label>
	     <input type="text" name="first_name" placeholder="First Name" value="<?php echo $_POST['first_name'];?>" pattern="^[A-Za-z]{1,20}$">
	     <br><br>
	     <label>Last name</label>
	   
	     <input type="text" name="last_name" placeholder="Last Name" value="<?php echo $_POST['last_name'];?>" pattern="^[A-Za-z]{1,20}$">
	     <br><br>     
	     <label>Phone</label>
	     &nbsp;&nbsp;&nbsp;&nbsp;
             <input type="text" name="phone" placeholder="Phone Number" value="<?php echo $_POST['phone']?>" pattern="^[0-9]{6,12}$">
	     <br><br>     
	     <label>Address</label>
	     &nbsp;
             <input type="text" name="address" placeholder="Address" value="<?php echo $_POST['address']?>">
	     <br><br>     
	     <input type="submit" value="Submit" id="signup_submit">
	</form>
	</div>
	</div>
<?php
    include('includes/footer.php');
?>
