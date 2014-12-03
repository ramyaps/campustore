<?php
session_start();
include_once('includes/connection.php');


if (isset($_SESSION['logged_in'])) {
    //Fetching the user id from the session variable
    $user_id = $_SESSION['user_id'];

    if (isset($_POST['name'], $_POST['category'], $_POST['description'], $_POST['price'], $_POST['quantity'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];

        //Check if any of the input fields is empty
        if (empty($name) or empty($description) or empty($category) or empty($price) or empty($quantity)) {
            $error = "All fields are required!";
        } else {

            //Check if the category is 'Books' i.e value of category id must be '1'
            if ($category == '1') {

                //If the category selected is 'Books', then author, edition and year are mandatory
                if (isset($_POST['author'], $_POST['edition'], $_POST['year'])) {
                    $author = $_POST['author'];
                    $edition = $_POST['edition'];
                    $year = $_POST['year'];

                    //Check if any of fields is empty, if so raise error.
                    if (empty($author) or empty($edition) or empty($year)) {
                        $error = "All fields are required!";
                    } else {

                        //else concatenate the author, edition and year to the description field
                        $description = "Author: " . $author . " Edition: " . $edition . " Year: " . $year . " " . $description;
                    }
                }
            }
        }

        /*****Code logic for uploading image file of the product****/
        if (empty($_FILES['picture']['name'])) {
            $image_attr = "0"; //no file chosen
        } else {
            //if user has chosen an image file for upload, check the file size
            if ($_FILES['picture']['size'] > 256000) {
                $error =  "Sorry, your file is too large.";
            }else {
		        $uploaddir = './uploads/icons/';
                $target_path = $uploaddir . basename( $_FILES['picture']['tmp_name']);
                if(move_uploaded_file($_FILES['picture']['tmp_name'], $target_path)) {
                    $image_attr = "1";
                } else{
                    $error = "There was an error uploading the file, please try again!";
                }
            }

        }
        /***** End of code logic for file upload ******/

        //If no errors encountered, then add the product to the database
        if (empty($error)) {
            //Prepare the SQL INSERT query to add the product to the database
            date_default_timezone_set('America/Detroit');
            $query = $pdo->prepare("INSERT INTO product (name, description, create_date, price, order_status, quantity, user_id, category_id) VALUES (?,?,?,?,?,?,?,?)");
            $query->bindValue(1, $name);
            $query->bindValue(2, $description);
            $query->bindValue(3, date("Y-m-d H:i:s")); //date format of MySQL 'datetime' type
            $query->bindValue(4, $price);
            $query->bindValue(5, "Available");
            $query->bindValue(6, $quantity);
            $query->bindValue(7, $user_id);
            $query->bindValue(8, $category);

            // Execute the INSERT query
            $query->execute() or die(print_r($query->errorInfo(), true));

            // Fetch the id of the new product inserted to the database
            $product_id = $pdo->lastInsertId();
	        rename($target_path, $uploaddir . $product_id);
	    
	    //For multiple pictures
            //If image file chosen by user, update the picture table with the filepth
	    
            if ($image_attr === "1") {
                $query = $pdo->prepare("UPDATE product SET icon = ? WHERE id=?");
                $query->bindValue(1, $product_id);
                $query->bindValue(2, $product_id);
                $query->execute() or die(print_r($query->errorInfo(), true));
            }

            header("Location: view_products.php");
        }
    }
}

$page_title = 'WebShelf-Upload Product to catalog';
include_once('includes/header.php');
?>
<br xmlns="http://www.w3.org/1999/html">&nbsp;&nbsp;<a href="account_menu.php" id="account">Your Account</a>&nbsp;&gt;&nbsp;<span style='color: indianred'>Sell a product</span>
        <div class="box">
            <h3 class="center_align"> Upload Product for sale </h3>
            <em class="comments">*NOTE: No leading and trailing white-spaces allowed</em><br><br>
            <?php if (isset($error)) { ?>
                <small style="color: #aa0000;"><?php echo $error;?></small>
                <br /><br />
            <?php } ?>
            <form method="post" action="sell.php" name="upload_item" enctype="multipart/form-data" >
                <label class="input_label">Category</label>&nbsp;&nbsp;<select name="category" id="category" onchange="changeDisplay()">
                    <?php
                    $query = $pdo->prepare("SELECT id, name FROM category");
                    $query->execute() or die(print_r($query->errorInfo(), true));
                    $result = $query->fetchAll(PDO::FETCH_ASSOC);
                    foreach($result as $row){
                        echo "<option value='".$row['id']."' >".$row['name']."</option>";
                    }
                    ?>
                </select>
                <br><br>

                <label class="input_label">Name</label>&nbsp;&nbsp;<input type="text" name="name" value="<?php isset($name) ? $name : ""; ?>" required/><br><br>
                <div id='book_details'>
                    <label class="input_label">Author</label>&nbsp;&nbsp;<input type='text' name='author'  pattern='^[A-Za-z][A-Za-z\s]*[A-Za-z]$'><br><br>
                    <label class="input_label">Edition</label>&nbsp;&nbsp;<input type='text' name='edition'  pattern="^\w[\w\s]*[\w]$"/><br><br>
                    <label class="input_label">Year</label>&nbsp;&nbsp;<input type='text' name='year' pattern='[1-9][0-9]{3}'/>
                    <em class='comments'>*YYYY</em>
                    <br><br>
                </div>

                <label class="input_label">Quantity</label>&nbsp;&nbsp;
                <input type="text" name="quantity" pattern="[1-9]\d*"  value="1" required/>
                <br><br>

                <label class="input_label">Price</label>&nbsp;&nbsp;
                <input type="text" name="price" pattern="[1-9]\d*[.]\d{2}" required/>
                <em class="comments">*##.## (Numbers only) </em>
                <br><br>

                <label class="input_label">Description</label><br>
                    <textarea name="description" rows="6" cols="50" required placeholder="Enter Description here.."></textarea>
                <br><br>

                <label class="input_label">Picture</label>&nbsp;&nbsp;
                    <input type="file" name="picture"/><br>
                    <em class="comments">*File size must be less than 256kb</em>
                <br><br>

                <input type="submit" name="submit" value="Upload"/>
                <input type="reset" name="reset" value="Cancel"/>
                <br>

            </form>
        </div>
    
    <!--Script to show/hide div id "Book_details" based on dropdown menu selected -->
    <script>
        function changeDisplay(){
            var category = document.getElementById("category");
            var book_details = document.getElementById("book_details");
            if (category.value === '1'){
                book_details.style.display="inline";
            }
            else{
                book_details.style.display="none";
            }
        }
    </script>

                                                              
<?php
include('includes/footer.php');
//} else{
//    header('Location: index.php');
//}
//
//?><!--                    -->
