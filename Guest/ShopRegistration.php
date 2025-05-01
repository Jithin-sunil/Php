<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery Mask Plugin -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <style>
        .registration-form {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }
        .error-message {
            color: red;
            font-size: 0.875em;
            margin-top: 5px;
        }
        .valid-input {
            border-color: #28a745;
        }
        .invalid-input {
            border-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="registration-form">
            <h2 class="text-center mb-4">Shop Registration</h2>
            <form id="shopRegistrationForm" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Shop Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="error-message" id="nameError"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="error-message" id="emailError"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="contact" class="form-label">Contact Number</label>
                        <input type="text" class="form-control" id="contact" name="contact" required>
                        <div class="error-message" id="contactError"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="error-message" id="passwordError"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                    <div class="error-message" id="addressError"></div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="logo" class="form-label">Shop Logo</label>
                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*" required>
                        <div class="error-message" id="logoError"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="proof" class="form-label">Proof Document</label>
                        <input type="file" class="form-control" id="proof" name="proof" accept=".pdf,.doc,.docx" required>
                        <div class="error-message" id="proofError"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="district" class="form-label">District</label>
                        <select class="form-control" id="district" name="district" required onchange="getPlace(this.value)">
                            <option value="">Select District</option>
                            <?php
                            include('../Assets/Connection/Connection.php');
                            $selQry = "SELECT * FROM tbl_district";
                            $result = $conn->query($selQry);
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='".$row['district_id']."'>".$row['district_name']."</option>";
                            }
                            ?>
                        </select>
                        <div class="error-message" id="districtError"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="place" class="form-label">Place</label>
                        <select class="form-control" id="place" name="place" required>
                            <option value="">Select Place</option>
                        </select>
                        <div class="error-message" id="placeError"></div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize contact number mask
            $('#contact').mask('0000000000');

            // Function to get places based on district
            function getPlace(did) {
                if(did != "") {
                    $.ajax({
                        url: "../Assets/AjaxPages/AjaxPlace.php?did=" + did,
                        success: function(data) {
                            $("#place").html(data);
                        }
                    });
                }
            }

            // Function to capitalize first letter
            function capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }

            // Function to validate contact number
            function validateContact(contact) {
                return /^\d{10}$/.test(contact);
            }

            // Function to validate email
            function validateEmail(email) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            }

            // Function to validate password
            function validatePassword(password) {
                return password.length >= 6;
            }

            // Function to validate file
            function validateFile(file, allowedTypes) {
                if (!file) return false;
                const fileType = file.type;
                return allowedTypes.includes(fileType);
            }

            // Function to validate name (letters and spaces only)
            function validateName(name) {
                return /^[A-Za-z\s]+$/.test(name);
            }

            // Input validation for all fields
            $('input, textarea').on('input', function() {
                const field = $(this);
                const value = field.val().trim();
                const fieldId = field.attr('id');
                const errorElement = $(`#${fieldId}Error`);

                // Remove previous validation classes
                field.removeClass('valid-input invalid-input');
                errorElement.text('');

                if (value) {
                    switch(fieldId) {
                        case 'name':
                            if (validateName(value)) {
                                if (value.charAt(0) !== value.charAt(0).toUpperCase()) {
                                    field.val(capitalizeFirstLetter(value));
                                }
                                field.addClass('valid-input');
                            } else {
                                field.addClass('invalid-input');
                                errorElement.text('Name can only contain letters and spaces');
                            }
                            break;
                        case 'district':
                        case 'place':
                            if (value.charAt(0) !== value.charAt(0).toUpperCase()) {
                                field.val(capitalizeFirstLetter(value));
                            }
                            field.addClass('valid-input');
                            break;
                        case 'contact':
                            if (validateContact(value)) {
                                field.addClass('valid-input');
                            } else {
                                field.addClass('invalid-input');
                                errorElement.text('Please enter a valid 10-digit contact number');
                            }
                            break;
                        case 'email':
                            if (validateEmail(value)) {
                                field.addClass('valid-input');
                            } else {
                                field.addClass('invalid-input');
                                errorElement.text('Please enter a valid email address');
                            }
                            break;
                        case 'password':
                            if (validatePassword(value)) {
                                field.addClass('valid-input');
                            } else {
                                field.addClass('invalid-input');
                                errorElement.text('Password must be at least 6 characters long');
                            }
                            break;
                        case 'logo':
                            const logoFile = field[0].files[0];
                            if (logoFile && !validateFile(logoFile, ['image/jpeg', 'image/png', 'image/gif'])) {
                                field.addClass('invalid-input');
                                errorElement.text('Please upload a valid image file (JPEG, PNG, GIF)');
                            } else {
                                field.addClass('valid-input');
                            }
                            break;
                        case 'proof':
                            const proofFile = field[0].files[0];
                            if (proofFile && !validateFile(proofFile, ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) {
                                field.addClass('invalid-input');
                                errorElement.text('Please upload a valid document (PDF, DOC, DOCX)');
                            } else {
                                field.addClass('valid-input');
                            }
                            break;
                    }
                }
            });

            // Form submission
            $('#shopRegistrationForm').on('submit', function(e) {
                e.preventDefault();
                
                // Validate all fields before submission
                let isValid = true;
                $('input, textarea').each(function() {
                    const field = $(this);
                    const value = field.val().trim();
                    const fieldId = field.attr('id');
                    
                    if (!value) {
                        isValid = false;
                        field.addClass('invalid-input');
                        $(`#${fieldId}Error`).text('This field is required');
                    }
                });

                if (isValid) {
                    // Here you can add your form submission logic
                    console.log('Form is valid, ready to submit');
                    // Add your AJAX call or form submission logic here
                }
            });
        });
    </script>
</body>
</html>
