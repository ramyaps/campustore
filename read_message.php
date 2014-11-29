<?php
session_start();
include_once('includes/connection.php');
include_once('includes/user.php');

$user = new User();
if(isset($_SESSION['logged_in'])) {
    $page_title = "Webshelf-Read Message";
    include('includes/header.php');
    $msg_id = $_GET['msg_id'];
    $user_id = $_SESSION['user_id'];

    $query = $pdo->prepare("SELECT * FROM message WHERE id = ?");
    $query->bindValue(1, $msg_id);
    $query->execute() or die(print_r($query->errorInfo()));

    $message = $query->fetch(PDO::FETCH_ASSOC);

    $sender = $user->fetch_user($message['sender_id']);        //fetch sender data
    $receiver = $user->fetch_user($message['receiver_id']);    //fetch receiver data
    $filter = "";
    $reply_to = "";

    if($user_id === $message['receiver_id']){
        $filter = 'inbox';
        $reply_to = $message['sender_id'];
        if($message['status'] === 'UNREAD'){
            $status = 'READ';
            $query = $pdo->prepare("UPDATE message SET status = ? WHERE id=?");
            $query->bindValue(1, $status);
            $query->bindValue(2, $msg_id);
            $query->execute() or die(print_r($query->errorInfo()));
        }
    }elseif($user_id == $message['sender_id']){
        $filter = 'sent';
        $reply_to = $message['receiver_id'];
    }

    print("<div class='center_column'>");
    print("<br>&nbsp;&nbsp;<a href='account_menu.php' id='account'>Your Account</a>&nbsp;&gt;&nbsp;");
    print("<a href='message_box.php?filter=$filter'>Message Box</a>&nbsp;&gt;&nbsp;");
    print("<span style='color: indianred'>Read Message</span><br><br>");
    print("<form method='post' action='message.php'>");
    print("<div><label class='input_label'>From:</label><span>".$sender['first_name']." ".$sender['last_name']."</span> </div><br>");
    print("<div><label class='input_label'>To:</label><span>".$receiver['first_name']." ".$receiver['last_name']."</span> </div><br>");
    print("<div><label class='input_label'>Title:</label><span>".$message['title']."</span></div><br>");
    print("<div><label class='input_label'>Message:</label></div><br> ");
    print("<div><textarea rows='8' cols='50' readonly>".$message['body']."</textarea></div><br>");
    print("<input type='hidden' name='msg_id' value='$msg_id'>");
    print("<input type='hidden' name='receiver_id' value='$reply_to'>");
    print("<input type='submit' name='msg_submit' value='Reply'>");
    print("<a href='message_box.php?filter=$filter'><input type='button' value='Back'></a>");
    print("</form>");
    print("</div>");
    include('includes/footer.php');
}
else {
    include("signin.php");
}
?>