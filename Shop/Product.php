<?php 
ob_start();
include '../Assets/Connection/Connection.php';
session_start();
if(isset($_POST['btn_submit'])){
    $name=$_POST['txt_name'];
    $price=$_POST['txt_price'];
    $details=$_POST['txt_details'];
    $category=$_POST['sel_category'];
    $brand=$_POST['sel_brand'];
    $photo=$_FILES['file_photo']['name'];
    $photo_tmp=$_FILES['file_photo']['tmp_name'];
    move_uploaded_file($photo_tmp,'../Assets/Files/Product/'.$photo);
    $shop_id=$_SESSION['sid'];
    
    $InsQry="INSERT INTO tbl_product (product_name,product_price,product_photo,product_details,category_id,brand_id,shop_id) VALUES ('$name','$price','$photo','$details','$category','$brand','$shop_id')";
   
    if($conn->query($InsQry)   ){
       ?>
       <script>
        alert('Product added successfully');
        window.location='Product.php';
       </script>
       <?php
    }else{
        ?>
        <script>
            alert('Product not added');
            window.location='Product.php';
        </script>
        <?php
    }
}

if(isset($_GET['pid'])){
    $pid=$_GET['pid'];
    $DelQry="DELETE FROM tbl_product WHERE product_id='$pid'";
    if($conn->query($DelQry)){
        header('location:Product.php');
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .form-title {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .-field::after {
            content: " *";
            color: red;
        }
        .preview-image {
            max-width: 150px;
            max-height: 150px;
            margin-top: 10px;
            display: none;
        }
        .preview-text {
            margin-top: 10px;
            display: none;
            font-size: 14px;
        }
        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
        .is-invalid {
            border-color: red !important;
        }
        .is-valid {
            border-color: green !important;
        }
    </style>
</head>
<body>
   
            

     
            <h2 class="form-title">Add New Product</h2>
            <form id="RegistrationForm" method="POST" enctype="multipart/form-data">
                <table class="table table-bordered">
                    <tr>
                        <td width="30%"><label for="product_name" class="form-label">Product Name</label></td>
                        <td><input type="text" class="form-control" id="txt_name" name="txt_name"></td>
                    </tr>
                    <tr>
                        <td><label for="price" class="form-label">Price</label></td>
                        <td><input type="text" class="form-control" id="txt_price" name="txt_price" step="0.01"></td>
                    </tr>
                    <tr>
                        <td><label for="photo" class="form-label">Product Photo</label></td>
                        <td>
                            <input type="file" class="form-control" id="file_photo" name="file_photo" accept="image/*">
                            <img id="photoPreview" class="preview-image" src="#" alt="Photo Preview">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="details" class="form-label">Product Details</label></td>
                        <td><textarea class="form-control" id="details" name="txt_details" rows="4"></textarea></td>
                    </tr>
                    <tr>
                        <td><label for="category" class="form-label">Category</label></td>
                        <td>
                            <select class="form-select" id="sel_category" name="sel_category">
                                <option value="">Select Category</option>
                                <?php
                                $selQry="SELECT * FROM tbl_category";
                                $result=$conn->query($selQry);
                                while($row=$result->fetch_assoc()){
                                    ?>
                                    <option value="<?php echo $row['category_id']; ?>"><?php echo $row['category_name']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="brand" class="form-label">Brand</label></td>
                        <td>
                            <select class="form-select" id="sel_brand" name="sel_brand">
                                <option value="">Select Brand</option>
                                <?php
                                $selQry="SELECT * FROM tbl_brand";
                                $result=$conn->query($selQry);
                                while($row=$result->fetch_assoc()){
                                    ?>
                                    <option value="<?php echo $row['brand_id']; ?>"><?php echo $row['brand_name']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-center">
                            <input type="submit" name="btn_submit" class="btn btn-primary" value="Add Product">
                        </td>
                    </tr>
                </table>
            </form>
    
            <h2 class="text-center mb-4">Product List</h2>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Photo</th>
                        <th>Details</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $selQry = "SELECT * FROM tbl_product p 
                              INNER JOIN tbl_category c ON p.category_id = c.category_id 
                              INNER JOIN tbl_brand b ON p.brand_id = b.brand_id 
                              WHERE p.shop_id = '".$_SESSION['sid']."'";
                    $result = $conn->query($selQry);
                    while($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $row['product_name']; ?></td>
                        <td><?php echo $row['product_price']; ?></td>
                        <td><?php echo $row['category_name']; ?></td>
                        <td><?php echo $row['brand_name']; ?></td>
                        <td><img src="../Assets/Files/Product/<?php echo $row['product_photo']; ?>" width="50" height="50"></td>
                        <td><?php echo $row['product_details']; ?></td>
                        <td>
                            <a href="DeleteProduct.php?eppid=<?php echo $row['product_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                            <a href="AddStock.php?pid=<?php echo $row['product_id']; ?>" class="btn btn-sm btn-primary">Add Stock</a>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Assets/Validation.js"></script>
    <script>
        // Initialize form validation
        validateForm('#RegistrationForm', 'submit');
        validateForm('#RegistrationForm', 'input');

      
    </script>
</body>
</html>