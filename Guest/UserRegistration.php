<?php
ob_start();
include('../Assets/Connection/Connection.php');

if(isset($_POST['btn_submit'])) {
    $name = $_POST['txt_name'];
    $email = $_POST['txt_email'];
    $password = $_POST['txt_password'];
    $contact = $_POST['txt_contact'];
    $address = $_POST['txt_address'];
    $dob = $_POST['txt_dob'];
    $district = $_POST['sel_district'];
    $place = $_POST['sel_place'];
    $gender = $_POST['rd_gender'];

    // Handle file uploads
    $photo = $_FILES['file_photo']['name'];
    $temp = $_FILES['file_photo']['tmp_name'];
    $path = "../Assets/Files/User/".$photo;
    move_uploaded_file($temp, $path);

    $proof = $_FILES['file_proof']['name'];
    $temp = $_FILES['file_proof']['tmp_name'];
    $path = "../Assets/Files/User/".$proof;
    move_uploaded_file($temp, $path);
    
    // Check if email already exists
    $checkEmail = "SELECT * FROM tbl_user WHERE user_email = '$email'";
    $result = $conn->query($checkEmail);
    
    if($result->num_rows > 0) {
        echo "<script>alert('Email already exists');</script>";
    } else {
        // Insert into database
        $insQry = "INSERT INTO tbl_user (user_name, user_email, user_password, user_contact, user_address, user_dob, district_id, place_id, user_gender, user_photo, user_proof) 
                   VALUES ('$name', '$email', '$password', '$contact', '$address', '$dob', '$district', '$place', '$gender', '$photo', '$proof')";
        
        if($conn->query($insQry)) {
            echo "<script>
                alert('Registration successful');
                window.location='UserRegistration.php';
            </script>";
        } else {
            echo "<script>
                alert('Registration failed: " . $conn->error . "');
            </script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .registration-form {
            max-width: 800px;
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
</head>
<body>
    <div class="container">
        <div class="registration-form">
            <h2 class="text-center mb-4">User Registration</h2>
            <form id="RegistrationForm" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="txt_name" class="required-field">Full Name</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="txt_name" 
                                   name="txt_name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="txt_email" class="required-field">Email Address</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="txt_email" 
                                   name="txt_email">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="txt_password" class="required-field">Password</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="txt_password" 
                                   name="txt_password">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="txt_contact" class="required-field">Contact Number</label>
                            <input type="tel" 
                                   class="form-control" 
                                   id="txt_contact" 
                                   name="txt_contact" 
                                   maxlength="10" 
                                   minlength="10" 
                                   onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="txt_address" class="required-field">Address</label>
                    <textarea class="form-control" 
                              id="txt_address" 
                              name="txt_address" 
                              rows="3"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="txt_dob" class="required-field">Date of Birth</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="txt_dob" 
                                   name="txt_dob">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sel_district" class="required-field">District</label>
                            <select class="form-control" 
                                    id="sel_district" 
                                    name="sel_district" 
                                    onchange="getPlace(this.value)">
                                <option value="">Select District</option>
                                <?php
                                $selQry = "SELECT * FROM tbl_district";
                                $result = $conn->query($selQry);
                                while($row = $result->fetch_assoc()) {
                                    echo "<option value='".$row['district_id']."'>".$row['district_name']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sel_place" class="required-field">Place</label>
                            <select class="form-control" id="sel_place" name="sel_place">
                                <option value="">Select Place</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required-field">Gender</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rd_gender" id="rd_male" value="male">
                                    <label class="form-check-label" for="rd_male">Male</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rd_gender" id="rd_female" value="female">
                                    <label class="form-check-label" for="rd_female">Female</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rd_gender" id="rd_other" value="other">
                                    <label class="form-check-label" for="rd_other">Other</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="file_photo" class="required-field">Photo</label>
                            <input type="file" 
                                   class="form-control" 
                                   id="file_photo" 
                                   name="file_photo" 
                                   accept="image/*">
                            <img id="photoPreview" class="preview-image" src="#" alt="Photo Preview">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="file_proof" class="required-field">Proof (PDF)</label>
                            <input type="file" 
                                   class="form-control" 
                                   id="file_proof" 
                                   name="file_proof" 
                                   accept=".pdf">
                            <div id="proofPreview" class="preview-text"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group text-center mt-4">
                    <input type="submit" name="btn_submit" class="btn btn-primary" value="Register">
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Assets/JQ/jQuery.js"></script>
    <script src="../Assets/Validation.js"></script>
    <script>
        // Initialize form validation
        validateForm('#RegistrationForm', 'submit');
        validateForm('#RegistrationForm', 'input');

        // Ajax for dynamic place selection
        function getPlace(did) {
            $.ajax({
                url: "../Assets/AjaxPages/AjaxPlace.php?did=" + did,
                success: function (result) {
                    $("#sel_place").html(result);
                }
            });
        }
    </script>
</body>
</html>
<?php
ob_end_flush();
?>