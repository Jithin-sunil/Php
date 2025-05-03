<?php
ob_start();
include('../Assets/Connection/Connection.php');
session_start();

// Fetch categories
$catQry = "SELECT * FROM tbl_category";
$catResult = $conn->query($catQry);

// Fetch brands
$brandQry = "SELECT * FROM tbl_brand";
$brandResult = $conn->query($brandQry);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Search and Filter</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Scoped styles to avoid conflicts with external templates */
        .ps-wrapper {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.5;
        }

        .ps-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Main Layout */
        .ps-main-content {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        /* Filters Sidebar */
        .ps-filters {
            flex: 0 0 260px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
        }

        .ps-filters-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 12px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }

        .ps-filters-header h2 {
            font-size: 18px;
            font-weight: 600;
            color: #222;
        }

        .ps-filters-header button {
            background: none;
            border: none;
            color: #007bff;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }

        .ps-filter-section {
            margin-bottom: 24px;
        }

        .ps-filter-section h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #222;
        }

        .ps-search-filter input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .ps-search-filter input:focus {
            outline: none;
            border-color: #007bff;
        }

        .ps-filter-list {
            max-height: 200px;
            overflow-y: auto;
            padding-right: 8px;
        }

        .ps-filter-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .ps-filter-item input[type="checkbox"] {
            margin-right: 10px;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .ps-filter-item label {
            font-size: 14px;
            color: #444;
            cursor: pointer;
            flex-grow: 1;
        }

        .ps-sort-section select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            background: #fff;
        }

        /* Products Section */
        .ps-products {
            flex: 1;
            min-width: 300px;
        }

        .ps-products-header {
            background: #fff;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ps-products-header h2 {
            font-size: 18px;
            font-weight: 600;
            color: #222;
        }

        .ps-products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        /* Product Card */
        .ps-product-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .ps-product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }

        .ps-product-image {
            position: relative;
            padding-top: 100%; /* 1:1 Aspect Ratio */
            background: #f5f5f5;
            overflow: hidden;
        }

        .ps-product-image img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .ps-product-card:hover .ps-product-image img {
            transform: scale(1.05);
        }

        .ps-product-actions {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .ps-product-card:hover .ps-product-actions {
            opacity: 1;
        }

        .ps-action-btn {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            cursor: pointer;
            border: none;
            font-size: 16px;
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .ps-action-btn:hover {
            transform: scale(1.1);
        }

        .ps-action-btn.ps-view {
            color: #007bff;
        }

        .ps-action-btn.ps-wishlist {
            color: #ff527b;
        }

        .ps-action-btn.ps-cart {
            color: #28a745;
        }

        .ps-product-details {
            padding: 16px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .ps-product-title {
            font-size: 15px;
            font-weight: 500;
            margin-bottom: 8px;
            color: #222;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            height: 42px;
        }

        .ps-product-rating {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .ps-rating-stars {
            display: flex;
            margin-right: 8px;
        }

        .ps-rating-stars i {
            color: #ffc107;
            font-size: 12px;
        }

        .ps-product-rating span {
            font-size: 12px;
            color: #666;
        }

        .ps-product-meta {
            font-size: 13px;
            color: #666;
            margin-bottom: 8px;
        }

        .ps-product-price {
            margin-top: auto;
            font-size: 16px;
            font-weight: 600;
            color: #222;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .ps-original-price {
            text-decoration: line-through;
            color: #888;
            font-weight: 400;
            font-size: 14px;
        }

        .ps-discount {
            color: #28a745;
            font-size: 13px;
            font-weight: 500;
        }

        .ps-out-of-stock {
            background: rgba(255,0,0,0.1);
            color: #dc3545;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            margin-top: 10px;
            text-align: center;
        }

        .ps-product-footer {
            padding: 12px 16px;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
        }

        .ps-product-footer button {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease, color 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .ps-product-footer .ps-wishlist-btn {
            background: #fff;
            color: #ff527b;
            border: 1px solid #ff527b;
        }

        .ps-product-footer .ps-wishlist-btn:hover {
            background: #ff527b;
            color: #fff;
        }

        .ps-product-footer .ps-cart-btn {
            background: #28a745;
            color: #fff;
        }

        .ps-product-footer .ps-cart-btn:hover {
            background: #218838;
        }

        .ps-product-footer .ps-cart-btn:disabled {
            background: #ccc;
            color: #666;
            cursor: not-allowed;
            opacity: 0.6;
        }

        /* Loading and No Results */
        .ps-loading {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .ps-loading img {
            width: 60px;
            height: 60px;
        }

        .ps-no-results {
            background: #fff;
            padding: 40px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            font-size: 16px;
            color: #666;
        }

        /* Notification */
        .ps-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 12px 20px;
            border-radius: 6px;
            color: #fff;
            font-size: 14px;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .ps-notification.success {
            background: #28a745;
        }

        .ps-notification.error {
            background: #dc3545;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .ps-main-content {
                flex-direction: column;
            }
            .ps-filters {
                flex: 0 0 100%;
            }
        }

        @media (max-width: 768px) {
            .ps-products-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }

        @media (max-width: 480px) {
            .ps-products-grid {
                grid-template-columns: 1fr;
            }
            .ps-product-footer {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <?php include('../User/Header.php'); ?>

    <div class="ps-wrapper">
        <div class="ps-container">
            <div class="ps-main-content">
                <!-- Filters Sidebar -->
                <div class="ps-filters">
                    <div class="ps-filters-header">
                        <h2>Filters</h2>
                        <button id="clear-filters">Clear All</button>
                    </div>

                    <!-- Search Filter -->
                    <div class="ps-filter-section ps-search-filter">
                        <h3>Search Products</h3>
                        <input type="text" id="txt_name" placeholder="Enter product name" class="product_check">
                    </div>

                    <!-- Categories Filter -->
                    <div class="ps-filter-section">
                        <h3>Categories</h3>
                        <div class="ps-filter-list">
                            <?php while ($catRow = $catResult->fetch_assoc()) { ?>
                            <div class="ps-filter-item">
                                <input type="checkbox" id="cat_<?php echo $catRow['category_id']; ?>" 
                                       class="product_check" name="categories[]" 
                                       value="<?php echo $catRow['category_id']; ?>">
                                <label for="cat_<?php echo $catRow['category_id']; ?>">
                                    <?php echo htmlspecialchars($catRow['category_name']); ?>
                                </label>
                            </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Brands Filter -->
                    <div class="ps-filter-section">
                        <h3>Brands</h3>
                        <div class="ps-filter-list">
                            <?php while ($brandRow = $brandResult->fetch_assoc()) { ?>
                            <div class="ps-filter-item">
                                <input type="checkbox" id="brand_<?php echo $brandRow['brand_id']; ?>" 
                                       class="product_check" name="brands[]" 
                                       value="<?php echo $brandRow['brand_id']; ?>">
                                <label for="brand_<?php echo $brandRow['brand_id']; ?>">
                                    <?php echo htmlspecialchars($brandRow['brand_name']); ?>
                                </label>
                            </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Sort Options -->
                    <div class="ps-filter-section ps-sort-section">
                        <h3>Sort By</h3>
                        <select id="sort" class="product_check">
                            <option value="name_asc">Name (A-Z)</option>
                            <option value="name_desc">Name (Z-A)</option>
                            <option value="price_asc">Price (Low to High)</option>
                            <option value="price_desc">Price (High to Low)</option>
                            <option value="rating_desc">Rating (High to Low)</option>
                        </select>
                    </div>
                </div>

                <!-- Products Section -->
                <div class="ps-products">
                    <div class="ps-products-header">
                        <h2>Products</h2>
                        <div class="view-options">
                            <i class="fas fa-th active"></i>
                            <i class="fas fa-list-ul"></i>
                        </div>
                    </div>

                    <div id="result" class="ps-products-grid">
                        <!-- Products loaded via AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../User/Footer.php'); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Filter products
        function productCheck() {
            $("#result").html('<div class="ps-loading"><img src="../Assets/Template/Search/loader.gif" alt="Loading..."></div>');

            var category = get_filter_text('cat');
            var brand = get_filter_text('brand');
            var name = document.getElementById('txt_name').value;
            var sort = document.getElementById('sort').value;

            $.ajax({
                url: "../Assets/AjaxPages/AjaxSearchProduct.php",
                data: {
                    action: 'data',
                    category: category.join(','),
                    brand: brand.join(','),
                    name: name,
                    sort: sort
                },
                success: function(response) {
                    $("#result").html(response);
                },
                error: function() {
                    $("#result").html('<div class="ps-no-results">Error loading products. Please try again.</div>');
                }
            });
        }

        // Get filter values
        function get_filter_text(prefix) {
            var filterData = [];
            $('input[id^="' + prefix + '_"]:checked').each(function() {
                filterData.push($(this).val());
            });
            return filterData;
        }

        // Add to Cart
        function addToCart(productId) {
            $.ajax({
                url: "../Assets/AjaxPages/AjaxAddToCart.php",
                data: {
                    action: 'add',
                    product_id: productId,
                    quantity: 1
                },
                success: function(response) {
                    showNotification(response, 'success');
                },
                error: function() {
                    showNotification('Error adding product to cart.', 'error');
                }
            });
        }

        // Add to Wishlist
        function addToWishlist(productId) {
            $.ajax({
                url: "../Assets/AjaxPages/AjaxAddToWishlist.php",
                data: {
                    action: 'add',
                    product_id: productId
                },
                success: function(response) {
                    showNotification(response, 'success');
                },
                error: function() {
                    showNotification('Error adding product to wishlist.', 'error');
                }
            });
        }

        // Show notification
        function showNotification(message, type) {
            var notification = document.createElement('div');
            notification.className = 'ps-notification ' + type;
            notification.innerHTML = message;

            document.body.appendChild(notification);

            setTimeout(function() {
                notification.style.opacity = '0';
                notification.style.transition = 'opacity 0.5s ease';
                setTimeout(function() {
                    document.body.removeChild(notification);
                }, 500);
            }, 3000);
        }

        // Clear filters
        document.getElementById('clear-filters').addEventListener('click', function() {
            document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
                checkbox.checked = false;
            });
            document.getElementById('txt_name').value = '';
            document.getElementById('sort').value = 'name_asc';
            productCheck();
        });

        // Initialize
        $(document).ready(function() {
            productCheck();
            $('#txt_name').on('keyup', productCheck);
            $('#sort').on('change', productCheck);
            $('.product_check').on('change', productCheck);
        });
    </script>
</body>
</html>
<?php ob_flush(); ?>