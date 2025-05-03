<?php
ob_start();
include('../Admin/Header.php');
include('../Assets/Connection/Connection.php');
$brand = "";
$eid = 0;
if(isset($_POST['btn_submit'])){
    $brand = $_POST['txt_name'];
    $eid = $_POST['eid'];
    
    // Check if brand already exists
    $checkQry = "SELECT * FROM tbl_brand WHERE brand_name = '$brand'";
    if($eid > 0){
        $checkQry .= " AND brand_id != '$eid'";
    }
    $checkResult = $conn->query($checkQry);
    
    if($checkResult->num_rows > 0){
        echo "<script>alert('Brand already exists');</script>";
    } else {
        if($eid == 0){
            $insQry = "INSERT INTO tbl_brand (brand_name) VALUES ('$brand')";
            if($conn->query($insQry)){
                echo "<script>alert('Brand added successfully');
                window.location='Brand.php';
                </script>";
            }else{
                echo "<script>alert('Brand not added');</script>";
            }
        }
        else{
            $updQry = "UPDATE tbl_brand SET brand_name = '$brand' WHERE brand_id = '$eid'";
            if($conn->query($updQry)){
                echo "<script>alert('Brand updated successfully');
                window.location='Brand.php';
                </script>";
            }else{
                echo "<script>alert('Brand not updated');</script>";
            }
        }
    }
}

if(isset($_GET['bid'])){
    $bid = $_GET['bid'];
    $delQry = "DELETE FROM tbl_brand WHERE brand_id = $bid";
    if($conn->query($delQry)){
        echo "<script>alert('Brand deleted successfully');
        window.location='Brand.php';
        </script>";
    }
}

if(isset($_GET['eid'])){
    $eid = $_GET['eid'];
    $selQry = "SELECT * FROM tbl_brand WHERE brand_id = $eid";
    $result = $conn->query($selQry);
    $row = $result->fetch_assoc();
    $brand = $row['brand_name'];
    $eid = $row['brand_id'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brand</title>
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
    <form action="" id="RegistrationForm" method="post">
        <h2>Add Brand</h2>
        <table class="table table-bordered table-hover">
            <tr>
                <td>Brand Name</td>
                <td>
                    <input type="text" 
                           name="txt_name" 
                           class="form-control" 
                           placeholder="Enter Brand Name" 
                          
                           value="<?php echo $brand ?>">
                    <input type="hidden" name="eid" value="<?php echo $eid ?>">
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" 
                           name="btn_submit" 
                           class="btn btn-outline-primary" 
                           value="Add Brand">
                </td>
            </tr>
        </table>
    </form>

    <h2>Brand List</h2>
    <table class="table table-bordered">
        <tr>
            <th>#</th>
            <th>Brand Name</th>
            <th>Action</th>
        </tr>
        <?php
        $selQry = "SELECT * FROM tbl_brand";
        $result = $conn->query($selQry);
        $i=0;
        while($row = $result->fetch_assoc()){
            $i++;
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $row['brand_name']; ?></td>
            <td>
                <a href="Brand.php?bid=<?php echo $row['brand_id']; ?>" class="btn btn-outline-danger">Delete</a>
                <a href="Brand.php?eid=<?php echo $row['brand_id']; ?>" class="btn btn-outline-warning">Edit</a>
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
include('../Admin/Footer.php');
?>
