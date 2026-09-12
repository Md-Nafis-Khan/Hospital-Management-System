function checkText(value, message) {
    if (value.trim() == "") {
        alert(message);
        return false;
    }

    return true;
}

function checkEmail(value) {
    var pattern = /^[^ ]+@[^ ]+\.[a-z]{2,}$/i;

    if (!pattern.test(value.trim())) {
        alert("Please enter a valid email.");
        return false;
    }

    return true;
}

function checkPassword(value) {
    if (value.length < 6) {
        alert("Password must contain at least 6 characters.");
        return false;
    }

    return true;
}
