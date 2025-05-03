<?php
ob_start();
include('../User/Header.php');
include('../Assets/Connection/Connection.php');
session_start();


// Get product ID from URL
$product_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Get product details from database
$selQry = "SELECT * FROM tbl_product p 
          INNER JOIN tbl_category c ON p.category_id = c.category_id 
          INNER JOIN tbl_brand b ON p.brand_id = b.brand_id 
          WHERE p.product_id = '".$product_id."' ";
$result = $conn->query($selQry);
$product = $result->fetch_assoc();

if(!$product) {
    header("Location: ViewProduct.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <title><?php echo $product['product_name']; ?> - Product Details</title>

</head>


  
    <!-- ***** Product Area Starts ***** -->
    <section class="section" id="product">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="left-images">
                        <img src="../Assets/Files/Product/<?php echo $product['product_photo']; ?>" alt="<?php echo $product['product_name']; ?>">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="right-content">
                        <h4><?php echo $product['product_name']; ?></h4>
                        <span class="price">$<?php echo number_format($product['product_price'], 2); ?></span>
                        <ul class="stars">
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                        </ul>
                        <div class="product-details">
                            <p><strong>Category:</strong> <?php echo $product['category_name']; ?></p>
                            <p><strong>Brand:</strong> <?php echo $product['brand_name']; ?></p>
                            <p><strong>Product Details:</strong></p>
                            <p><?php echo $product['product_details']; ?></p>
                        </div>
                        
                        <div class="total">
                            <div class="main-border-button">
                                <a href="#">Add To Cart</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Product Area Ends ***** -->

  
    <script>
        $(document).ready(function() {
            // Quantity buttons
            $('.quantity button').on('click', function() {
                var button = $(this);
                var oldValue = button.parent().find('input').val();
                if (button.hasClass('plus')) {
                    var newVal = parseFloat(oldValue) + 1;
                } else {
                    if (oldValue > 1) {
                        var newVal = parseFloat(oldValue) - 1;
                    } else {
                        newVal = 1;
                    }
                }
                button.parent().find('input').val(newVal);
            });
        });
    </script>
</body>
</html> 
<?php
include('../User/Footer.php');
?>