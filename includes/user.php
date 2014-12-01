<?php
//User class with methods for user table - select or modify operations
class User {
    public function fetch_user($user_id){
        global $pdo;

        $query = $pdo->prepare("SELECT * FROM user WHERE id = ?");
        $query->bindValue(1, $user_id);
        $query->execute();

        return $query->fetch();
    }
    public function isEmailExist($email) {
	global $pdo;
	$query = $pdo->prepare('SELECT * FROM user WHERE email = ?');
	$query->bindValue(1, $email);
	$query->execute();
	$result = $query->fetchall();
	if(count($result)>=1)
		return true;
	else
		return false;
    }

    public function fetch_feedback($user_id){
        global $pdo;

        $query = $pdo->prepare("SELECT AVG(stars) AS feedback FROM review WHERE user_id = ? GROUP BY user_id;");
        $query->bindValue(1, $user_id );
        $query->execute() or die(print_r($query->errorInfo()));
        $review = $query->fetch(PDO::FETCH_ASSOC);
        $feedback = round($review['feedback'], 1);

        return $feedback;
    }
}
?>
