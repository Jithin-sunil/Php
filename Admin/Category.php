<?php
ob_start();
include('../Admin/Header.php');
include('../Assets/Connection/Connection.php');
$category = "";
$eid = "";
$photo = "";

if(isset($_POST['btn_submit'])){
    $category = $_POST['txt_name'];
    $photo = $_FILES['file_photo']['name'];
    $photo_tmp = $_FILES['file_photo']['tmp_name'];
    $eid = $_POST['eid'];
    
    // Check if category already exists
    $checkQry = "SELECT * FROM tbl_category WHERE category_name = '$category'";
    if($eid > 0){
        $checkQry .= " AND category_id != '$eid'";
    }
    $checkResult = $conn->query($checkQry);
    
    if($checkResult->num_rows > 0){
        echo "<script>alert('Category already exists');</script>";
    } else {
        if($eid == ""){
            $insQry = "INSERT INTO tbl_category (category_name,category_photo) VALUES ('$category','$photo  ')";
            if($conn->query($insQry)){
                echo "<script>alert('Category added successfully');
                window.location='Category.php';
                </script>";
            }else{
                echo "<script>alert('Category not added');</script>";
            }
        }
        else{
            $updQry = "UPDATE tbl_category SET category_name = '$category' WHERE category_id = '$eid'";
            if($conn->query($updQry)){
                echo "<script>alert('Category updated successfully');
                window.location='Category.php';
                </script>";
            }else{
                echo "<script>alert('Category not updated');</script>";
            }
        }
    }
}

if(isset($_GET['bid'])){
    $bid = $_GET['bid'];
    $delQry = "DELETE FROM tbl_category WHERE category_id = $bid";
    if($conn->query($delQry)){
        echo "<script>alert('Category deleted successfully');
        window.location='Category.php';
        </script>";
    }
}

if(isset($_GET['eid'])){
    $eid = $_GET['eid'];
    $selQry = "SELECT * FROM tbl_category WHERE category_id = $eid";
    $result = $conn->query($selQry);
    $row = $result->fetch_assoc();
    $category = $row['category_name'];
    $photo = $row['category_photo'];
    $eid = $row['category_id'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category</title>
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
        <h2><?php echo ($eid == "") ? "Add Category" : "Edit Category"; ?></h2>
        <table class="table table-bordered table-hover">
            <tr>
                <td>Category Name</td>
                <td>
                    <input type="text" 
                           name="txt_name" 
                           class="form-control" 
                           placeholder="Enter Category Name" 
                          
                           value="<?php echo $category ?>">
                    <input type="hidden" name="eid" value="<?php echo $eid ?>">
                </td>
            </tr>
            <tr>
                <td>Photo</td>
                <td>
                    <input type="file" 
                           name="file_photo" 
                           class="form-control" 

                           accept="image/*"
                           title="Please upload an image file">
                  
                            <img id="photoPreview" class="preview-image" src="#" alt="Photo Preview">
                        </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" name="btn_submit" class="btn <?php echo ($eid == "") ? 'btn-outline-primary' : 'btn-outline-success'; ?>" value="<?php echo ($eid == "") ? 'Add Category' : 'Update Category'; ?>">
                    <?php if($eid != ""){ ?>
                        <a href="Category.php" class="btn btn-outline-secondary">Cancel</a>
                    <?php } ?>
                </td>
            </tr>
        </table>
    </form>

    <h2>Category List</h2>
    <table class="table table-bordered">
        <tr>
            <th>#</th>
            <th>Category Name</th>
            <th>Photo</th>
            <th>Action</th>
        </tr>
        <?php
        $selQry = "SELECT * FROM tbl_category";
        $result = $conn->query($selQry);
        $i=0;
        while($row = $result->fetch_assoc()){
            $i++;
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $row['category_name']; ?></td>
            <td><img src="../Assets/Files/Category/<?php echo $row['category_photo']; ?>" height="100" width="100"></td>
            <td>
                <a href="Category.php?bid=<?php echo $row['category_id']; ?>" class="btn btn-outline-danger">Delete</a>
                <a href="Category.php?eid=<?php echo $row['category_id']; ?>" class="btn btn-outline-warning">Edit</a>
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
