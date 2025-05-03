<?php
// Include database connection
include("../Connection/Connection.php");

// Check for AJAX action parameter
if (isset($_GET["action"])) {
    // Base SQL query with joins for product, category, brand, and stock
    $sqlQry = "SELECT p.*, c.category_name, b.brand_name, COALESCE(s.stock_qty, 0) as stock_qty 
               FROM tbl_product p 
               INNER JOIN tbl_category c ON p.category_id = c.category_id 
               INNER JOIN tbl_brand b ON p.brand_id = b.brand_id 
               LEFT JOIN tbl_stock s ON p.product_id = s.product_id 
               WHERE TRUE";

    // Filter by category
    if (isset($_GET["category"]) && $_GET["category"] != "") {
        $category = $conn->real_escape_string($_GET["category"]);
        $sqlQry .= " AND p.category_id IN ($category)";
    }

    // Filter by brand
    if (isset($_GET["brand"]) && $_GET["brand"] != "") {
        $brand = $conn->real_escape_string($_GET["brand"]);
        $sqlQry .= " AND p.brand_id IN ($brand)";
    }

    // Search by product name
    if (isset($_GET["name"]) && $_GET["name"] != "") {
        $name = $conn->real_escape_string($_GET["name"]);
        $sqlQry .= " AND p.product_name LIKE '%$name%'";
    }

    // Sorting
    if (isset($_GET["sort"]) && $_GET["sort"] != "") {
        $sort = $_GET["sort"];
        if ($sort == "price_asc") {
            $sqlQry .= " ORDER BY p.product_price ASC";
        } else if ($sort == "price_desc") {
            $sqlQry .= " ORDER BY p.product_price DESC";
        } else if ($sort == "name_desc") {
            $sqlQry .= " ORDER BY p.product_name DESC";
        } else if ($sort == "rating_desc") {
            $sqlQry .= " ORDER BY (SELECT AVG(user_rating) FROM tbl_review WHERE product_id = p.product_id) DESC";
        } else {
            $sqlQry .= " ORDER BY p.product_name ASC";
        }
    } else {
        $sqlQry .= " ORDER BY p.product_name ASC";
    }

    // Execute query
    $result = $conn->query($sqlQry);

    // Check for results
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Fetch average rating and review count
            $ratingQry = "SELECT AVG(user_rating) as avg_rating, COUNT(*) as review_count 
                          FROM tbl_review WHERE product_id = '" . $row['product_id'] . "'";
            $ratingResult = $conn->query($ratingQry);
            $ratingRow = $ratingResult->fetch_assoc();
            $rating = $ratingRow['avg_rating'] ? round($ratingRow['avg_rating']) : 5;
            $reviewCount = $ratingRow['review_count'] ? $ratingRow['review_count'] : 0;

            // Calculate discount percentage
            $discountPercent = 0;
            if (isset($row['product_original_price']) && $row['product_original_price'] > $row['product_price']) {
                $discountPercent = round(100 - ($row['product_price'] / $row['product_original_price'] * 100));
            }
?>
            <div class="ps-product-card">
                <div class="ps-product-image">
                    <img src="../Assets/Files/Product/<?php echo $row['product_photo']; ?>" 
                         alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                    <div class="ps-product-actions">
                        <button class="ps-action-btn ps-view" 
                                onclick="window.location.href='ProductDetails.php?id=<?php echo $row['product_id']; ?>'">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="ps-action-btn ps-wishlist" 
                                onclick="addToWishlist(<?php echo $row['product_id']; ?>)">
                            <i class="fas fa-heart"></i>
                        </button>
                        <?php if ($row['stock_qty'] > 0) { ?>
                        <button class="ps-action-btn ps-cart" 
                                onclick="addToCart(<?php echo $row['product_id']; ?>)">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                        <?php } ?>
                    </div>
                </div>
                <div class="ps-product-details">
                    <h3 class="ps-product-title"><?php echo htmlspecialchars($row['product_name']); ?></h3>
                    <div class="ps-product-rating">
                        <div class="ps-rating-stars">
                            <?php
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                            }
                            ?>
                        </div>
                        <span>(<?php echo $reviewCount; ?>)</span>
                    </div>
                    <div class="ps-product-meta">
                        <?php echo htmlspecialchars($row['category_name']); ?> - 
                        <?php echo htmlspecialchars($row['brand_name']); ?>
                    </div>
                    <div class="ps-product-price">
                        <span>$<?php echo number_format($row['product_price'], 2); ?></span>
                        <?php if ($discountPercent > 0) { ?>
                        <span class="ps-original-price">
                            $<?php echo number_format($row['product_original_price'], 2); ?>
                        </span>
                        <span class="ps-discount"><?php echo $discountPercent; ?>% off</span>
                        <?php } ?>
                    </div>
                    <?php if ($row['stock_qty'] <= 0) { ?>
                    <div class="ps-out-of-stock">Out of Stock</div>
                    <?php } ?>
                </div>
                <div class="ps-product-footer">
                    <button class="ps-wishlist-btn" 
                            onclick="addToWishlist(<?php echo $row['product_id']; ?>)">
                        <i class="far fa-heart"></i> Wishlist
                    </button>
                    <?php if ($row['stock_qty'] > 0) { ?>
                    <button class="ps-cart-btn" 
                            onclick="addToCart(<?php echo $row['product_id']; ?>)">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                    <?php } else { ?>
                    <button class="ps-cart-btn" disabled>
                        <i class="fas fa-shopping-cart"></i> Out of Stock
                    </button>
                    <?php } ?>
                </div>
            </div>
<?php
        }
    } else {
        echo '<div class="ps-no-results">No products found.</div>';
    }
}
?>