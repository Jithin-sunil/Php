<?php
ob_start();
session_start();
include("../Assets/Connection/Connection.php");

// Get cart_id from URL
$cart_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Fetch bill details
$selQry = "SELECT b.booking_id, b.booking_date, c.cart_id, c.cart_quantity, p.product_name, p.product_price, k.shop_name, k.shop_contact, k.shop_email, u.user_name 
           FROM tbl_booking b 
           JOIN tbl_cart c ON c.booking_id = b.booking_id 
           JOIN tbl_product p ON p.product_id = c.product_id 
           JOIN tbl_shop k ON k.shop_id = p.shop_id 
           JOIN tbl_user u ON b.user_id = u.user_id 
           WHERE c.cart_id = '$cart_id' AND b.user_id = '" . $_SESSION["uid"] . "'";
$result = $conn->query($selQry);

// Group products by booking_id
$bill_data = [];
if ($row = $result->fetch_assoc()) {
    $bill_data = $row; // Single cart item for this bill
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f4f4f4;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .invoice-header {
            text-align: center;
            border-bottom: 2px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .invoice-header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .invoice-details div {
            width: 45%;
        }
        .invoice-details h3 {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .invoice-details p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
        }
        .total-row {
            font-weight: bold;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
        @media print {
            body {
                background: #fff;
            }
            .invoice-container {
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h1>Invoice</h1>
            <p>Booking ID: <?php echo $bill_data['booking_id'] ?? 'N/A'; ?></p>
            <p>Date: <?php echo date('d-m-Y', strtotime($bill_data['booking_date'] ?? date('Y-m-d'))); ?></p>
        </div>

        <div class="invoice-details">
            <div>
                <h3>Bill To</h3>
                <p><strong>Name:</strong> <?php echo $bill_data['user_name'] ?? 'N/A'; ?></p>
            </div>
            <div>
                <h3>From</h3>
                <p><strong>Shop:</strong> <?php echo $bill_data['shop_name'] ?? 'N/A'; ?></p>
                <p><strong>Contact:</strong> <?php echo $bill_data['shop_contact'] ?? 'N/A'; ?></p>
                <p><strong>Email:</strong> <?php echo $bill_data['shop_email'] ?? 'N/A'; ?></p>
            </div>
        </div>

        <table>
            <tr>
                <th>Sl No</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            <?php
            if ($bill_data) {
                $sl_no = 1;
                $total = $bill_data['cart_quantity'] * $bill_data['product_price'];
            ?>
            <tr>
                <td><?php echo $sl_no; ?></td>
                <td><?php echo $bill_data['product_name']; ?></td>
                <td><?php echo $bill_data['cart_quantity']; ?></td>
                <td><?php echo $bill_data['product_price']; ?></td>
                <td><?php echo $total; ?></td>
            </tr>
            <tr class="total-row">
                <td colspan="4">Grand Total</td>
                <td><?php echo $total; ?></td>
            </tr>
            <?php } else { ?>
            <tr>
                <td colspan="5">No items found for this bill.</td>
            </tr>
            <?php } ?>
        </table>

        <div class="footer">
            <p>Thank you for your purchase!</p>
            <p>Generated on <?php echo date('d-m-Y H:i:s'); ?></p>
        </div>
    </div>
</body>
<?php ob_flush(); ?>
</html>