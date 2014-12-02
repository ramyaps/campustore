<?php
session_start();
include_once('includes/connection.php');
include_once('includes/user.php');
$user = new User();

if(isset($_SESSION['logged_in'])) {
    $page_title = "Update Personal Info";
    include('includes/header.php');
    $id = $_SESSION['user_id'];     //fetch user id
    $heading = "";
    $action = isset($_GET['action']) ? $_GET['action'] : "";
    if ($action == 'updateinfo'){
        $nname = (!empty($_POST['nname']) ? $_POST['nname'] : null);
        $firstName = (!empty($_POST['firstName']) ? $_POST['firstName'] : null);
        $lastName = (!empty($_POST['lastName']) ? $_POST['lastName'] : null);
        $id = $_POST['user_id'];
        $phone = (!empty($_POST['phone']) ? $_POST['phone'] : null);
        $address = (!empty($_POST['address']) ? $_POST['address'] : null);

        $query = $pdo->prepare("UPDATE user SET nick_name = :nname, phone = :phone, first_name = :first_name, last_name = :last_name, address = :address WHERE id = :id");
        $query->bindParam(':nname', $nname, PDO::PARAM_STR);
        $query->bindParam(':phone', $phone, PDO::PARAM_STR);
        $query->bindParam(':first_name', $firstName, PDO::PARAM_STR);
        $query->bindParam(':last_name', $lastName, PDO::PARAM_STR);
        $query->bindParam(':address', $address, PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute() or die(print_r($query->errorInfo()));
        $heading = "Updated Successfully";
    }

//    $sql = "SELECT * FROM user where id = ?";
//    $r = $pdo->query($sql);
    $row = $user->fetch_user($id);
?>
    <br>&nbsp;&nbsp;<a href="account_menu.php" id="account">Your Account</a>&nbsp;&gt;&nbsp;<span style='color: indianred'>Update Personal Info</span>
    <div class='box'>
<!--        <div class='center_column'>-->
            <h3><?php echo $heading ?></h3>
            <em class="comments">Fields marked with * are mandatory</em>
            <form  method="POST" action="update_personal_info.php?action=updateinfo">
                <input type="hidden" name='user_id' value='<?php echo $id ?>'>
                <table>
                    <tbody>
                        <p>
                            <label class='input_label'>User:</label>
                            <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" readonly>
                        </p>
                        <p><label class='input_label'>Nick Name:</label>
                            <input type="text" id="nname" name="nname" value="<?php echo htmlspecialchars($row['nick_name']); ?>" pattern="^[A-Za-z ]+$" required>
                            <em class="comments">*</em>
                        </p>

                        <p><label class='input_label'>First name: </label>
                            <input type="text" id="firstName" name="firstName"
                                   value="<?php echo htmlspecialchars($row['first_name']); ?>" pattern="^[A-Za-z]{1,20}$" required>
                            <em class="comments">*Alpha only;Limit to 20char</em>
                        </p>
                        <p><label class='input_label'>Last Name:</label>
                            <input type="text" id="lastName" name="lastName"
                                   value="<?php echo htmlspecialchars($row['last_name']); ?>" pattern="^[A-Za-z]{1,20}$" required>
                            <em class="comments">*Alpha only;Limit to 20char</em>
                        </p>
                        <p><label class='input_label'>Phone:</label>
                            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>" pattern="^[0-9]{6,12}$" required>
                            <em class="comments">*Numbers only</em>
                        </p>
                        <p><label class='input_label'>Address:</label>
                            <input type="text" id="address" name="address"
                                                     value="<?php echo htmlspecialchars($row['address']); ?>" required>
                            <em class="comments">*</em>
                        </p>

                        <input type="submit" name="Update Info" value="Update Info" > <!--onclick="setbutton();"-->
                        &nbsp;<a href="account_menu.php"><input type="button" name="back" value="Back"></a>
                    </tbody>
                </table>
            </form>

<!--        </div>-->

    <!-- Script to validate data -->
    <script  type="text/javascript">
        function setbutton(){

            var firstNamejs = document.forms[0].firstName;
            var lastNamejs = document.forms[0].lastName;
            var emailjs = document.forms[0].email;
            var phonejs = document.forms[0].phone;
            var phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
            var emailText = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            var letters = /^[A-Za-z]+$/;

            if(firstNamejs.value.match(letters)&&
                lastNamejs.value.match(letters)
                && (emailText.test(emailjs.value))
                && phonejs.value.match(phoneno))
            {
                document.forms[0].submit();
            }
            else
            {
                alert('Please correct inputs!');
                return false;
            }
        }

    </script>
<?php
    include('includes/footer.php');
} else {
    include('signin.php');
}
?>
