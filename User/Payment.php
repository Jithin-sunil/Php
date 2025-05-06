<?php
ob_start(); // Start output buffering
include("../Assets/Connection/Connection.php"); // Include the database connection file
session_start();

$selQry = "SELECT * FROM tbl_booking WHERE booking_id = '" . $_SESSION['bid'] . "'";
$selRes = $conn->query($selQry);
$selRow = $selRes->fetch_assoc();
if (isset($_POST['btn_pay'])) {
    $upQry = "UPDATE tbl_booking SET booking_status = '2' WHERE booking_id = '" . $_SESSION['bid'] . "'";
    $upRes = $conn->query($upQry);
    if ($upRes) {
        echo "<script>alert('Payment Successful'); window.location='HomePage.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment Gateway</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --secondary-color: #f9fafb;
            --text-color: #111827;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --success-color: #10b981;
            --error-color: #ef4444;
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --input-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            line-height: 1.5;
        }

        .payment-container {
            max-width: 1100px;
            width: 100%;
        }

        .payment-card {
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), #818cf8);
            color: white;
            padding: 24px 32px;
            position: relative;
        }

        .card-header h3 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
        }

        .card-header p {
            margin: 4px 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .card-body {
            padding: 32px;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-color);
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .form-control {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.2s ease;
            box-shadow: var(--input-shadow);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }

        .form-control.is-invalid {
            border-color: var(--error-color);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }

        .invalid-feedback {
            color: var(--error-color);
            font-size: 0.8rem;
            margin-top: 4px;
        }

        .card-icon {
            position: absolute;
            top: 50%;
            right: 16px;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 18px;
        }

        .card-number-wrapper {
            position: relative;
        }

        .card-type {
            position: absolute;
            top: 50%;
            right: 16px;
            transform: translateY(-50%);
            font-size: 24px;
        }

        .btn-pay {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 14px 24px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-pay:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-pay:active {
            transform: translateY(0);
        }

        .amount-display {
            background-color: var(--secondary-color);
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            border: 1px solid var(--border-color);
            margin-bottom: 32px;
        }

        .amount-title {
            font-size: 1rem;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .amount-value {
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--text-color);
        }

        .payment-methods {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }

        .payment-method {
            width: 64px;
            height: 40px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            background-color: white;
        }

        .payment-method img {
            max-width: 80%;
            max-height: 80%;
            object-fit: contain;
        }

        .payment-method.active {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
        }

        .form-text {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .secure-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 24px;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .secure-badge i {
            margin-right: 8px;
            color: var(--success-color);
        }

        /* Credit Card Styles */
        .card-container {
            width: 100%;
            height: 220px;
            position: relative;
            margin-bottom: 32px;
            perspective: 1000px;
        }

        .credit-card {
            width: 100%;
            height: 100%;
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.6s;
        }

        .credit-card-front,
        .credit-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            backface-visibility: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .credit-card-front {
            background: linear-gradient(135deg, #4f46e5, #818cf8);
            color: white;
            transform: rotateY(0deg);
        }

        .credit-card-back {
            background: linear-gradient(135deg, #818cf8, #4f46e5);
            color: white;
            transform: rotateY(180deg);
        }

        .card-chip {
            width: 50px;
            height: 40px;
            background: linear-gradient(135deg, #ffd700, #ffec8b);
            border-radius: 8px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .card-chip::before {
            content: "";
            position: absolute;
            width: 30px;
            height: 30px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -15px;
            left: 10px;
        }

        .card-number-display {
            font-size: 1.4rem;
            letter-spacing: 2px;
            text-align: center;
            margin-bottom: 20px;
            font-family: monospace;
        }

        .card-holder-display {
            font-size: 1rem;
            text-transform: uppercase;
        }

        .card-expiry-display {
            font-size: 1rem;
        }

        .card-details {
            display: flex;
            justify-content: space-between;
        }

        .card-brand {
            font-size: 24px;
            align-self: flex-end;
        }

        /* Card Back Styles */
        .card-magnetic-strip {
            height: 40px;
            background: #000;
            margin: 20px -24px;
        }

        .card-cvv-display {
            background: white;
            color: #000;
            padding: 8px;
            border-radius: 4px;
            text-align: right;
            font-family: monospace;
            margin-top: 10px;
            align-self: flex-end;
            width: 60px;
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .card-body {
                padding: 24px;
            }
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 20px;
            }
            
            .row {
                flex-direction: column-reverse;
            }
            
            .card-container {
                height: 200px;
                margin-bottom: 24px;
            }
            
            .card-number-display {
                font-size: 1.2rem;
            }
            
            .amount-value {
                font-size: 1.75rem;
            }
        }

        @media (max-width: 576px) {
            .card-container {
                height: 180px;
            }
            
            .card-number-display {
                font-size: 1rem;
            }
            
            .card-details {
                flex-direction: column;
                gap: 8px;
            }
            
            .card-brand {
                align-self: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-card">
            <div class="card-header">
                <h3>Secure Payment</h3>
                <p>Complete your purchase with our secure payment system</p>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-7">
                        <form id="payment-form" action="Payment.php" method="POST">
                            <!-- Hidden input for booking ID -->
                            <input type="hidden" name="booking_id" value="<?php echo isset($_SESSION['bid']) ? $_SESSION['bid'] : ''; ?>">
                            <!-- Amount Display -->
                            <div class="amount-display">
                                <div class="amount-title">Total Amount</div>
                                <div class="amount-value">$<span id="payment-amount"><?php echo $selRow['booking_amount']?></span></div>
                            </div>
                            
                            <!-- Payment Methods -->
                            <div class="payment-methods">
                                <div class="payment-method active" data-type="visa">
                                    <img src="https://cdn-icons-png.flaticon.com/512/196/196578.png" alt="Visa">
                                </div>
                                <div class="payment-method" data-type="mastercard">
                                    <img src="https://cdn-icons-png.flaticon.com/512/196/196561.png" alt="MasterCard">
                                </div>
                                <div class="payment-method" data-type="amex">
                                    <img src="https://cdn-icons-png.flaticon.com/512/196/196539.png" alt="American Express">
                                </div>
                                <div class="payment-method" data-type="discover">
                                    <img src="https://cdn-icons-png.flaticon.com/512/196/196565.png" alt="Discover">
                                </div>
                            </div>
                            
                            <!-- Card Holder Name -->
                            <div class="mb-3">
                                <label for="cardHolder" class="form-label">Card Holder Name</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" id="cardHolder" name="cardHolder" placeholder="John Doe" required>
                                    <i class="fas fa-user card-icon"></i>
                                </div>
                                <div class="invalid-feedback">Please enter the name on your card</div>
                            </div>
                            
                            <!-- Card Number -->
                            <div class="mb-3">
                                <label for="cardNumber" class="form-label">Card Number</label>
                                <div class="card-number-wrapper">
                                    <input type="text" class="form-control" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19" required>
                                    <div class="card-type">
                                        <i class="fab fa-cc-visa" id="card-type-icon"></i>
                                    </div>
                                </div>
                                <div class="form-text">Enter the 16-digit card number on the card</div>
                                <div class="invalid-feedback">Please enter a valid card number</div>
                            </div>
                            
                            <div class="row">
                                <!-- Expiration Date -->
                                <div class="col-md-6 mb-3">
                                    <label for="expiryDate" class="form-label">Expiry Date</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control" id="expiryDate" name="expiryDate" placeholder="MM/YY" maxlength="5" required>
                                        <i class="fas fa-calendar-alt card-icon"></i>
                                    </div>
                                    <div class="form-text">Enter the expiration date of the card</div>
                                    <div class="invalid-feedback">Please enter a valid expiry date (MM/YY)</div>
                                </div>
                                
                                <!-- CVV -->
                                <div class="col-md-6 mb-3">
                                    <label for="cvv" class="form-label">CVV</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control" id="cvv" name="cvv" placeholder="123" maxlength="4" required>
                                        <i class="fas fa-lock card-icon"></i>
                                    </div>
                                    <div class="form-text">Enter the 3 or 4 digit number on the card</div>
                                    <div class="invalid-feedback">Please enter a valid CVV</div>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" class="btn-pay mt-4" name="btn_pay">
                                Pay $<span class="payment-amount-btn"><?php echo $selRow['booking_amount']?></span>
                                <i class="fas fa-lock"></i>
                            </button>
                            
                            <div class="secure-badge">
                                <i class="fas fa-shield-alt"></i>
                                <span>Your payment information is secure and encrypted</span>
                            </div>
                        </form>
                    </div>
                    
                    <div class="col-lg-5">
                        <!-- Credit Card Visual -->
                        <div class="card-container">
                            <div class="credit-card" id="credit-card">
                                <div class="credit-card-front">
                                    <div class="card-chip"></div>
                                    <div class="card-number-display" id="display-card-number">•••• •••• •••• ••••</div>
                                    <div class="card-details">
                                        <div>
                                            <div class="form-text text-white mb-1">Card Holder</div>
                                            <div class="card-holder-display" id="display-card-holder">JOHN DOE</div>
                                        </div>
                                        <div>
                                            <div class="form-text text-white mb-1">Expires</div>
                                            <div class="card-expiry-display" id="display-expiry">MM/YY</div>
                                        </div>
                                    </div>
                                    <div class="card-brand">
                                        <i class="fab fa-cc-visa" id="display-card-brand"></i>
                                    </div>
                                </div>
                                <div class="credit-card-back">
                                    <div class="card-magnetic-strip"></div>
                                    <div class="card-cvv-display" id="display-cvv">•••</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card p-4 mb-4 border-0 shadow-sm">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-shield-alt text-success fs-3"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 fs-6 fw-bold">Secure Payment</h5>
                                    <p class="mb-0 small text-muted">Your payment information is encrypted and secure. We never store your full card details.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card p-4 border-0 shadow-sm">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-check-circle text-primary fs-3"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 fs-6 fw-bold">Purchase Protection</h5>
                                    <p class="mb-0 small text-muted">Your transaction is protected. If there's an issue with your order, we've got you covered.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Card flip functionality
            const cvvInput = document.getElementById('cvv');
            const creditCard = document.getElementById('credit-card');
            
            cvvInput.addEventListener('focus', function() {
                creditCard.style.transform = 'rotateY(180deg)';
            });
            
            cvvInput.addEventListener('blur', function() {
                creditCard.style.transform = 'rotateY(0deg)';
            });
            
            // Payment method selection
            const paymentMethods = document.querySelectorAll('.payment-method');
            const cardTypeIcon = document.getElementById('card-type-icon');
            const displayCardBrand = document.getElementById('display-card-brand');
            
            paymentMethods.forEach(method => {
                method.addEventListener('click', function() {
                    // Remove active class from all methods
                    paymentMethods.forEach(m => m.classList.remove('active'));
                    // Add active class to clicked method
                    this.classList.add('active');
                    
                    // Get card type
                    const cardType = this.getAttribute('data-type');
                    
                    // Change card brand icon
                    cardTypeIcon.className = 'fab fa-cc-' + cardType;
                    displayCardBrand.className = 'fab fa-cc-' + cardType;
                });
            });
            
            // Card number formatting and validation
            const cardNumberInput = document.getElementById('cardNumber');
            const displayCardNumber = document.getElementById('display-card-number');
            
            cardNumberInput.addEventListener('input', function(e) {
                // Remove all non-digit characters
                let value = this.value.replace(/\D/g, '');
                
                // Add space after every 4 digits
                value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
                
                // Update input value
                this.value = value;
                
                // Update card display
                if (value === '') {
                    displayCardNumber.textContent = '•••• •••• •••• ••••';
                } else {
                    // Show first 4 digits and mask the rest
                    const visibleDigits = value.substring(0, 4);
                    const maskedDigits = value.substring(4).replace(/\d/g, '•');
                    displayCardNumber.textContent = visibleDigits + maskedDigits;
                }
                
                // Detect card type
                detectCardType(value.replace(/\s/g, ''));
            });
            
            // Card holder name update
            const cardHolderInput = document.getElementById('cardHolder');
            const displayCardHolder = document.getElementById('display-card-holder');
            
            cardHolderInput.addEventListener('input', function() {
                displayCardHolder.textContent = this.value.toUpperCase() || 'JOHN DOE';
            });
            
            // Expiry date formatting
            const expiryDateInput = document.getElementById('expiryDate');
            const displayExpiry = document.getElementById('display-expiry');
            
            expiryDateInput.addEventListener('input', function(e) {
                let value = this.value;
                
                // Remove all non-digit characters
                value = value.replace(/\D/g, '');
                
                // Add slash after 2 digits
                if (value.length > 2) {
                    value = value.substring(0, 2) + '/' + value.substring(2);
                }
                
                // Limit to 5 characters (MM/YY)
                if (value.length > 5) {
                    value = value.substring(0, 5);
                }
                
                this.value = value;
                displayExpiry.textContent = value || 'MM/YY';
            });
            
            // CVV display update
            const cvvDisplay = document.getElementById('display-cvv');
            
            cvvInput.addEventListener('input', function() {
                cvvDisplay.textContent = this.value.replace(/\d/g, '•') || '•••';
            });
            
            // Form validation
            const form = document.getElementById('payment-form');
            
            form.addEventListener('submit', function(e) {
                let isValid = true;
                
                // Validate card holder name
                if (!cardHolderInput.value.trim()) {
                    cardHolderInput.classList.add('is-invalid');
                    isValid = false;
                } else {
                    cardHolderInput.classList.remove('is-invalid');
                }
                
                // Validate card number (basic validation)
                const cardNumber = cardNumberInput.value.replace(/\s/g, '');
                if (!cardNumber || cardNumber.length < 16 || !/^\d+$/.test(cardNumber)) {
                    cardNumberInput.classList.add('is-invalid');
                    isValid = false;
                } else {
                    cardNumberInput.classList.remove('is-invalid');
                }
                
                // Validate expiry date (basic validation)
                const expiryDate = expiryDateInput.value;
                if (!expiryDate || !/^\d{2}\/\d{2}$/.test(expiryDate)) {
                    expiryDateInput.classList.add('is-invalid');
                    isValid = false;
                } else {
                    expiryDateInput.classList.remove('is-invalid');
                }
                
                // Validate CVV (basic validation)
                const cvv = cvvInput.value;
                if (!cvv || !/^\d{3,4}$/.test(cvv)) {
                    cvvInput.classList.add('is-invalid');
                    isValid = false;
                } else {
                    cvvInput.classList.remove('is-invalid');
                }
                
                if (!isValid) {
                    e.preventDefault();
                }
            });
            
            // Helper function to detect card type
            function detectCardType(cardNumber) {
                // Visa
                if (/^4/.test(cardNumber)) {
                    cardTypeIcon.className = 'fab fa-cc-visa';
                    displayCardBrand.className = 'fab fa-cc-visa';
                    return;
                }
                
                // Mastercard
                if (/^5[1-5]/.test(cardNumber)) {
                    cardTypeIcon.className = 'fab fa-cc-mastercard';
                    displayCardBrand.className = 'fab fa-cc-mastercard';
                    return;
                }
                
                // American Express
                if (/^3[47]/.test(cardNumber)) {
                    cardTypeIcon.className = 'fab fa-cc-amex';
                    displayCardBrand.className = 'fab fa-cc-amex';
                    return;
                }
                
                // Discover
                if (/^6(?:011|5)/.test(cardNumber)) {
                    cardTypeIcon.className = 'fab fa-cc-discover';
                    displayCardBrand.className = 'fab fa-cc-discover';
                    return;
                }
                
                // Default to generic
                cardTypeIcon.className = 'far fa-credit-card';
                displayCardBrand.className = 'far fa-credit-card';
            }
        });
    </script>

    <?php
    ob_flush();
    ?>
</body>
</html>