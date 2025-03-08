document.getElementById("createAccountForm").addEventListener("submit", function(event) {
    event.preventDefault();

    const userName = document.getElementById("userName").value;
    const Password = document.getElementById("Password").value;
    const confirmPassword = document.getElementById("confirm-password").value;
    const usernameError = document.getElementById("usernameError");
    const passwordError = document.getElementById("passwordError");

    // Username validation: only letters and numbers
    const usernameRegex = /^[a-zA-Z0-9]+$/;
    if (!usernameRegex.test(userName)) {
        usernameError.textContent = "User name can only contain letters and numbers.";
        usernameError.classList.remove("hidden");
    } else {
        usernameError.classList.add("hidden");
    }

    // Password validation: at least 8 characters, combination of uppercase, lowercase, numbers, and symbols
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    if (!passwordRegex.test(Password)) {
        passwordError.textContent = "Password should be a combination of uppercase letters, lowercase letters, numbers, and symbols, and at least 8 characters long.";
        passwordError.classList.remove("hidden");
    } else {
        passwordError.classList.add("hidden");
    }

    // Confirm Password validation: match the password
    if (Password !== confirmPassword) {
        confirmPasswordError.textContent = "Passwords do not match.";
        confirmPasswordError.classList.remove("hidden");
    } else {
        confirmPasswordError.classList.add("hidden");
    }

    // If all validations pass, proceed with form submission
    if (
        usernameRegex.test(userName) &&
        passwordRegex.test(Password) &&
        Password === confirmPassword
    ) {
        this.submit();
    }
});
