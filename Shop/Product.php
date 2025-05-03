<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .form-title {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .-field::after {
            content: " *";
            color: red;
        }
        .preview-image {
            max-width: 150px;
            max-height: 150px;
            margin-top: 10px;
            display: none;
        }
        .preview-text {
            margin-top: 10px;
            display: none;
            font-size: 14px;
        }
        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            display: none;
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
        <div class="form-container">
            <h2 class="form-title">Add New Product</h2>
            <form id="RegistrationForm" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="product_name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="txt_name" name="txt_name" >
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="text" class="form-control" id="txt_price" name="txt_price" step="0.01" >
                </div>

                <div class="mb-3">
                    <label for="photo" class="form-label">Product Photo</label>
                    <input type="file" class="form-control" id="file_photo" name="file_photo" accept="image/*" >
                    <img id="photoPreview" class="preview-image" src="#" alt="Photo Preview">

                </div>

                <div class="mb-3">
                    <label for="details" class="form-label">Product Details</label>
                    <textarea class="form-control" id="details" name="txt_details" rows="4" ></textarea>
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-select" id="sel_category" name="sel_category" >
                        <option value="">Select Category</option>
                       
                    </select>
                </div>

                <div class="mb-3">
                    <label for="brand" class="form-label">Brand</label>
                    <select class="form-select" id="sel_brand" name="sel_brand" >
                        <option value="">Select Brand</option>
                       
                    </select>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </div>
            </form>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Assets/Validation.js"></script>
    <script>
        // Initialize form validation
        validateForm('#RegistrationForm', 'submit');
        validateForm('#RegistrationForm', 'input');

      
    </script>
</body>
</html>