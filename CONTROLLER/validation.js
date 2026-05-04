/**
 * JavaScript Form Validation for e_dossier
 * Handles required fields and common validation logic
 */

/**
 * Validates that specific fields in a form are not empty
 * @param {string} formId - The ID of the form to validate
 * @param {Array} fieldNames - Array of field names to check
 * @returns {boolean} - True if valid, false otherwise
 */
function validateRequiredFields(formId, fieldNames) {
    const form = document.querySelector(`form[action*="${formId}"]`) || document.forms[0];
    let isValid = true;
    let firstErrorElement = null;

    fieldNames.forEach(name => {
        const input = form.querySelector(`[name="${name}"]`);
        if (input) {
            const value = input.value.trim();
            if (value === "") {
                isValid = false;
                input.classList.add("is-invalid");
                if (!firstErrorElement) firstErrorElement = input;
                
                // Add error message if not present
                let feedback = input.parentNode.querySelector(".invalid-feedback");
                if (!feedback) {
                    feedback = document.createElement("div");
                    feedback.className = "invalid-feedback";
                    feedback.innerText = "This field is required.";
                    input.parentNode.appendChild(feedback);
                }
            } else {
                input.classList.remove("is-invalid");
            }
        }
    });

    if (firstErrorElement) {
        firstErrorElement.focus();
    }

    return isValid;
}

/**
 * Specifically for Sign Up form
 */
function validateSignUpForm() {
    const requiredFields = ['name', 'email', 'password'];
    let isValid = validateRequiredFields('UserController.php?action=add', requiredFields);

    // Email validation
    const emailInput = document.querySelector('input[name="email"]');
    if (emailInput && emailInput.value) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(emailInput.value)) {
            emailInput.classList.add("is-invalid");
            let feedback = emailInput.parentNode.querySelector(".invalid-feedback");
            if (feedback) feedback.innerText = "Please enter a valid email address.";
            isValid = false;
        }
    }

    // Password confirmation check
    const password = document.getElementById('psw-input');
    const confirmPassword = document.querySelector('input[type="password"]:not([name="password"])');
    if (password && confirmPassword && password.value !== confirmPassword.value) {
        confirmPassword.classList.add("is-invalid");
        let feedback = confirmPassword.parentNode.querySelector(".invalid-feedback");
        if (!feedback) {
            feedback = document.createElement("div");
            feedback.className = "invalid-feedback";
            feedback.innerText = "Passwords do not match.";
            confirmPassword.parentNode.appendChild(feedback);
        } else {
            feedback.innerText = "Passwords do not match.";
        }
        isValid = false;
    }

    return isValid;
}

/**
 * Specifically for Sign In form
 */
function validateSignInForm() {
    const requiredFields = ['email', 'password'];
    return validateRequiredFields('UserController.php?action=login', requiredFields);
}
