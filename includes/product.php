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
    public function fetch_by_category($cate_id, $page_num) {
	global $pdo;
	$ITEM_PER_PAGE = 4;
	$offset = ($page_num - 1) * $ITEM_PER_PAGE;
	if($cate_id == 0) { // 0 means all category
		$query = $pdo->prepare("SELECT * FROM product limit ?,?");
		$query->bindValue(1, (int)$offset, PDO::PARAM_INT);
		$query->bindValue(2, (int)$ITEM_PER_PAGE, PDO::PARAM_INT);
	} else {
		$query = $pdo->prepare("SELECT * FROM product WHERE category_id=? LIMIT ?,?");
		$query->bindValue(1, $cate_id);
		$query->bindValue(2, (int)$offset, PDO::PARAM_INT);
		$query->bindValue(3, (int)$ITEM_PER_PAGE, PDO::PARAM_INT);
	}
	$query->execute();
//echo $cate_id."<br>".$offset."<br>".$ITEM_PER_PAGE."<br>end";
	$result = $query->fetchall();
//echo "<br>".count($result);
	return $result;
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
