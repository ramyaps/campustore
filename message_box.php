<?php
session_start();
include_once('includes/connection.php');
include_once('includes/user.php');

$user = new User();

if(isset($_SESSION['logged_in'])) {
    $page_title = "Webshelf-Message Box";
    include('includes/header.php');
    $filter = isset($_GET['filter']) ? $_GET['filter'] :'';
    if(isset($_GET['success']) && $_GET['success'] == '1'){
            $success = "Message sent successfully !!";
        }
    else {
        $success = "";
    }
    $user_id = $_SESSION['user_id'];

    if(empty($filter) || ($filter == 'inbox')){
        $query = $pdo->prepare("SELECT u.first_name, u.last_name, m.id, m.date_time, m.title, m.status FROM message AS m INNER JOIN user AS u ON m.sender_id = u.id WHERE receiver_id = ? ORDER BY date_time DESC;");
        $query->bindValue(1, $user_id);
        $query->execute() or die(print_r($query->errorInfo()));

        $list = $query->fetchAll(PDO::FETCH_ASSOC);
    }
    elseif($filter == 'sent'){
        $query = $pdo->prepare("SELECT u.first_name, u.last_name, m.id, m.date_time, m.title, m.status FROM message AS m INNER JOIN user AS u ON m.receiver_id = u.id WHERE sender_id = ? ORDER BY date_time DESC;");
        $query->bindValue(1, $user_id);
        $query->execute() or die(print_r($query->errorInfo()));

        $list = $query->fetchAll(PDO::FETCH_ASSOC);
    }
    print("<br>&nbsp;&nbsp;<a href='account_menu.php' id='account'>Your Account</a>&nbsp;&gt;&nbsp;<span style='color: indianred'>Message box</span>");
    if(!empty($success)){
        print("<br><h3 class='center_align'>$success</h3>");
    }
    print("<div class='box'>");
//        print("<div class='center_column'>");
            print("<p ><a href='message_box.php?filter=inbox'". (($filter=='' || $filter=='inbox') ? "style='color: indianred'>" : ">")."Inbox</a>&nbsp;&nbsp;&nbsp;&nbsp;");
            print("<a href='message_box.php?filter=sent'". ($filter=='sent' ? "style='color: indianred'>" : ">")."Sent</a></p>");
            print("<br><table><tr><th>".($filter=='sent' ? "To" : "From")."</th><th>Title</th><th>Date</th></tr>");
            foreach($list AS $row){
                print("<tr>");
                print("<td".(($row['status']=='UNREAD' && $filter!='sent') ? " style='font-weight: bold'>" : ">").$row['first_name']." ".$row['last_name']."</td>");
                print("<td".(($row['status']=='UNREAD' && $filter!='sent') ? " style='font-weight: bold'>" : ">")."<a href='read_message.php?msg_id=".$row['id']."'>".$row['title']."</a></td>");
                print("<td>".$row['date_time']."</td>");
                print("</tr>");
            }
            print("</table>");
//        print("</div>");
    print("</div>");
    include('includes/footer.php');
}
else {
    include("signin.php");
}
?>
