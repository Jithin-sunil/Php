<?php
ob_start();
include('../Admin/Header.php');
include('../Assets/Connection/Connection.php');
$category = "";
$eid = "";
$photo = "";

if(isset($_POST['btn_submit'])){
    $category = $_POST['txt_category'];
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
        if($eid == 0){
            $insQry = "INSERT INTO tbl_category (category_name) VALUES ('$category')";
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

<body>
    <form action="" method="post" enctype="multipart/form-data">
        <h2><?php echo ($eid == "") ? "Add Category" : "Edit Category"; ?></h2>
        <table class="table table-bordered table-hover">
            <tr>
                <td>Category Name</td>
                <td>
                    <input type="text" 
                           name="txt_category" 
                           class="form-control" 
                           placeholder="Enter Category Name" 
                           required 
                           pattern="[A-Za-z\s]+" 
                           title="Category name should only contain letters"
                           minlength="2"
                           maxlength="50"
                           value="<?php echo $category ?>">
                    <input type="hidden" name="eid" value="<?php echo $eid ?>">
                </td>
            </tr>
            <tr>
                <td>Photo</td>
                <td>
                    <input type="file" 
                           name="txt_photo" 
                           class="form-control" 
                           <?php if($eid==""){echo "required";} ?>
                           accept="image/*"
                           title="Please upload an image file">
                    <?php if($eid!="" && $photo!=""){ ?>
                        <div class="mt-2">
                            <p>Current Photo:</p>
                            <img src="../Assets/Files/Category/<?php echo $photo; ?>" height="100" width="100" class="img-thumbnail">
                        </div>
                    <?php } ?>
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

</html>
<?php
ob_end_flush();
include('../Admin/Footer.php');
?>
