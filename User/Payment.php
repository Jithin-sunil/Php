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
            perspective: 1000px;
            width: 100%;
            height: 220px;
            position: relative;
            margin-bottom: 32px;
        }

        .credit-card {
            width: 100%;
            height: 100%;
            position: relative;
            transition: transform 0.8s;
            transform-style: preserve-3d;
        }

        .credit-card.flipped {
            transform: rotateY(180deg);
        }

        .credit-card-front, .credit-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .credit-card-front {
            background: linear-gradient(135deg, #4f46e5, #818cf8);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .credit-card-back {
            background: linear-gradient(135deg, #4f46e5, #818cf8);
            color: white;
            transform: rotateY(180deg);
            display: flex;
            flex-direction: column;
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

        .card-black-strip {
            height: 40px;
            background-color: #000;
            margin: 20px 0;
        }

        .card-signature-strip {
            height: 40px;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 10px;
            margin-bottom: 20px;
        }

        .card-cvv-display {
            color: #333;
            font-weight: bold;
        }

        /* Processing and Success States */
        .processing-overlay, .success-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.95);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 100;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .processing-overlay.active, .success-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .lottie-container {
            width: 200px;
            height: 200px;
        }

        .processing-text, .success-text {
            font-size: 1.25rem;
            font-weight: 600;
            margin-top: 16px;
            color: var(--text-color);
        }

        .success-message {
            text-align: center;
            max-width: 80%;
        }

        .success-details {
            margin-top: 8px;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .btn-continue {
            background-color: var(--success-color);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            font-size: 1rem;
            margin-top: 24px;
            transition: all 0.3s ease;
        }

        .btn-continue:hover {
            background-color: #0ca678;
            transform: translateY(-1px);
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
                        <form id="payment-form">
                            <!-- Amount Display -->
                            <div class="amount-display">
                                <div class="amount-title">Total Amount</div>
                                <div class="amount-value">$<span id="payment-amount">149.99</span></div>
                            </div>
                            
                            <!-- Payment Methods -->
                            <div class="payment-methods">
                                <div class="payment-method active">
                                    <img src="https://cdn-icons-png.flaticon.com/512/196/196578.png" alt="Visa">
                                </div>
                                <div class="payment-method">
                                    <img src="https://cdn-icons-png.flaticon.com/512/196/196561.png" alt="MasterCard">
                                </div>
                                <div class="payment-method">
                                    <img src="https://cdn-icons-png.flaticon.com/512/196/196539.png" alt="American Express">
                                </div>
                                <div class="payment-method">
                                    <img src="https://cdn-icons-png.flaticon.com/512/196/196565.png" alt="Discover">
                                </div>
                            </div>
                            
                            <!-- Card Holder Name -->
                            <div class="mb-3">
                                <label for="cardHolder" class="form-label">Card Holder Name</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" id="cardHolder" placeholder="John Doe" required>
                                    <i class="fas fa-user card-icon"></i>
                                </div>
                            </div>
                            
                            <!-- Card Number -->
                            <div class="mb-3">
                                <label for="cardNumber" class="form-label">Card Number</label>
                                <div class="card-number-wrapper">
                                    <input type="text" class="form-control" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19" required>
                                    <div class="card-type">
                                        <i class="fab fa-cc-visa" id="card-type-icon"></i>
                                    </div>
                                </div>
                                <div class="form-text">Enter the 16-digit card number on the card</div>
                            </div>
                            
                            <div class="row">
                                <!-- Expiration Date -->
                                <div class="col-md-6 mb-3">
                                    <label for="expiryDate" class="form-label">Expiry Date</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control" id="expiryDate" placeholder="MM/YY" maxlength="5" required>
                                        <i class="fas fa-calendar-alt card-icon"></i>
                                    </div>
                                    <div class="form-text">Enter the expiration date of the card</div>
                                </div>
                                
                                <!-- CVV -->
                                <div class="col-md-6 mb-3">
                                    <label for="cvv" class="form-label">CVV</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control" id="cvv" placeholder="123" maxlength="4" required>
                                        <i class="fas fa-lock card-icon"></i>
                                    </div>
                                    <div class="form-text">Enter the 3 or 4 digit number on the card</div>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" class="btn-pay mt-4">
                                Pay $<span class="payment-amount-btn">149.99</span>
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
                                    <div class="card-number-display" id="card-number-display">•••• •••• •••• ••••</div>
                                    <div class="card-details">
                                        <div>
                                            <div class="form-text text-white mb-1">Card Holder</div>
                                            <div class="card-holder-display" id="card-holder-display">JOHN DOE</div>
                                        </div>
                                        <div>
                                            <div class="form-text text-white mb-1">Expires</div>
                                            <div class="card-expiry-display" id="card-expiry-display">MM/YY</div>
                                        </div>
                                    </div>
                                    <div class="card-brand">
                                        <i class="fab fa-cc-visa"></i>
                                    </div>
                                </div>
                                <div class="credit-card-back">
                                    <div class="card-black-strip"></div>
                                    <div class="card-signature-strip">
                                        <div class="card-cvv-display" id="card-cvv-display">123</div>
                                    </div>
                                    <div class="card-brand">
                                        <i class="fab fa-cc-visa"></i>
                                    </div>
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
                
                <!-- Processing Overlay -->
                <div class="processing-overlay" id="processing-overlay">
                    <div class="lottie-container" id="processing-animation"></div>
                    <div class="processing-text">Processing your payment...</div>
                </div>
                
                <!-- Success Overlay -->
                <div class="success-overlay" id="success-overlay">
                    <div class="lottie-container" id="success-animation"></div>
                    <div class="success-message">
                        <div class="success-text">Payment Successful!</div>
                        <div class="success-details">Your transaction has been completed and a receipt has been emailed to you.</div>
                        <button class="btn-continue" id="continue-btn">Continue</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Lottie Player -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Create Lottie animations
            const processingAnimation = document.createElement('lottie-player');
            processingAnimation.src = 'https://assets3.lottiefiles.com/packages/lf20_kk62um5v.json';
            processingAnimation.background = 'transparent';
            processingAnimation.speed = '1';
            processingAnimation.style.width = '100%';
            processingAnimation.style.height = '100%';
            processingAnimation.loop = true;
            processingAnimation.autoplay = true;
            
            document.getElementById('processing-animation').appendChild(processingAnimation);
            
            const successAnimation = document.createElement('lottie-player');
            successAnimation.src = 'https://assets2.lottiefiles.com/packages/lf20_s2lryxtd.json';
            successAnimation.background = 'transparent';
            successAnimation.speed = '1';
            successAnimation.style.width = '100%';
            successAnimation.style.height = '100%';
            successAnimation.loop = false;
            successAnimation.autoplay = true;
            
            document.getElementById('success-animation').appendChild(successAnimation);
            
            // Card Number Formatting and Validation
            const cardNumberInput = document.getElementById('cardNumber');
            cardNumberInput.addEventListener('input', function(e) {
                // Remove all non-digit characters
                let value = e.target.value.replace(/\D/g, '');
                
                // Format with spaces after every 4 digits
                let formattedValue = '';
                for (let i = 0; i < value.length; i++) {
                    if (i > 0 && i % 4 === 0) {
                        formattedValue += ' ';
                    }
                    formattedValue += value[i];
                }
                
                // Update input value
                e.target.value = formattedValue;
                
                // Update card display
                let displayValue = formattedValue || '•••• •••• •••• ••••';
                document.getElementById('card-number-display').textContent = displayValue;
                
                // Detect card type
                detectCardType(value);
            });
            
            // Expiry Date Formatting
            const expiryDateInput = document.getElementById('expiryDate');
            expiryDateInput.addEventListener('input', function(e) {
                // Remove all non-digit characters
                let value = e.target.value.replace(/\D/g, '');
                
                // Format as MM/YY
                if (value.length > 0) {
                    // Handle month input
                    if (value.length === 1 && parseInt(value) > 1) {
                        // If first digit is greater than 1, prepend 0
                        value = '0' + value;
                    }
                    
                    if (value.length >= 2) {
                        // Ensure month is between 01-12
                        let month = parseInt(value.substring(0, 2));
                        if (month > 12) {
                            month = 12;
                            value = month.toString() + value.substring(2);
                        }
                        
                        // Add slash after month
                        if (value.length > 2) {
                            value = value.substring(0, 2) + '/' + value.substring(2);
                        }
                    }
                }
                
                // Update input value
                e.target.value = value;
                
                // Update card display
                let displayValue = value || 'MM/YY';
                document.getElementById('card-expiry-display').textContent = displayValue;
            });
            
            // Card Holder Name
            const cardHolderInput = document.getElementById('cardHolder');
            cardHolderInput.addEventListener('input', function(e) {
                let value = e.target.value;
                
                // Update card display
                let displayValue = value.toUpperCase() || 'JOHN DOE';
                document.getElementById('card-holder-display').textContent = displayValue;
            });
            
            // CVV Input
            const cvvInput = document.getElementById('cvv');
            cvvInput.addEventListener('focus', function() {
                document.getElementById('credit-card').classList.add('flipped');
            });
            
            cvvInput.addEventListener('blur', function() {
                document.getElementById('credit-card').classList.remove('flipped');
            });
            
            cvvInput.addEventListener('input', function(e) {
                // Remove all non-digit characters
                let value = e.target.value.replace(/\D/g, '');
                e.target.value = value;
                
                // Update card display
                document.getElementById('card-cvv-display').textContent = value || '123';
            });
            
            // Payment Method Selection
            const paymentMethods = document.querySelectorAll('.payment-method');
            paymentMethods.forEach(method => {
                method.addEventListener('click', function() {
                    paymentMethods.forEach(m => m.classList.remove('active'));
                    this.classList.add('active');
                });
            });
            
            // Form Submission
            const paymentForm = document.getElementById('payment-form');
            paymentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate form
                if (validateForm()) {
                    // Show processing overlay
                    document.getElementById('processing-overlay').classList.add('active');
                    
                    // Simulate payment processing
                    setTimeout(() => {
                        // Hide processing overlay
                        document.getElementById('processing-overlay').classList.remove('active');
                        
                        // Show success overlay
                        document.getElementById('success-overlay').classList.add('active');
                    }, 3000);
                }
            });
            
            // Continue button after successful payment
            document.getElementById('continue-btn').addEventListener('click', function() {
                // Hide success overlay
                document.getElementById('success-overlay').classList.remove('active');
                
                // Reset form
                paymentForm.reset();
                document.getElementById('card-number-display').textContent = '•••• •••• •••• ••••';
                document.getElementById('card-holder-display').textContent = 'JOHN DOE';
                document.getElementById('card-expiry-display').textContent = 'MM/YY';
                document.getElementById('card-cvv-display').textContent = '123';
                
                // You could redirect to another page here
                // window.location.href = 'thank-you.html';
            });
            
            // Card Type Detection
            function detectCardType(number) {
                const cardTypeIcon = document.getElementById('card-type-icon');
                const cardBrandIcons = document.querySelectorAll('.card-brand i');
                
                // Remove all classes
                cardTypeIcon.className = '';
                
                // Visa
                if (number.startsWith('4')) {
                    cardTypeIcon.className = 'fab fa-cc-visa';
                    cardBrandIcons.forEach(icon => icon.className = 'fab fa-cc-visa');
                    return;
                }
                
                // Mastercard
                if (/^5[1-5]/.test(number)) {
                    cardTypeIcon.className = 'fab fa-cc-mastercard';
                    cardBrandIcons.forEach(icon => icon.className = 'fab fa-cc-mastercard');
                    return;
                }
                
                // Amex
                if (/^3[47]/.test(number)) {
                    cardTypeIcon.className = 'fab fa-cc-amex';
                    cardBrandIcons.forEach(icon => icon.className = 'fab fa-cc-amex');
                    return;
                }
                
                // Discover
                if (/^6(?:011|5)/.test(number)) {
                    cardTypeIcon.className = 'fab fa-cc-discover';
                    cardBrandIcons.forEach(icon => icon.className = 'fab fa-cc-discover');
                    return;
                }
                
                // Default
                cardTypeIcon.className = 'far fa-credit-card';
                cardBrandIcons.forEach(icon => icon.className = 'far fa-credit-card');
            }
            
            // Form Validation
            function validateForm() {
                let isValid = true;
                
                // Card Holder Validation
                const cardHolder = document.getElementById('cardHolder').value.trim();
                if (cardHolder.length < 3) {
                    showError('cardHolder', 'Please enter a valid name');
                    isValid = false;
                } else {
                    clearError('cardHolder');
                }
                
                // Card Number Validation
                const cardNumber = document.getElementById('cardNumber').value.replace(/\s/g, '');
                
                if (cardNumber.length < 16 || !luhnCheck(cardNumber)) {
                    showError('cardNumber', 'Please enter a valid card number');
                    isValid = false;
                } else {
                    clearError('cardNumber');
                }
                
                // Expiry Date Validation
                const expiryDate = document.getElementById('expiryDate').value;
                if (!validateExpiryDate(expiryDate)) {
                    showError('expiryDate', 'Please enter a valid expiry date');
                    isValid = false;
                } else {
                    clearError('expiryDate');
                }
                
                // CVV Validation
                const cvv = document.getElementById('cvv').value;
                if (cvv.length < 3) {
                    showError('cvv', 'Please enter a valid CVV');
                    isValid = false;
                } else {
                    clearError('cvv');
                }
                
                return isValid;
            }
            
            // Luhn Algorithm for Card Validation
            function luhnCheck(cardNumber) {
                if (!cardNumber) return false;
                
                let sum = 0;
                let shouldDouble = false;
                
                // Loop from right to left
                for (let i = cardNumber.length - 1; i >= 0; i--) {
                    let digit = parseInt(cardNumber.charAt(i));
                    
                    if (shouldDouble) {
                        digit *= 2;
                        if (digit > 9) digit -= 9;
                    }
                    
                    sum += digit;
                    shouldDouble = !shouldDouble;
                }
                
                return (sum % 10) === 0;
            }
            
            // Validate Expiry Date
            function validateExpiryDate(expiryDate) {
                if (!expiryDate || expiryDate.length !== 5) return false;
                
                const parts = expiryDate.split('/');
                if (parts.length !== 2) return false;
                
                const month = parseInt(parts[0], 10);
                const year = parseInt('20' + parts[1], 10);
                
                if (isNaN(month) || isNaN(year)) return false;
                if (month < 1 || month > 12) return false;
                
                const now = new Date();
                const currentYear = now.getFullYear();
                const currentMonth = now.getMonth() + 1;
                
                if (year < currentYear) return false;
                if (year === currentYear && month < currentMonth) return false;
                
                return true;
            }
            
            // Show Error
            function showError(inputId, message) {
                const input = document.getElementById(inputId);
                input.classList.add('is-invalid');
                
                // Check if error message already exists
                let errorDiv = input.nextElementSibling;
                if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    input.parentNode.insertBefore(errorDiv, input.nextSibling);
                }
                
                errorDiv.textContent = message;
            }
            
            // Clear Error
            function clearError(inputId) {
                const input = document.getElementById(inputId);
                input.classList.remove('is-invalid');
                
                // Remove error message if it exists
                const errorDiv = input.nextElementSibling;
                if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                    errorDiv.remove();
                }
            }
            
            // Set random amount for demo
            const amount = (Math.random() * 900 + 100).toFixed(2);
            document.getElementById('payment-amount').textContent = amount;
            document.querySelector('.payment-amount-btn').textContent = amount;
        });
    </script>
</body>
</html>