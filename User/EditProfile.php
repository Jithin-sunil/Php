<?php
ob_start();
include("../User/Header.php");
session_start();
include('../Assets/Connection/Connection.php');

if(isset($_POST['btn_update'])) {
    $name = $_POST['txt_name'];
    $contact = $_POST['txt_contact'];
    $dob = $_POST['txt_dob'];
    $address = $_POST['txt_address'];
    $district = $_POST['sel_district'];
    $place = $_POST['sel_place'];
    $gender = $_POST['txt_gender'];
    
    // Handle photo upload
    if(isset($_FILES['file_photo']) && $_FILES['file_photo']['name'] != "") {
        $photo = $_FILES['file_photo']['name'];
        $photo_temp = $_FILES['file_photo']['tmp_name'];
        move_uploaded_file($photo_temp, "../Assets/Files/User/".$photo);
        
        $upQry = "UPDATE tbl_user SET 
                  user_name='$name', 
                  user_contact='$contact', 
                  user_dob='$dob', 
                  user_address='$address', 
                  place_id='$place', 
                  user_gender='$gender',
                  user_photo='$photo' 
                  WHERE user_id='".$_SESSION['uid']."'";
    } else {
        $upQry = "UPDATE tbl_user SET 
                  user_name='$name', 
                  user_contact='$contact', 
                  user_dob='$dob', 
                  user_address='$address', 
                  place_id='$place', 
                  user_gender='$gender' 
                  WHERE user_id='".$_SESSION['uid']."'";
    }
    
    if($conn->query($upQry)) {
        echo "<script>alert('Profile Updated Successfully')</script>";
        echo "<script>window.location='MyProfile.php'</script>";
    } else {
        echo "<script>alert('Failed to Update Profile')</script>";
    }
}

// Get current user data
$selQry = "SELECT * FROM tbl_user u 
           INNER JOIN tbl_place p ON u.place_id = p.place_id 
           INNER JOIN tbl_district d ON p.district_id = d.district_id 
           WHERE user_id = '".$_SESSION['uid']."'";
$result = $conn->query($selQry);
$row = $result->fetch_assoc();

// Get districts for dropdown
$districtQry = "SELECT * FROM tbl_district";
$districtResult = $conn->query($districtQry);

// Get places for dropdown
$placeQry = "SELECT * FROM tbl_place WHERE district_id = '".$row['district_id']."'";
$placeResult = $conn->query($placeQry);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   
</head>
<body>
  
            <h2 class="text-center mb-4">Edit Profile</h2>
            
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="form-label">Current Profile Photo</label>
                    <div class="text-center mb-3">
                        <img src="../Assets/Files/User/<?php echo $row['user_photo']; ?>" class="img-thumbnail" width="150" height="150" alt="Current Profile Photo">
                    </div>
                    <label class="form-label">Change Photo</label>
                    <input type="file" name="file_photo" class="form-control">
                    <small class="text-muted">Current photo: <?php echo $row['user_photo']; ?></small>
                </div>

                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text" name="txt_name" class="form-control" value="<?php echo $row['user_name']; ?>" required pattern="[A-Za-z\s]{3,}" title="Name should contain only letters and spaces, minimum 3 characters">
                </div>

                <div class="form-group">
                    <label class="form-label">Contact</label>
                    <input type="tel" name="txt_contact" class="form-control" value="<?php echo $row['user_contact']; ?>" required pattern="[0-9]{10}" title="Contact number should be 10 digits">
                </div>

                <div class="form-group">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="txt_dob" class="form-control" value="<?php echo $row['user_dob']; ?>" required max="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea name="txt_address" class="form-control" required minlength="10" maxlength="200"><?php echo $row['user_address']; ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">District</label>
                    <select name="sel_district" id="sel_district" class="form-control" onchange="getPlace(this.value)" required>
                        <option value="">Select District</option>
                        <?php
                        while($district = $districtResult->fetch_assoc()) {
                            $selected = ($district['district_id'] == $row['district_id']) ? 'selected' : '';
                            echo "<option value='".$district['district_id']."' ".$selected.">".$district['district_name']."</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Place</label>
                    <select name="sel_place" id="sel_place" class="form-control" required>
                        <option value="">Select Place</option>
                        <?php
                        while($place = $placeResult->fetch_assoc()) {
                            $selected = ($place['place_id'] == $row['place_id']) ? 'selected' : '';
                            echo "<option value='".$place['place_id']."' ".$selected.">".$place['place_name']."</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Gender</label>
                    <select name="txt_gender" class="form-control" required>
                        <option value="">Select Gender</option>
                        <option value="male" <?php echo ($row['user_gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?php echo ($row['user_gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                        <option value="other" <?php echo ($row['user_gender'] == 'other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" name="btn_update" class="btn btn-primary">Update Profile</button>
                    <a href="MyProfile.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
      

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function getPlace(did) {
            if(did != "") {
                $.ajax({
                    url: "../Assets/AjaxPages/AjaxPlace.php?did="+did,
                    success: function(data) {
                        $("#sel_place").html(data);
                    }
                });
            }
        }
    </script>
</body>
</html>
<?php
include("../User/Footer.php");
ob_end_flush();
?>
