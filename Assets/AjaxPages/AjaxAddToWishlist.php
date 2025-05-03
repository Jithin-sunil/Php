<?php
include("../Connection/Connection.php");
session_start();

if (isset($_GET["action"]) && $_GET["action"] == "add" && isset($_SESSION['uid'])) {
    $product_id = $conn->real_escape_string($_GET['product_id']);
    $user_id = $_SESSION['uid'];

    // Check if product is already in wishlist
    $checkQry = "SELECT wishlist_id FROM tbl_wishlist WHERE product_id = '$product_id' AND user_id = '$user_id'";
    $checkResult = $conn->query($checkQry);

    if ($checkResult->num_rows > 0) {
        echo "Product is already in your wishlist.";
    } else {
        // Insert new wishlist item
        $insertQry = "INSERT INTO tbl_wishlist (product_id, user_id, wishlist_date) 
                      VALUES ('$product_id', '$user_id', NOW())";
        if ($conn->query($insertQry)) {
            echo "Added to wishlist successfully.";
        } else {
            echo "Error adding to wishlist.";
        }
    }
} else {
    echo "Please log in to add items to wishlist.";
}
?>