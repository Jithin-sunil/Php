<?php
ob_start();
include("../Shop/Header.php");
session_start();
include('../Assets/Connection/Connection.php');

// Get shop data
$selQry = "SELECT * FROM tbl_shop u 
           INNER JOIN tbl_place p ON u.place_id = p.place_id 
           INNER JOIN tbl_district d ON p.district_id = d.district_id 
           WHERE shop_id = '".$_SESSION['uid']."'";
$result = $conn->query($selQry);
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    
</head>
<body>
    <div class="container">
        <div class="profile-container">
            <h2 class="text-center mb-4">My Profile</h2>
            
            <div class="text-center">
                <img src="../Assets/Files/Shop/<?php echo $row['shop_photo']; ?>" class="img-thumbnail" width="150" height="150" alt="Profile Photo">
            </div>

            <div class="profile-info">
                <span class="info-label">Name:</span>
                <span class="info-value"><?php echo $row['shop_name']; ?></span>
            </div>

            <div class="profile-info">
                <span class="info-label">Email:</span>
                <span class="info-value"><?php echo $row['shop_email']; ?></span>
            </div>

            <div class="profile-info">
                <span class="info-label">Contact:</span>
                <span class="info-value"><?php echo $row['shop_contact']; ?></span>
            </div>

            <div class="profile-info">
                <span class="info-label">Date of Birth:</span>
                <span class="info-value"><?php echo date('d-m-Y', strtotime($row['shop_dob'])); ?></span>
            </div>

            <div class="profile-info">
                <span class="info-label">Address:</span>
                <span class="info-value"><?php echo $row['shop_address']; ?></span>
            </div>

            <div class="profile-info">
                <span class="info-label">District:</span>
                <span class="info-value"><?php echo $row['district_name']; ?></span>
            </div>

            <div class="profile-info">
                <span class="info-label">Place:</span>
                <span class="info-value"><?php echo $row['place_name']; ?></span>
            </div>

            <div class="profile-info">
                <span class="info-label">Gender:</span>
                <span class="info-value"><?php echo ucfirst($row['shop_gender']); ?></span>
            </div>

            <div class="profile-info">
                <span class="info-label">Member Since:</span>
                <span class="info-value"><?php echo date('d-m-Y', strtotime($row['shop_doj'])); ?></span>
            </div>

            <div class="text-center mt-4">
                <a href="EditProfile.php" class="btn btn-primary">Edit Profile</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
include("../Shop/Footer.php");
ob_end_flush();
?>