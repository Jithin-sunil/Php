<?php
ob_start();
include('../Shop/Header.php');
include('../Assets/Connection/Connection.php');
$stock = "";

if(isset($_POST['btn_submit'])){
    $stock = $_POST['txt_qty'];
    $pid = $_GET['pid'];
    
  
            $insQry = "INSERT INTO tbl_stock (stock_qty,product_id) VALUES ('$stock','$pid')";
            if($conn->query($insQry)){
                echo "<script>alert('Stock added successfully');
                window.location='AddStock.php?pid=".$_GET['pid']."';
                </script>";
            }else{
                echo "<script>alert('Stock not added');</script>";
            }
        }
      

if(isset($_GET['sid'])){
    $sid = $_GET['sid'];
    $delQry = "DELETE FROM tbl_stock WHERE stock_id = $sid";
    if($conn->query($delQry)){
        echo "<script>alert('Stock deleted successfully');
        window.location='AddStock.php?pid=".$_GET['pid']."';
        </script>";
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock</title>
</head>

<style>
     .required-field::after {
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

<body> 
    <form action="" id="RegistrationForm" method="post" enctype="multipart/form-data">
        <h2>Add Stock</h2>
        <table class="table table-bordered table-hover">
            <tr>
                <td>Stock Quantity</td>
                <td>
                    <input type="text" 
                           name="txt_qty" 
                           class="form-control" 
                           placeholder="Enter Stock Quantity" 
                          
                    >
                </td>
            </tr>
           
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" name="btn_submit" class="btn btn-outline-primary" value="Add Stock">
                   
                </td>
            </tr>
        </table>
    </form>

    <h2>Stock List</h2>
    <table class="table table-bordered">
        <tr>
            <th>#</th>
            <th>Stock Quantity</th>
    
            <th>Action</th>
        </tr>
        <?php
        $selQry = "SELECT * FROM tbl_stock where product_id='".$_GET['pid']."'";
        $result = $conn->query($selQry);
        $i=0;
        while($row = $result->fetch_assoc()){
            $i++;
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $row['stock_qty']; ?></td>
            <td>
                <a href="AddStock.php?sid=<?php echo $row['stock_id']; ?>&pid=<?php echo $row['product_id']; ?>" class="btn btn-outline-danger">Delete</a>
            </td>
        </tr>
        <?php
        }
        ?>
    </table>
</body>

<script src="../Assets/Validation.js"></script>
    <script>
        // Initialize form validation
        validateForm('#RegistrationForm', 'submit');
        validateForm('#RegistrationForm', 'input');
    </script>
</html>
<?php
ob_end_flush();
include('../Shop/Footer.php');
?>
