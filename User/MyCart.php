<?php
session_start();
include("../Assets/Connection/Connection.php");
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart | Your Store</title>
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* Header */
        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .cart-title {
            font-size: 2rem;
            font-weight: 600;
            color: var(--dark);
        }

        .continue-shopping {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .continue-shopping:hover {
            color: var(--secondary);
            text-decoration: underline;
        }

        /* Cart Layout */
        .cart-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        @media (max-width: 768px) {
            .cart-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Cart Items */
        .cart-items {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
        }

        .empty-cart {
            text-align: center;
            padding: 3rem 0;
            color: var(--gray);
        }

        .empty-cart a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .empty-cart a:hover {
            text-decoration: underline;
        }

        /* Cart Item */
        .cart-item {
            display: grid;
            grid-template-columns: 100px 1fr auto;
            gap: 1.5rem;
            padding: 1.5rem 0;
            border-bottom: 1px solid var(--light-gray);
        }

        @media (max-width: 576px) {
            .cart-item {
                grid-template-columns: 80px 1fr;
                grid-template-rows: auto auto;
            }
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 100%;
            height: auto;
            border-radius: var(--border-radius);
            object-fit: cover;
        }

        .item-details {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .item-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .item-description {
            font-size: 0.9rem;
            color: var(--gray);
            margin-bottom: 0.5rem;
        }

        .item-price {
            font-weight: 600;
            color: var(--dark);
        }

        .item-price::before {
            content: "₹";
        }

        .item-stock {
            font-size: 0.8rem;
            color: var(--danger);
            display: none;
        }

        .item-stock.visible {
            display: block;
        }

        .item-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: space-between;
        }

        @media (max-width: 576px) {
            .item-actions {
                grid-column: 1 / -1;
                flex-direction: row;
                align-items: center;
                margin-top: 1rem;
            }
        }

        /* Quantity Controls */
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quantity-btn {
            width: 32px;
            height: 32px;
            border: 1px solid var(--light-gray);
            background: var(--white);
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray);
            transition: var(--transition);
        }

        .quantity-btn:hover {
            background: var(--light-gray);
            color: var(--dark);
        }

        .quantity-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .quantity-input {
            width: 50px;
            text-align: center;
            border: 1px solid var(--light-gray);
            border-radius: 4px;
            padding: 0.5rem;
            font-size: 0.9rem;
            background: var(--light);
        }

        /* Remove Button */
        .remove-btn {
            background: var(--danger);
            color: var(--white);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
        }

        .remove-btn:hover {
            background: #d1145a;
        }

        /* Item Total */
        .item-total {
            font-weight: 600;
            color: var(--dark);
            text-align: right;
        }

        .item-total::before {
            content: "₹";
        }

        /* Cart Summary */
        .cart-summary {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
            position: sticky;
            top: 2rem;
        }

        .summary-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--light-gray);
        }

        .summary-row {
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

        .summary-value::before {
            content: "₹";
        }

        .summary-total {
            font-weight: 600;
            font-size: 1.1rem;
            margin: 1.5rem 0;
            padding-top: 1rem;
            border-top: 1px solid var(--light-gray);
        }

        /* Payment Toggle */
        .payment-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 1.5rem 0;
        }

        .toggle-label {
            font-size: 0.9rem;
            color: var(--gray);
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--gray);
            transition: var(--transition);
            border-radius: 34px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: var(--white);
            transition: var(--transition);
            border-radius: 50%;
        }

        input:checked + .toggle-slider {
            background-color: var(--primary);
        }

        input:checked + .toggle-slider:before {
            transform: translateX(26px);
        }

        /* Checkout Button */
        .checkout-btn {
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .checkout-btn:hover {
            background: var(--secondary);
        }

        .checkout-btn:disabled {
            background: var(--gray);
            cursor: not-allowed;
        }

        /* Notification */
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            color: var(--white);
            font-size: 0.9rem;
            font-weight: 500;
            z-index: 1000;
            box-shadow: var(--box-shadow);
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .notification.show {
            opacity: 1;
        }

        .notification.success {
            background: var(--success);
        }

        .notification.error {
            background: var(--danger);
        }
    </style>
</head>
<body>
    <?php include('../User/Header.php'); ?>

    <div class="container">
        <div class="cart-header">
            <h1 class="cart-title">Your Shopping Cart</h1>
            <a href="ViewProduct.php" class="continue-shopping">
                <i class="fas fa-arrow-left"></i> Continue Shopping
            </a>
        </div>

        <form method="post" class="cart-layout">
            <div class="cart-items">
                <?php
                $sel = "SELECT b.*, c.*, p.product_name, p.product_price, p.product_photo, p.product_details 
                        FROM tbl_booking b 
                        INNER JOIN tbl_cart c ON c.booking_id = b.booking_id 
                        INNER JOIN tbl_product p ON c.product_id = p.product_id 
                        WHERE b.user_id = '" . $_SESSION["uid"] . "' 
                        AND b.booking_status = '0' 
                        AND c.cart_status = 0";
                $res = $conn->query($sel);
                if ($res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        $selStock = "SELECT COALESCE(SUM(stock_qty), 0) as stock 
                                     FROM tbl_stock 
                                     WHERE product_id = '" . $row["product_id"] . "'";
                        $selBooked = "SELECT COALESCE(SUM(cart_quantity), 0) as quantity 
                                      FROM tbl_cart 
                                      WHERE product_id = '" . $row["product_id"] . "' 
                                      AND cart_status > '0'";
                        $stockRow = $conn->query($selStock)->fetch_assoc();
                        $bookedRow = $conn->query($selBooked)->fetch_assoc();
                        $availableStock = $stockRow["stock"] - $bookedRow["quantity"];
                ?>
                <div class="cart-item" data-cart-id="<?php echo $row["cart_id"]; ?>">
                    <div class="item-image-container">
                        <img src="../Assets/Files/Product/<?php echo $row["product_photo"]; ?>" 
                             alt="<?php echo htmlspecialchars($row["product_name"]); ?>" class="item-image">
                    </div>
                    <div class="item-details">
                        <div>
                            <h3 class="item-name"><?php echo htmlspecialchars($row["product_name"]); ?></h3>
                            <p class="item-description"><?php echo htmlspecialchars($row["product_details"]); ?></p>
                            <div class="item-price"><?php echo number_format($row["product_price"], 2); ?></div>
                            <div class="item-stock <?php echo $availableStock <= 5 ? 'visible' : ''; ?>">
                                Only <?php echo $availableStock; ?> left in stock!
                            </div>
                        </div>
                    </div>
                    <div class="item-actions">
                        <div class="quantity-control">
                            <button type="button" class="quantity-btn ps-decrement" 
                                    onclick="updateQuantity(this, <?php echo $row["cart_id"]; ?>, -1)" 
                                    <?php if ($row["cart_quantity"] <= 1) echo 'disabled'; ?>>
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" class="quantity-input" value="<?php echo $row["cart_quantity"]; ?>" 
                                   min="1" max="<?php echo $availableStock; ?>" 
                                   data-max="<?php echo $availableStock; ?>" readonly>
                            <button type="button" class="quantity-btn ps-increment" 
                                    onclick="updateQuantity(this, <?php echo $row["cart_id"]; ?>, 1)" 
                                    <?php if ($row["cart_quantity"] >= $availableStock) echo 'disabled'; ?>>
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="item-total">
                            <?php
                            $total = $row["product_price"] * $row["cart_quantity"];
                            echo number_format($total, 2);
                            ?>
                        </div>
                        <button type="button" class="remove-btn" 
                                onclick="removeItem(this, <?php echo $row["cart_id"]; ?>)">
                            <i class="fas fa-trash-alt"></i> Remove
                        </button>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo '<div class="empty-cart">Your cart is empty. <a href="ViewProduct.php">Start shopping!</a></div>';
                }
                ?>
            </div>

            <div class="cart-summary">
                <h2 class="summary-title">Order Summary</h2>
                <div class="summary-row">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value" id="cart-subtotal">0.00</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Tax (5%)</span>
                    <span class="summary-value" id="cart-tax">0.00</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Shipping</span>
                    <span class="summary-value" id="cart-shipping">0.00</span>
                </div>
                <div class="summary-row summary-total">
                    <span>Total</span>
                    <span id="cart-total">0.00</span>
                    <input type="hidden" id="cart-totalamt" name="carttotalamt" value="">
                </div>

                <div class="payment-toggle">
                    <span class="toggle-label">Cash on Delivery</span>
                    <label class="toggle-switch">
                        <input type="checkbox" name="cb_checkout" checked>
                        <span class="toggle-slider"></span>
                    </label>
                    <span class="toggle-label">Card Payment</span>
                </div>

                <button type="submit" class="checkout-btn" name="btn_checkout">
                    <i class="fas fa-credit-card"></i> Proceed to Checkout
                </button>
            </div>
        </form>
    </div>

    <?php include('../User/Footer.php'); ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        const taxRate = 0.05;
        const shippingRate = 15.00;
        const fadeTime = 300;

        function showNotification(message, type) {
            const notification = $(`
                <div class="notification ${type}">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                    ${message}
                </div>
            `);
            $('body').append(notification);
            notification.addClass('show');
            
            setTimeout(() => {
                notification.removeClass('show');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        function recalculateCart() {
            let subtotal = 0;
            $('.cart-item').each(function() {
                const total = parseFloat($(this).find('.item-total').text().replace('₹', '')) || 0;
                subtotal += total;
            });

            const tax = subtotal * taxRate;
            const shipping = subtotal > 0 ? shippingRate : 0;
            const total = subtotal + tax + shipping;

            $('#cart-subtotal').text(subtotal.toFixed(2));
            $('#cart-tax').text(tax.toFixed(2));
            $('#cart-shipping').text(shipping.toFixed(2));
            $('#cart-total').text(total.toFixed(2));
            $('#cart-totalamt').val(total.toFixed(2));
            $('.checkout-btn').prop('disabled', subtotal === 0);
        }

        function updateQuantity(button, cartId, change) {
            const $item = $(button).closest('.cart-item');
            const $input = $item.find('.quantity-input');
            const currentQty = parseInt($input.val());
            const newQty = currentQty + change;
            const maxQty = parseInt($input.data('max'));

            if (newQty < 1 || newQty > maxQty) {
                showNotification(newQty < 1 ? 'Minimum quantity is 1.' : `Only ${maxQty} available in stock.`, 'error');
                return;
            }

            $.ajax({
                url: `../Assets/AjaxPages/AjaxCart.php?action=Update&id=${cartId}&qty=${newQty}`,
                success: function() {
                    $input.val(newQty);
                    const price = parseFloat($item.find('.item-price').text().replace('₹', ''));
                    const linePrice = price * newQty;
                    $item.find('.item-total').text(linePrice.toFixed(2));
                    recalculateCart();
                    
                    $item.find('.ps-decrement').prop('disabled', newQty <= 1);
                    $item.find('.ps-increment').prop('disabled', newQty >= maxQty);
                    $item.find('.item-stock').toggleClass('visible', maxQty <= 5);
                    showNotification('Quantity updated successfully!', 'success');
                },
                error: function() {
                    showNotification('Error updating quantity.', 'error');
                }
            });
        }

        function removeItem(button, cartId) {
            const $item = $(button).closest('.cart-item');
            $.ajax({
                url: `../Assets/AjaxPages/AjaxCart.php?action=Delete&id=${cartId}`,
                success: function() {
                    $item.slideUp(fadeTime, function() {
                        $item.remove();
                        recalculateCart();
                        if ($('.cart-item').length === 0) {
                            $('.cart-items').html('<div class="empty-cart">Your cart is empty. <a href="ViewProduct.php">Start shopping!</a></div>');
                        }
                        showNotification('Item removed from cart.', 'success');
                    });
                },
                error: function() {
                    showNotification('Error removing item.', 'error');
                }
            });
        }

        $(document).ready(function() {
            recalculateCart();
            
            $('.toggle-switch input').on('change', function() {
                const paymentMethod = this.checked ? 'Card Payment' : 'Cash on Delivery';
                showNotification(`Payment method set to ${paymentMethod}`, 'success');
            });

            $('.cart-item').each(function() {
                const maxQty = parseInt($(this).find('.quantity-input').data('max'));
                $(this).find('.item-stock').toggleClass('visible', maxQty <= 5);
            });
        });
    </script>
</body>
</html>
<?php
if (isset($_POST["btn_checkout"])) {
    $amt = $_POST["carttotalamt"];
    $selC = "SELECT * FROM tbl_booking WHERE user_id = '" . $_SESSION["uid"] . "' AND booking_status = '0'";
    $rs = $conn->query($selC);
    if ($rs->num_rows > 0) {
        $row = $rs->fetch_assoc();
        $_SESSION["bid"] = $row["booking_id"];

        $upQry1 = "UPDATE tbl_booking SET booking_date = CURDATE(), booking_amount = '$amt', booking_status = '1' 
                   WHERE user_id = '" . $_SESSION["uid"] . "'";
        $conn->query($upQry1);

        $upQry2 = "UPDATE tbl_cart SET cart_status = '1' WHERE booking_id = '" . $row["booking_id"] . "'";
        if ($conn->query($upQry2)) {
            if (isset($_POST["cb_checkout"])) {
                header("Location: Payment.php");
            } else {
                header("Location: MyBooking.php");
            }
        }
    } else {
        echo "<script>showNotification('No active booking found. Please add items to your cart.', 'error');</script>";
    }
}
ob_flush();
?>