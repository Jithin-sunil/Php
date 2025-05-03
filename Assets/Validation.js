function validateForm(formSelector, eventType = 'submit') {
    const form = document.querySelector(formSelector);
    if (!form) return;

    // Preview Functionality
    const setupPreviews = () => {
        // Photo Preview
        const photoInput = form.querySelector("[name='file_photo']");
        const photoPreview = form.querySelector("#photoPreview");
        if (photoInput && photoPreview) {
            photoInput.addEventListener('change', (e) => {
                if (e.target.files && e.target.files[0]) {
                    photoPreview.src = URL.createObjectURL(e.target.files[0]);
                    photoPreview.style.display = 'block';
                } else {
                    photoPreview.style.display = 'none';
                }
            });
        }

        // Proof Preview (File Name)
        const proofInput = form.querySelector("[name='file_proof']");
        const proofPreview = form.querySelector("#proofPreview");
        if (proofInput && proofPreview) {
            proofInput.addEventListener('change', (e) => {
                if (e.target.files && e.target.files[0]) {
                    proofPreview.textContent = `Selected: ${e.target.files[0].name}`;
                    proofPreview.style.display = 'block';
                } else {
                    proofPreview.textContent = '';
                    proofPreview.style.display = 'none';
                }
            });
        }
    };

    // Helper Functions
    const showError = (el, msg) => {
        const existingError = el.parentElement.querySelector('.error-message');
        if (existingError) existingError.remove();

        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.style.display = 'block';
        errorDiv.textContent = msg;
        el.parentElement.appendChild(errorDiv);
        el.classList.add('is-invalid');
        el.classList.remove('is-valid');
    };

    const clearError = (el) => {
        const existingError = el.parentElement.querySelector('.error-message');
        if (existingError) existingError.remove();
        el.classList.remove('is-invalid');
        el.classList.add('is-valid');
    };

    const firstLetterCapital = (str) => /^[A-Z][a-zA-Z\s]*$/.test(str);
    const isEmailValid = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    const isPasswordValid = (pwd) =>
        /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/.test(pwd);
    const isNumber = (val) => /^\d+$/.test(val);
    const isContactStartValid = (val) => /^[6-9]\d{9}$/.test(val);
    const isFileSelected = (input) => input.files && input.files.length > 0;
    const isImageFile = (input) => {
        if (!isFileSelected(input)) return false;
        const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
        return validTypes.includes(input.files[0].type);
    };
    const isPdfFile = (input) => {
        if (!isFileSelected(input)) return false;
        return input.files[0].type === 'application/pdf';
    };
    const isAtLeast18 = (dob) => {
        const today = new Date();
        const birthDate = new Date(dob);
        const age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();
        return age > 18 || (age === 18 && m >= 0 && today.getDate() >= birthDate.getDate());
    };
    const isPastDate = (dob) => new Date(dob) < new Date();
    const isPositiveNumber = (val) => !isNaN(val) && parseFloat(val) > 0;

    // Validation Definitions
    const validations = [
        // User Registration Validations
        {
            selector: "[name='txt_name']",
            check: (el) => el.value.trim() && /^[A-Za-z\s]+$/.test(el.value) && firstLetterCapital(el.value) && el.value.trim().length >= 3,
            error: "Enter a valid name (letters & spaces only, first letter capital, minimum 3 characters)."
        },
        {
            selector: "[name='txt_email']",
            check: (el) => el.value.trim() && isEmailValid(el.value) && el.value.trim().length >= 3,
            error: "Enter a valid email (minimum 3 characters)."
        },
        {
            selector: "[name='txt_password']",
            check: (el) => el.value.trim() && isPasswordValid(el.value) && el.value.trim().length >= 3,
            error: "Password must be at least 8 characters with uppercase, lowercase, number, and special character (minimum 3 characters)."
        },
        {
            selector: "[name='txt_address']",
            check: (el) => el.value.trim() && firstLetterCapital(el.value) && el.value.trim().length >= 3,
            error: "Enter a valid address (first letter capital, minimum 3 characters)."
        },
        {
            selector: "[name='txt_contact']",
            check: (el) => el.value.trim() && isNumber(el.value) && el.value.length === 10 && isContactStartValid(el.value),
            error: "Enter a valid 10-digit contact number starting with 6, 7, 8, or 9."
        },
        {
            selector: "[name='rd_gender']",
            check: (el) => [...form.querySelectorAll("[name='rd_gender']")].some(r => r.checked),
            error: "Please select a gender."
        },
        {
            selector: "[name='sel_district']",
            check: (el) => el.value.trim(),
            error: "Please select a district."
        },
        {
            selector: "[name='sel_place']",
            check: (el) => el.value.trim(),
            error: "Please select a place."
        },
        {
            selector: "[name='file_photo']",
            check: isImageFile,
            error: "Please upload a valid image file (jpg, png, gif)."
        },
        {
            selector: "[name='file_proof']",
            check: isPdfFile,
            error: "Please upload a valid PDF file."
        },
        {
            selector: "[name='txt_dob']",
            check: (el) => {
                if (!el.value.trim()) {
                    showError(el, "Please enter your Date of Birth.");
                    return false;
                }
                if (!isPastDate(el.value)) {
                    showError(el, "Date of Birth cannot be today or a future date.");
                    return false;
                }
                if (!isAtLeast18(el.value)) {
                    showError(el, "You must be at least 18 years old.");
                    return false;
                }
                return true;
            },
            error: "Invalid Date of Birth."
        },
        // Add Product Validations
        {
            selector: "[name='txt_name']",
            check: (el) => el.value.trim() && /^[A-Za-z\s]+$/.test(el.value) && firstLetterCapital(el.value) && el.value.trim().length >= 3,
            error: "Enter a valid product name (letters & spaces only, first letter capital, minimum 3 characters)."
        },
        {
            selector: "[name='txt_price']",
            check: (el) => el.value.trim() && isPositiveNumber(el.value),
            error: "Enter a valid price (greater than 0)."
        },
        {
            selector: "[name='file_photo']",
            check: isImageFile,
            error: "Please upload a valid image file (jpg, png, gif)."
        },
        {
            selector: "[name='txt_details']",
            check: (el) => el.value.trim() && firstLetterCapital(el.value) && el.value.trim().length >= 3,
            error: "Enter valid product details (first letter capital, minimum 3 characters)."
        },
        {
            selector: "[name='sel_category']",
            check: (el) => el.value.trim(),
            error: "Please select a category."
        },
        {
            selector: "[name='sel_brand']",
            check: (el) => el.value.trim(),
            error: "Please select a brand."
        }
    ];

    // Validate a single field
    const validateField = (element) => {
        const validation = validations.find(v => element.matches(v.selector));
        if (validation) {
            if (!validation.check(element)) {
                showError(element, validation.error);
                return false;
            } else {
                clearError(element);
                return true;
            }
        }
        return true;
    };

    // Validate all fields (for submit)
    const validateAllFields = () => {
        let valid = true;
        validations.forEach(({ selector, check, error }) => {
            const element = form.querySelector(selector);
            if (element && !check(element)) {
                showError(element, error);
                valid = false;
            } else if (element) {
                clearError(element);
            }
        });
        return valid;
    };

    // Attach individual field validators
    validations.forEach(({ selector }) => {
        const elements = form.querySelectorAll(selector);
        elements.forEach(element => {
            if (element.type === 'file' || element.type === 'select-one' || element.type === 'radio') {
                element.addEventListener('change', () => validateField(element));
            } else {
                element.addEventListener('input', () => validateField(element));
            }
        });
    });

    // Handle form submission
    if (eventType === 'submit') {
        form.addEventListener('submit', (e) => {
            if (!validateAllFields()) {
                e.preventDefault();
            }
        });
    }

    // Setup previews on load
    setupPreviews();
}

// Initialize on DOMContentLoaded
document.addEventListener("DOMContentLoaded", () => {
    // Set DOB constraints for User Registration
    const dobInput = document.querySelector("[name='txt_dob']");
    if (dobInput) {
        const today = new Date();
        const maxDate = today.toISOString().split('T')[0];
        const minDate = new Date(today.getFullYear() - 100, today.getMonth(), today.getDate()).toISOString().split('T')[0];
        dobInput.setAttribute('max', maxDate);
        dobInput.setAttribute('min', minDate);
    }
});