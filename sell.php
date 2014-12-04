<?php
session_start();
include_once('includes/connection.php');


if (isset($_SESSION['logged_in'])) {
    //Fetching the user id from the session variable
    $user_id = $_SESSION['user_id'];
    $query = $pdo->prepare("SELECT id, name FROM category");
    $query->execute() or die(print_r($query->errorInfo(), true));
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    $name = $descr = $description = $category = $price = $quantity = $edition = $year = $author = $file = '';
    if (isset($_POST['name'], $_POST['category'], $_POST['description'], $_POST['price'], $_POST['quantity'])) {
        $name = $_POST['name'];
        $descr = $description = $_POST['description'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];

        //Check if any of the input fields is empty
        if (empty($name) or empty($description) or empty($category) or empty($price) or empty($quantity)) {
            $error = "***ALL FIELDS ARE REQUIRED!!";
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
                        $error = "***ALL FIELDS ARE REQUIRED!!";
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
                $error =  "***SORRY, YOUR FILE IS TOO LARGE.";
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
    $page_title = 'WebShelf-Upload Product to catalog';
    include_once('includes/header.php');

    print("<br xmlns='http://www.w3.org/1999/html'>");
    print("&nbsp;&nbsp;<a href='account_menu.php' id='account'>Your Account</a>&nbsp;&gt;&nbsp;<span style='color: indianred'>Sell a product</span>");
    print("<div class='box'>");
        print("<h3 class='center_align'> Upload Product for sale </h3>");
        print("<em class='comments'>NOTE: No leading and trailing white-spaces allowed</em><br><br>");
        if (isset($error)) {
            print("<small class='bold' style='color: firebrick;'>$error</small>");
            print("<br><br>");
        }
        print("<form method='post' action='sell.php' name='upload_item' enctype='multipart/form-data' >");
            print("<label class='input_label'>Category</label><select name='category' id='category' onchange='changeDisplay()'>");
            foreach($result as $row){
                print("<option value='".$row['id']."'".($row['id'] == $category ? " selected>" : ">").$row['name']."</option>");
            }
            print("</select><br><br>");
            print("<label class='input_label'>Name</label><input type='text' maxlength='45' name='name' value='$name' required><br><br>");
            if($category == '' || $category == 1){
            print("<div id='book_details'>");
                print("<label class='input_label'>Author</label><input type='text' name='author' value='$author' pattern='^[A-Za-z][A-Za-z\s,&\.\-]*[A-Za-z]$'>");
                print("<em class='comments'>*Alphabets separated by {, - & . space}</em><br><br>");
                print("<label class='input_label'>Edition</label><input type='text' name='edition' value='$edition' pattern='^\w[\w\s]*[\w]$'>");
                print("<em class='comments'>*No Special chars</em><br><br>");
                print("<label class='input_label'>Year</label><input type='text' name='year' value='$year' pattern='^[1-9][0-9]{3}$'>");
                print("<em class='comments'>*YYYY</em><br><br>");
            print("</div>");
            }
            print("<label class='input_label'>Quantity</label><input type='text' name='quantity' pattern='^[1-9]\d*$'  value='$quantity' required>");
            print("<em class='comments'>*Numbers only</em><br><br>");
            print("<label class='input_label'>Unit Price</label><input type='text' name='price' pattern='^[1-9]\d*[.]\d{2}$' value='$price' required>");
            print("<em class='comments'>*##.## (Numbers only) </em><br><br>");
            print("<label class='input_label'>Description</label><br>");
            print("<textarea name='description' rows='6' cols='50' required pattern='^\w[\w\s]*[\w]$'>".$descr."</textarea><br><br>");
            print("<label class='input_label'>Picture</label><input type='file' name='picture'><br>");
            print("<em class='comments'>*File size must be less than 256kb</em><br><br>");
            print("<input type='submit' name='submit' value='Upload'>&nbsp;<a href='account_menu.php'><input type='button' name='reset' value='Cancel'></a><br>");

        print("</form>");
    print("</div>");
?>
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
} else{
    header('Location: signin.php');
}

?>
