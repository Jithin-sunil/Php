<?php
ob_start();
include('../Admin/Header.php');
include('../Assets/Connection/Connection.php');

$place = "";
$district = "";
$eid = 0;

if(isset($_POST['btn_submit'])){
    $place = $_POST['txt_place'];
    $district = $_POST['sel_district'];
    $eid = $_POST['eid'];
    
  
        if($eid == 0){
            $insQry = "INSERT INTO tbl_place (place_name, district_id) VALUES ('$place', '$district')";
            if($conn->query($insQry)){
                echo "<script>alert('Place added successfully');
                window.location='Place.php';
                </script>";
            }else{
                echo "<script>alert('Place not added');</script>";
            }
        }
        else{
            $updQry = "UPDATE tbl_place SET place_name = '$place', district_id = '$district' WHERE place_id = '$eid'";
            if($conn->query($updQry)){
                echo "<script>alert('Place updated successfully');
                window.location='Place.php';
                </script>";
            }else{
                echo "<script>alert('Place not updated');</script>";
            }
        }
    }


if(isset($_GET['did'])){
    $did = $_GET['did'];
    $delQry = "DELETE FROM tbl_place WHERE place_id = $did";
    if($conn->query($delQry)){
        echo "<script>alert('Place deleted successfully');
        window.location='Place.php';
        </script>";
    }else{
        echo "<script>alert('Place not deleted');</script>";
    }
}

if(isset($_GET['eid'])){
    $eid = $_GET['eid'];
    $selQry = "SELECT * FROM tbl_place WHERE place_id = '".$eid."'";
    $result = $conn->query($selQry);
    $row = $result->fetch_assoc();
    $place = $row['place_name'];
    $district = $row['district_id'];
    $eid = $row['place_id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place</title>
</head>
<body>
    <form action="" method="post">
        <h2>Add Place</h2>
        <table class="table table-bordered table-hover">
        <tr>
                <td>District</td>
                <td>
                    <select name="sel_district" class="form-control" required>
                        <option value="">Select District</option>
                        <?php
                        $selQry = "SELECT * FROM tbl_district";
                        $result = $conn->query($selQry);
                        while($row = $result->fetch_assoc()){
                            $selected = ($row['district_id'] == $district) ? 'selected' : '';
                            echo "<option value='".$row['district_id']."' ".$selected.">".$row['district_name']."</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Place Name</td>
                <td><input type="text" name="txt_place" class="form-control" placeholder="Enter Place Name" required value="<?php echo $place; ?>"></td>
            </tr>
            
            <tr>
                <td colspan="2" align="center">
                    <input type="hidden" name="eid" value="<?php echo $eid; ?>">
                    <input type="submit" name="btn_submit" class="btn btn-outline-primary" value="Add Place">
                </td>
            </tr>
        </table>
    </form>

    <h2>Place List</h2>
    <table class="table table-bordered">
        <tr>
            <th>#</th>
            <th>Place Name</th>
            <th>District</th>
            <th>Action</th>
        </tr>
        <?php
        $selQry = "SELECT p.*, d.district_name FROM tbl_place p INNER JOIN tbl_district d ON p.district_id = d.district_id";
        $result = $conn->query($selQry);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $i++;
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $row['place_name']; ?></td>
            <td><?php echo $row['district_name']; ?></td>
            <td>
                <a href="Place.php?did=<?php echo $row['place_id']; ?>" class="btn btn-outline-danger">Delete</a>
                <a href="Place.php?eid=<?php echo $row['place_id']; ?>" class="btn btn-outline-warning">Edit</a>
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
