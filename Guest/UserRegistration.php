<?php
ob_start();
include('../Assets/Connection/Connection.php');

if(isset($_POST['btn_submit'])) {
    ?>
    <script>
        alert('Registration successful');
        window.location='UserRegistration.php';
    </script>
    <?php

    $name = $_POST['txt_name'];
    $email = $_POST['txt_email'];
    $contact = $_POST['txt_contact'];
    $address = $_POST['txt_address'];
    $dob = $_POST['txt_dob'];
    $district = $_POST['sel_district'];
    $place = $_POST['sel_place'];
    $gender = $_POST['rd_gender'];
    $photo = $_FILES['file_photo']['name'];
    $temp = $_FILES['file_photo']['tmp_name'];
    $path = "../Assets/Files/User/".$photo;
    move_uploaded_file($temp, $path);
    
    // Check if email already exists
    $checkEmail = "SELECT * FROM tbl_user WHERE user_email = '$email'";
    $result = $conn->query($checkEmail);
    
    if($result->num_rows > 0) {
        echo "<script>alert('Email already exists');</script>";
    } else {
        // Handle photo upload
        
        
        // Insert into database
       echo  $insQry = "INSERT INTO tbl_user (user_name, user_email, user_contact, user_address, user_dob, place_id, user_gender, user_photo) 
                   VALUES ('$name', '$email', '$contact', '$address', '$dob', '$place', '$gender', '$photo')";
        
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
        <div class="registration-form">
            <h2 class="text-center mb-4">User Registration</h2>
            <form method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="txt_name" class="required-field">Full Name</label>
                            <input type="text" class="form-control" id="txt_name" name="txt_name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="txt_email" class="required-field">Email Address</label>
                            <input type="email" class="form-control" id="txt_email" name="txt_email" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="txt_contact" class="required-field">Contact Number</label>
                            <input type="tel" class="form-control" id="txt_contact" name="txt_contact" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="txt_dob" class="required-field">Date of Birth</label>
                            <input type="date" class="form-control" id="txt_dob" name="txt_dob" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="txt_address" class="required-field">Address</label>
                    <textarea class="form-control" id="txt_address" name="txt_address" rows="3" required></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sel_district" class="required-field">District</label>
                            <select class="form-control" id="sel_district" name="sel_district" required onchange="getPlace(this.value)">
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sel_place" class="required-field">Place</label>
                            <select class="form-control" id="sel_place" name="sel_place" required>
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
                                    <input class="form-check-input" type="radio" name="rd_gender" id="rd_male" value="male" required>
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
                            <input type="file" class="form-control" id="file_photo" name="file_photo" accept="image/*" required>
                            <img id="photoPreview" class="preview-image" src="#" alt="Photo Preview">
                        </div>
                    </div>
                </div>

                <div class="form-group text-center mt-4">
                    <input type="submit" name="btn_submit" class="btn btn-primary" value="Register"></input>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to show error message
        function showError(element, message) {
            const errorDiv = element.nextElementSibling;
            if (errorDiv && errorDiv.classList.contains('error-message')) {
                errorDiv.textContent = message;
                errorDiv.style.display = 'block';
                element.classList.add('is-invalid');
                element.classList.remove('is-valid');
            }
        }

        // Function to hide error message
        function hideError(element) {
            const errorDiv = element.nextElementSibling;
            if (errorDiv && errorDiv.classList.contains('error-message')) {
                errorDiv.style.display = 'none';
                element.classList.remove('is-invalid');
                element.classList.add('is-valid');
            }
        }

        // Real-time validation functions
        function validateName() {
            const name = document.getElementById('txt_name');
            const nameRegex = /^[a-zA-Z\s]+$/;
            if (!nameRegex.test(name.value)) {
                showError(name, 'Name should contain only letters and spaces');
                return false;
            }
            hideError(name);
            return true;
        }

        function validateEmail() {
            const email = document.getElementById('txt_email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                showError(email, 'Please enter a valid email address');
                return false;
            }
            hideError(email);
            return true;
        }

        function validateContact() {
            const contact = document.getElementById('txt_contact');
            const contactRegex = /^\d{10}$/;
            if (!contactRegex.test(contact.value)) {
                showError(contact, 'Contact number should be 10 digits');
                return false;
            }
            hideError(contact);
            return true;
        }

        function validateAddress() {
            const address = document.getElementById('txt_address');
            if (address.value.length < 10) {
                showError(address, 'Address should be at least 10 characters long');
                return false;
            }
            hideError(address);
            return true;
        }

        function validateDOB() {
            const dob = document.getElementById('txt_dob');
            const today = new Date();
            const birthDate = new Date(dob.value);
            const age = today.getFullYear() - birthDate.getFullYear();
            
            if (!dob.value) {
                showError(dob, 'Please select your date of birth');
                return false;
            }
            if (age < 18) {
                showError(dob, 'You must be at least 18 years old');
                return false;
            }
            hideError(dob);
            return true;
        }

        function validateDistrict() {
            const district = document.getElementById('sel_district');
            if (district.value === '') {
                showError(district, 'Please select a district');
                return false;
            }
            hideError(district);
            return true;
        }

        function validatePlace() {
            const place = document.getElementById('sel_place');
            if (place.value === '') {
                showError(place, 'Please select a place');
                return false;
            }
            hideError(place);
            return true;
        }

        function validateGender() {
            const gender = document.querySelector('input[name="rd_gender"]:checked');
            if (!gender) {
                showError(document.querySelector('input[name="rd_gender"]'), 'Please select your gender');
                return false;
            }
            hideError(document.querySelector('input[name="rd_gender"]'));
            return true;
        }

        function validatePhoto() {
            const photo = document.getElementById('file_photo');
            if (photo.files.length === 0) {
                showError(photo, 'Please upload a photo');
                return false;
            }

            const file = photo.files[0];
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            const maxSize = 2 * 1024 * 1024; // 2MB

            if (!validTypes.includes(file.type)) {
                showError(photo, 'Please upload a valid image file (JPEG, PNG, JPG)');
                return false;
            }

            if (file.size > maxSize) {
                showError(photo, 'Image size should be less than 2MB');
                return false;
            }
            hideError(photo);
            return true;
        }

        // Add error message divs after each input
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.type !== 'hidden') {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message';
                    input.parentNode.insertBefore(errorDiv, input.nextSibling);
                }
            });
        });

        // Add event listeners for real-time validation
        document.getElementById('txt_name').addEventListener('input', validateName);
        document.getElementById('txt_email').addEventListener('input', validateEmail);
        document.getElementById('txt_contact').addEventListener('input', validateContact);
        document.getElementById('txt_address').addEventListener('input', validateAddress);
        document.getElementById('txt_dob').addEventListener('change', validateDOB);
        document.getElementById('sel_district').addEventListener('change', validateDistrict);
        document.getElementById('sel_place').addEventListener('change', validatePlace);
        document.querySelectorAll('input[name="rd_gender"]').forEach(radio => {
            radio.addEventListener('change', validateGender);
        });
        document.getElementById('file_photo').addEventListener('change', validatePhoto);

        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const validations = [
                validateName(),
                validateEmail(),
                validateContact(),
                validateAddress(),
                validateDOB(),
                validateDistrict(),
                validatePlace(),
                validateGender(),
                validatePhoto()
            ];

            if (validations.every(validation => validation === true)) {
                this.submit();
            }
        });

        // Photo preview functionality
        document.getElementById('file_photo').addEventListener('change', function(e) {
            const preview = document.getElementById('photoPreview');
            const file = e.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
<script src="../Assets/JQ/jQuery.js"></script>
<script>
  function getPlace(did) {
    $.ajax({
      url: "../Assets/AjaxPages/AjaxPlace.php?did=" + did,
      success: function (result) {
        $("#sel_place").html(result);
      }
    });
  }

</script>
<?php
ob_end_flush();
?>

