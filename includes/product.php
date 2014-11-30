<?php

class Product {
    public function fetch_all() {
	global $pdo;
	
	$query = $pdo->prepare("SELECT * FROM product");
	$query->execute();
	
	return $query->fetchall();
    }
    
    public function fetch_data($product_id) {
	global $pdo;
	$query = $pdo->prepare("SELECT * FROM product where id = ?");
	$query->bindValue(1, $product_id);
	$query->execute();

	return $query->fetch();
    }
    public function fetch_by_category($cate_id) {
	global $pdo;
	$query = $pdo->prepare("SELECT * FROM product where category_id = ?");
	$query->bindValue(1, $cate_id);
	$query->execute();
	return $query->fetchall();
    }
    public function fetch_image($product_id){
        global $pdo;
        $query = $pdo->prepare("SELECT * FROM picture WHERE product_id = ?");
        $query->bindValue(1, $product_id);
        $query->execute();

        return $query->fetch();
    }
    
    public function delete_data($product_id) {
	global $pdo;
	$delete = $pdo->prepare("DELETE FROM product WHERE id = :id");
	$delete->bindParam(':id', $product_id, PDO::PARAM_INT);
	$delete->execute();

	return 0;
    }

}
?>
