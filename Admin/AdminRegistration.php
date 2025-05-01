<?php
ob_start();
include('../Admin/Header.php');
include('../Assets/Connection/Connection.php');

if(isset($_POST['btn_submit'])) {
    $name = $_POST['txt_name'];
    $email = $_POST['txt_email'];
    $password = $_POST['txt_password'];
    $confirm_password = $_POST['txt_confirm_password'];
    
    // Validation
    if($password != $confirm_password) {
        echo "<script>alert('Passwords do not match');</script>";
    } else {
        // Check if email already exists
        $checkEmail = "SELECT * FROM tbl_admin WHERE admin_email = '$email'";
        $result = $conn->query($checkEmail);
        
        if($result->num_rows > 0) {
            echo "<script>alert('Email already exists');</script>";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert into database
            $insQry = "INSERT INTO tbl_admin (admin_name, admin_email, admin_password) VALUES ('$name', '$email', '$hashed_password')";
            
            if($conn->query($insQry)) {
                echo "<script>alert('Registration successful');
                window.location='AdminRegistration.php';
                </script>";
            } else {
                echo "<script>alert('Registration failed');</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
</head>
<body>
    <h2 class="text-center">Admin Registration</h2>
    <form method="post">
        <table class="table table-bordered table-hover">
            <tr>
                <td width="30%">Name</td>
                <td>
                    <input 
                        type="text" 
                        class="form-control" 
                        name="txt_name" 
                        required
                        pattern="^[A-Z][a-zA-Z ]*$"
                        title="Name must start with a capital letter and contain only letters and spaces"
                    >
                </td>
            </tr>
            <tr>
                <td>Email</td>
                <td>
                    <input 
                        type="email" 
                        class="form-control" 
                        name="txt_email" 
                        required
                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                        title="Enter a valid email address"
                    >
                </td>
            </tr>
            <tr>
                <td>Password</td>
                <td>
                    <input 
                        type="password" 
                        class="form-control" 
                        name="txt_password" 
                        required
                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                        title="Password must be at least 8 characters, include one number, one uppercase, and one lowercase letter"
                    >
                </td>
            </tr>
            <tr>
                <td>Confirm Password</td>
                <td>
                    <input 
                        type="password" 
                        class="form-control" 
                        name="txt_confirm_password" 
                        required
                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                        title="Confirm password must match the password"
                    >
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <button type="submit" name="btn_submit" class="btn btn-outline-primary">Register</button>
                </td>
            </tr>
        </table>
    </form>
    
<?php
ob_end_flush();
include('../Admin/Footer.php');
?>