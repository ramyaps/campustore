<?php
session_start();
include_once('includes/connection.php');
include_once('includes/user.php');
$user = new User();

if(isset($_SESSION['logged_in'])){
    $page_title = "Webshelf-Send Message";
    include_once('includes/header.php');

    date_default_timezone_set('America/Detroit');

    $sender_id = $_SESSION['user_id'];
    $receiver_id = isset($_POST['receiver_id']) ? $_POST['receiver_id'] : "";

    $sender = $user->fetch_user($sender_id);        //fetch sender data
    $receiver = $user->fetch_user($receiver_id);    //fetch receiver data

    $action = isset($_GET['action']) ? $_GET['action'] : "";
    $msg_id = isset($_POST['msg_id']) ? $_POST['msg_id'] : "";

    if(!empty($msg_id)){
        $query = $pdo->prepare("SELECT * FROM message WHERE id = ?");
        $query->bindValue(1, $msg_id);
        $query->execute() or die(print_r($query->errorInfo()));
        $message = $query->fetch(PDO::FETCH_ASSOC);
        $title = "Re: ".$message['title'];
    }

    if($action == 'send'){
        $title = $_POST['title'];
        $message = $_POST['message'];
        $text = isset($_POST['text']) ? $_POST['text'] : "";
        if(empty($text)){
            $body = $message;
        }else {
            $body = $message."\n-----------\n".$text;
        }
        $query = $pdo->prepare("INSERT INTO message (sender_id, receiver_id, date_time, title, body, status) VALUES (?,?,?,?,?,?)");
        $query->bindValue(1, $sender_id);
        $query->bindValue(2, $receiver_id);
        $query->bindValue(3, date("Y-m-d H:i:s"));
        $query->bindValue(4, $title);
        $query->bindValue(5, $body);
        $query->bindValue(6, 'UNREAD');
        $query->execute() or die(print_r($query->errorInfo()));

        $alert = "Message sent successfully!! ";
        print("<script type='text/javascript'>alert('".$alert."');</script>");
        header("Location: message_box.php?filter=sent&success=1");
    }
//    else {
    print("<br>&nbsp;&nbsp;<a href='account_menu.php' id='account'>Your Account</a>&nbsp;&gt;&nbsp;");
    print("<a href='message_box.php?filter=sent'>Message Box</a>&nbsp;&gt;&nbsp;");
    print("<span style='color: indianred'>Send Message</span><br><br>");
    print("<div class='box'>");
        print("<div class='center_column'>");
        print("<p><span style='color: indianred'>Send Message</span></p>");
        print("<form method='post' action=message.php?action=send>");
        print("<input type='hidden' name='receiver_id' value='".$receiver['id']."'>");
        print("<div><label class='input_label'>Send To:</label><span>".$receiver['first_name']." ".$receiver['last_name']."</span> </div><br>");
        print("<div><label class='input_label'>From:</label><span>".$sender['first_name']." ".$sender['last_name']."</span> </div><br>");
        print("<div><label class='input_label'>Title:</label><input type='text' name='title' value='".(isset($title)?$title:"")."' required> </div><br>");
        print("<div><label class='input_label'>Message:</label></div> ");
        print("<textarea rows='8' name='message' id='message' cols='50' required></textarea>");
        if(!empty($message)){
           print("<textarea rows='8' cols='50' name='text' readonly>".$message['body']."</textarea>");
        }
        print("<p><input type='submit' name='send_submit' value='Send Message'><input type='reset' name='clear' value='Clear' class='mybutton'>");
        print("<input type='button' value='Back' onclick='history.go(-1)'></p>");
        print("</form>");
        print("</div>");
    print("</div>");
//    }
    include_once("includes/footer.php");
}else{
    header("Location: signin.php");
}
?>
