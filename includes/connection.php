<?php

try {
    $pdo = new PDO('mysql:host=54.196.151.99;dbname=campustore', 'updownlife', 'CIS525termproject***');
} catch(PDOException $err) {
    var_dump($err->getMessage());
    var_dump($dbh->errorInfo());
    die('....');
}

?>
