<?php
session_start();
include_once('includes/connection.php');
include_once('includes/product.php');
include_once('includes/user.php');
$page_title = "Products from this seller.php";
include('includes/header.php');
$product = new Product();
$user = new user();
//$data = $product->fetch_all();

$seller_id = isset($_GET['seller']) ? $_GET['seller'] : ""; // getting seller id
//if seller id is not empty
if(!empty($seller_id)) {
    $seller_data = $user->fetch_user($seller_id);
    $page_num = isset($_GET['page_num']) ? $_GET['page_num'] : 1;
    if (isset($_GET['action']) and $_GET['action'] == 'search') {
        $keywords = $_GET['search_str'];
        $data = $product->search_seller($keywords, $page_num, $seller_id);

    } else {
        $data = $product->fetch_by_category_seller($category_id, $page_num, $seller_id);
    }

    ?>
    <div class="box_center">
        <h3>Products from seller: </h3> <?php echo $seller_data['first_name']. " ". $seller_data['last_name'] ?>
        <table class="show_table">
            <?php

            if (empty($data)) {
                echo "<br><br><br><p>Nothing found!</p><br><br><br>";
            }
            $MAX_COLUMN = 4;
            $column_count = 0;
            foreach ($data as $item) {
                $icon_path = "./uploads/icons/" . $item['icon'];
                if (!file_exists($icon_path) || is_dir($icon_path)) {
                    $icon_path = "./uploads/icons/" . "default.png";
                }

                if ($column_count % $MAX_COLUMN == 0) {
                    echo "<tr>";
                }
                ?>
                <td>
                    <div class="item">
                        <a href="product_detail.php?id=<?php echo $item['id']; ?>"><img class="item_icon"
                                                                                        src=<?php echo $icon_path ?> alt="item
                                                                                        picture"><br><?php echo $item['name']; ?>
                        </a>
                        <br>
                        <!--
			<small>posted in
		   	<?php
                        date_default_timezone_set('America/Detroit');
                        echo date('l jS', $article['create_date']);
                        ?>
			
			</small>
			-->
                    </div>
                </td>
                <?php
                $column_count++;
                if ($column_count % $MAX_COLUMN == 0) {
                    echo "</tr>";
                }
            }
            ?>
        </table>

        <div class="page_nav">
            <?php
            if ($page_num > 1) {
                echo "<a href='seller.php?cate_id=" . $category_id . "&page_num=" . ($page_num - 1) . "&seller=".$seller_id."'>prev&nbsp;&nbsp;</a>";
            }

            if (isset($_GET['action']) and $_GET['action'] == 'search') {
                $next_data = $product->search_seller($keywords, $page_num + 1, $seller_id);
            } else {
                $next_data = $product->fetch_by_category_seller($category_id, $page_num + 1, $seller_id);
            }
            if (count($next_data) > 0) {
                echo "<a href='seller.php?cate_id=" . $category_id . "&page_num=" . ($page_num + 1) . "&seller=".$seller_id . "'>next</a>";
            }

            ?>
        </div>

    </div>
    <?php
    include('includes/footer.php');
}else{   // if no seller id given go to index.php page
    include('includes/index.php');
}
?>

