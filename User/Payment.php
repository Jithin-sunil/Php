<?php
session_start();
include("../Assets/Connection/Connection.php");
ob_start();

// Process payment if form submitted
if(isset($_POST["btn_pay"])) {
    $booking_id = $_SESSION["bid"];
    $amount = $_POST["txt_amount"];
    $payment_method = $_POST["payment_method"];
    $card_number = isset($_POST["card_number"]) ? $_POST["card_number"] : '';
    $expiry = isset($_POST["expiry"]) ? $_POST["expiry"] : '';
    $cvv = isset($_POST["cvv"]) ? $_POST["cvv"] : '';
    
    // Validate card details if card payment
    if($payment_method == 'card' && (empty($card_number) || empty($expiry) || empty($cvv))) {
        $error = "Please enter all card details";
    } else {
        // Process payment (in a real system, this would connect to a payment processor)
        $insQry = "INSERT INTO tbl_payment (booking_id, payment_amount, payment_method, payment_date, payment_status) 
                   VALUES ('$booking_id', '$amount', '$payment_method', NOW(), '1')";
        
        if($conn->query($insQry)) {
            // Update booking status to paid
            $upQry = "UPDATE tbl_booking SET booking_status = '2' WHERE booking_id = '$booking_id'";
            $conn->query($upQry);
            
            header("Location: PaymentSuccess.php");
            exit();
        } else {
            $error = "Payment processing failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment | Your Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #3a86ff;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --white: #ffffff;
            --border-radius: 8px;
            --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
        }

        .container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        /* Payment Header */
        .payment-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .payment-title {
            font-size: 2rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .payment-subtitle {
            color: var(--gray);
            font-size: 1rem;
        }

        /* Payment Layout */
        .payment-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        @media (max-width: 768px) {
            .payment-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Order Summary */
        .order-summary {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
        }

        .summary-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--light-gray);
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .summary-label {
            color: var(--gray);
        }

        .summary-value {
            font-weight: 500;
        }

        .summary-total {
            font-weight: 600;
            font-size: 1.1rem;
            margin: 1.5rem 0;
            padding-top: 1rem;
            border-top: 1px solid var(--light-gray);
        }

        /* Payment Methods */
        .payment-methods {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
        }

        .methods-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--light-gray);
        }

        .method-tabs {
            display: flex;
            border-bottom: 1px solid var(--light-gray);
            margin-bottom: 1.5rem;
        }

        .method-tab {
            padding: 0.75rem 1rem;
            cursor: pointer;
            font-weight: 500;
            color: var(--gray);
            border-bottom: 2px solid transparent;
            transition: var(--transition);
        }

        .method-tab.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        .method-content {
            display: none;
        }

        .method-content.active {
            display: block;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--light-gray);
            border-radius: var(--border-radius);
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.2);
        }

        .card-icons {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .card-icon {
            width: 40px;
            height: 25px;
            object-fit: contain;
            border: 1px solid var(--light-gray);
            border-radius: 4px;
            padding: 0.25rem;
        }

        .card-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 480px) {
            .card-details {
                grid-template-columns: 1fr;
            }
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 1rem;
        }

        .submit-btn:hover {
            background: var(--secondary);
        }

        /* Error Message */
        .error-message {
            color: var(--danger);
            background: rgba(247, 37, 133, 0.1);
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        /* Secure Info */
        .secure-info {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
            color: var(--gray);
            font-size: 0.85rem;
        }

        /* Payment Success */
        .payment-success {
            text-align: center;
            padding: 3rem 0;
        }

        .success-icon {
            font-size: 4rem;
            color: var(--success);
            margin-bottom: 1.5rem;
        }

        .success-title {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .success-message {
            color: var(--gray);
            margin-bottom: 2rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .success-btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: var(--primary);
            color: var(--white);
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .success-btn:hover {
            background: var(--secondary);
        }
    </style>
</head>
<body>
    <?php include('../User/Header.php'); ?>

    <div class="container">
        <div class="payment-header">
            <h1 class="payment-title">Secure Payment</h1>
            <p class="payment-subtitle">Complete your purchase with confidence</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="payment-layout">
            <div class="order-summary">
                <h2 class="summary-title">Order Summary</h2>
                
                <?php
                if(isset($_SESSION["bid"])) {
                    $booking_id = $_SESSION["bid"];
                    $selQry = "SELECT b.*, SUM(p.product_price * c.cart_quantity) as total 
                               FROM tbl_booking b 
                               INNER JOIN tbl_cart c ON c.booking_id = b.booking_id 
                               INNER JOIN tbl_product p ON c.product_id = p.product_id 
                               WHERE b.booking_id = '$booking_id'";
                    $res = $conn->query($selQry);
                    $row = $res->fetch_assoc();
                    
                    $subtotal = $row["total"];
                    $tax = $subtotal * 0.05;
                    $shipping = 15.00;
                    $total = $subtotal + $tax + $shipping;
                ?>
                <div class="summary-item">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value">₹<?php echo number_format($subtotal, 2); ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Tax (5%)</span>
                    <span class="summary-value">₹<?php echo number_format($tax, 2); ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Shipping</span>
                    <span class="summary-value">₹<?php echo number_format($shipping, 2); ?></span>
                </div>
                <div class="summary-item summary-total">
                    <span>Total Amount</span>
                    <span>₹<?php echo number_format($total, 2); ?></span>
                    <input type="hidden" name="txt_amount" value="<?php echo $total; ?>">
                </div>
                <?php } ?>
                
                <div class="secure-info">
                    <i class="fas fa-lock"></i>
                    <span>Your payment information is encrypted</span>
                </div>
            </div>

            <div class="payment-methods">
                <h2 class="methods-title">Payment Method</h2>
                
                <div class="method-tabs">
                    <div class="method-tab active" data-tab="card">Credit/Debit Card</div>
                    <div class="method-tab" data-tab="cod">Cash on Delivery</div>
                    <div class="method-tab" data-tab="wallet">Wallet</div>
                </div>
                
                <!-- Card Payment -->
                <div class="method-content active" id="card-method">
                    <div class="card-icons">
                        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/visa/visa-original.svg" class="card-icon" alt="Visa">
                        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mastercard/mastercard-original.svg" class="card-icon" alt="Mastercard">
                        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/apple/apple-original.svg" class="card-icon" alt="Apple Pay">
                        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg" class="card-icon" alt="Google Pay">
                    </div>
                    
                    <div class="form-group">
                        <label for="card_number" class="form-label">Card Number</label>
                        <input type="text" id="card_number" name="card_number" class="form-control" placeholder="1234 5678 9012 3456" maxlength="19">
                    </div>
                    
                    <div class="card-details">
                        <div class="form-group">
                            <label for="expiry" class="form-label">Expiry Date</label>
                            <input type="text" id="expiry" name="expiry" class="form-control" placeholder="MM/YY" maxlength="5">
                        </div>
                        
                        <div class="form-group">
                            <label for="cvv" class="form-label">CVV</label>
                            <input type="password" id="cvv" name="cvv" class="form-control" placeholder="•••" maxlength="3">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="card_name" class="form-label">Name on Card</label>
                        <input type="text" id="card_name" name="card_name" class="form-control" placeholder="John Doe">
                    </div>
                    
                    <input type="hidden" name="payment_method" value="card">
                    <button type="submit" name="btn_pay" class="submit-btn">
                        <i class="fas fa-lock"></i> Pay ₹<?php echo isset($total) ? number_format($total, 2) : '0.00'; ?>
                    </button>
                </div>
                
                <!-- COD Payment -->
                <div class="method-content" id="cod-method">
                    <div style="text-align: center; margin: 2rem 0;">
                        <i class="fas fa-truck" style="font-size: 3rem; color: var(--primary); margin-bottom: 1rem;"></i>
                        <p style="color: var(--gray); margin-bottom: 1.5rem;">Pay when your order is delivered</p>
                    </div>
                    
                    <input type="hidden" name="payment_method" value="cod">
                    <button type="submit" name="btn_pay" class="submit-btn">
                        <i class="fas fa-check-circle"></i> Confirm Order
                    </button>
                </div>
                
                <!-- Wallet Payment -->
                <div class="method-content" id="wallet-method">
                    <div style="text-align: center; margin: 2rem 0;">
                        <i class="fas fa-wallet" style="font-size: 3rem; color: var(--primary); margin-bottom: 1rem;"></i>
                        <p style="color: var(--gray); margin-bottom: 1.5rem;">Pay using your wallet balance</p>
                        
                        
                        
                    </div>
                    
                    <input type="hidden" name="payment_method" value="wallet">
                    <button type="submit" name="btn_pay" class="submit-btn" ?>>
                        <i class="fas fa-wallet"></i> Pay with Wallet
                    </button>
                </div>
            </div>
        </form>
    </div>

    <?php include('../User/Footer.php'); ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Tab switching
            $('.method-tab').click(function() {
                $('.method-tab').removeClass('active');
                $(this).addClass('active');
                
                const tabId = $(this).data('tab');
                $('.method-content').removeClass('active');
                $(`#${tabId}-method`).addClass('active');
                
                // Update payment method hidden input
                $(`#${tabId}-method input[name="payment_method"]`).val(tabId);
            });
            
            // Format card number
            $('#card_number').on('input', function() {
                let value = $(this).val().replace(/\s+/g, '');
                if (value.length > 0) {
                    value = value.match(new RegExp('.{1,4}', 'g')).join(' ');
                }
                $(this).val(value);
            });
            
            // Format expiry date
            $('#expiry').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                if (value.length > 2) {
                    value = value.substring(0, 2) + '/' + value.substring(2, 4);
                }
                $(this).val(value);
            });
            
            // Restrict CVV to numbers only
            $('#cvv').on('input', function() {
                $(this).val($(this).val().replace(/\D/g, ''));
            });
        });
    </script>
</body>
</html>
<?php ob_flush(); ?>