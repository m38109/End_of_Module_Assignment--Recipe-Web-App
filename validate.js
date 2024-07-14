function validateForm() {
    var form = document.forms["registerForm"];
    var username = form["username"].value;
    var email = form["email"].value;
    var password = form["password"].value;
    
    // Clear previous error messages
    var errorElements = document.getElementsByClassName("error-message");
    while (errorElements[0]) {
        errorElements[0].parentNode.removeChild(errorElements[0]);
    }

    var valid = true;

    var usernamePattern = /^[a-zA-Z0-9]*$/;
    if (!usernamePattern.test(username)) {
        showError(form["username"], "Username can only contain letters and numbers.");
        valid = false;
    }

    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        showError(form["email"], "Invalid email format.");
        valid = false;
    }

    if (password.length < 8) {
        showError(form["password"], "Password must be at least 8 characters long.");
        valid = false;
    }

    return valid;
}

function showError(inputElement, message) {
    var errorElement = document.createElement("div");
    errorElement.className = "error-message";
    errorElement.innerText = message;
    inputElement.parentNode.insertBefore(errorElement, inputElement.nextSibling);
}