<?php
ob_start();
session_start();
include('../Assets/Connection/Connection.php');

if(isset($_POST['btn_login'])) {
    $email = $_POST['txt_email'];
    $password = $_POST['txt_password'];
    $selQryU = "SELECT * FROM tbl_user WHERE user_email = '$email' AND user_password = '$password'";
    $resultuser = $conn->query($selQryU);

    $selQryS = "SELECT * FROM tbl_shop WHERE shop_email = '$email' AND shop_password = '$password'";
    $resultshop = $conn->query($selQryS);

    
    if($rowuser = $resultuser->fetch_assoc()) {

            $_SESSION['uid'] = $rowuser['user_id'];
            $_SESSION['uname'] = $rowuser['user_name'];
            header('location:../User/HomePage.php');
    
        
    }
    elseif($rowshop = $resultshop->fetch_assoc()) {
        $_SESSION['sid'] = $rowshop['shop_id'];
        $_SESSION['sname'] = $rowshop['shop_name'];
        header('location:../Shop/HomePage.php');
    }
    else {
        ?>
        <script>alert('Invalid email or account not active');</script>
        <?php
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-form {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .required-field::after {
            content: " *";
            color: red;
        }
        .error-message {
            color: red;
            font-size: 12px;
            display: none;
            margin-top: 5px;
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
    <div class="container">
        <div class="login-form">
            <h2 class="text-center mb-4">User Login</h2>
            <form method="post" id="loginForm">
                <div class="form-group">
                    <label for="txt_email" class="required-field">Email Address</label>
                    <input type="email" class="form-control" id="txt_email" name="txt_email" required>
                    
                </div>
                
                <div class="form-group">
                    <label for="txt_password" class="required-field">Password</label>
                    <input type="password" class="form-control" id="txt_password" name="txt_password" required>
                    
                </div>

                <div class="form-group text-center mt-4">
                    <button type="submit" name="btn_login" class="btn btn-primary">Login</button>
                </div>

                <div class="text-center mt-3">
                    <a href="UserRegistration.php">Don't have an account? Register here</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   
</body>
</html>
<?php
ob_end_flush();
?> 