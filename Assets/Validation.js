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

    const validationHandler = (e) => {
        let valid = true;

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
            valid = false;
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

        // Validation Checks
        const validations = [
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
            }
        ];

        // Clear all previous errors
        form.querySelectorAll('.form-control, .form-check-input').forEach(el => clearError(el));

        // Perform validations
        validations.forEach(({ selector, check, error }) => {
            const element = form.querySelector(selector);
            if (element && !check(element)) {
                showError(element, error);
            } else if (element) {
                clearError(element);
            }
        });

        if (!valid && e.type === 'submit') {
            e.preventDefault();
        }
    };

    // Attach event listener based on eventType
    form.addEventListener(eventType, validationHandler);

    // Setup previews on load
    setupPreviews();
}

// Initialize on DOMContentLoaded
document.addEventListener("DOMContentLoaded", () => {
    // Set DOB constraints
    const dobInput = document.querySelector("[name='txt_dob']");
    if (dobInput) {
        const today = new Date();
        const maxDate = today.toISOString().split('T')[0];
        const minDate = new Date(today.getFullYear() - 100, today.getMonth(), today.getDate()).toISOString().split('T')[0];
        dobInput.setAttribute('max', maxDate);
        dobInput.setAttribute('min', minDate);
    }
});