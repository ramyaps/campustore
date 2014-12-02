<?php
session_start();
include_once('includes/connection.php');
include_once('includes/user.php');
$user = new User();

if(isset($_SESSION['logged_in'])) {
    $page_title = "Change Password";
    include('includes/header.php');
    $user_id = $_SESSION['user_id'];
    $profile = $user->fetch_user($user_id);
    $heading = "";
    $cpwd = "";
    $npwd = "" ;
    $confpwd = "";
    $action = isset($_GET['action']) ? $_GET['action'] : "";
    if($action == 'change'){
        $cpwd = isset($_POST['cpwd']) ? $_POST['cpwd'] : "" ;
        $npwd = isset($_POST['npwd']) ? $_POST['npwd'] : "" ;
        $confpwd = isset($_POST['confpwd']) ? $_POST['confpwd'] : "" ;

        $hcpwd = md5($cpwd);
        $hnpwd = md5($npwd);
        $hconfpwd = md5($confpwd);

        if($hcpwd != $profile['password']){
            $error = "Incorrect current password";
        }elseif($hnpwd != $hconfpwd){
            $error = "New password and confirm password do not match";
        }else {
            $query = $pdo->prepare("UPDATE user SET password = ? WHERE id = ?");
            $query->bindValue(1, $hnpwd);
            $query->bindValue(2, $user_id);
            $query->execute() or die(print_r($query->errorInfo()));

            $heading = "Password changed successfully";
            $cpwd = $npwd = $confpwd = "";
        }
    }
?>
    <br>&nbsp;&nbsp;<a href="account_menu.php" id="account">Your Account</a>&nbsp;&gt;&nbsp;<span style='color: indianred'>Change Password</span>
    <div class='box'>
<!--        <div class='center_column'>-->
            <h3><?php echo $heading ?></h3>
            <?php if (isset($error)) {?>
                <p class="error_info"><?php echo $error;?></p>
            <?php } ?>
        <form method="post" action="change_password.php?action=change">
            <p><label class="input_label">Current Password</label><input type="password" name="cpwd" value="<?php echo $cpwd?>" required></p>
            <p><label class="input_label">New Password</label><input type="password" name="npwd" value="<?php echo $npwd?>" required></p>
            <p><label class="input_label">Confirm Password</label><input type="password" name="confpwd" value="<?php echo $confpwd ?>" required></p>
            <input type="submit" value="Change Password">&nbsp;<input type="reset" value="Cancel">&nbsp;
            <a href="account_menu.php"><input type="button" value="Back"></a>
        </form>
<!--        </div>-->

<?php
    include('includes/footer.php');
}else {
    include('signin.php');
}
?>