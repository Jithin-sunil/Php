<?php
ob_start();
include("../Shop/Header.php");
session_start();
include('../Assets/Connection/Connection.php');

if(isset($_POST['btn_change'])) {
    $current_password = $_POST['txt_current'];
    $new_password = $_POST['txt_new'];
    $confirm_password = $_POST['txt_confirm'];
    
    // Check if current password matches
    $selQry = "SELECT * FROM tbl_shop WHERE shop_id='".$_SESSION['sid']."' AND shop_password='$current_password'";
    $result = $conn->query($selQry);
    
    if($result->num_rows > 0) {
        if($new_password == $confirm_password) {
            $upQry = "UPDATE tbl_shop SET shop_password='$new_password' WHERE shop_id='".$_SESSION['sid']."'";
            if($conn->query($upQry)) {
                echo "<script>alert('Password Changed Successfully')</script>";
                echo "<script>window.location='MyProfile.php'</script>";
            } else {
                echo "<script>alert('Failed to Change Password')</script>";
            }
        } else {
            echo "<script>alert('New Password and Confirm Password do not match')</script>";
        }
    } else {
        echo "<script>alert('Current Password is Incorrect')</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
    
            <h2 class="text-center mb-4">Change Password</h2>
            
            <form method="post">
                <div class="form-group">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="txt_current" class="form-control" required minlength="8">
                </div>

                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="txt_new" class="form-control" required minlength="8" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" title="Password must contain at least one letter and one number">
                    <small class="text-muted">Password must be at least 8 characters long and contain at least one letter and one number</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="txt_confirm" class="form-control" required minlength="8">
                </div>

                <div class="text-center mt-4">
                    <button type="submit" name="btn_change" class="btn btn-primary">Change Password</button>
                    <a href="MyProfile.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
include("../Shop/Footer.php");
ob_end_flush();
?>
