<?php

try {
    $pdo = new PDO('mysql:host=aa7v6mo2fs8t19.cv7unwp2ljkv.us-east-1.rds.amazonaws.com;dbname=campustore', 'updownlife', 'CIS525termproject***');
} catch(PDOException $e) {
    exit('Database error.');
}
?>
