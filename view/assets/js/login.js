var loginForm = document.getElementById("login-form");
var registerForm = document.getElementById("register-form");

if (document.getElementById("show-register")) {
    document.getElementById("show-register").onclick = function (event) {
        event.preventDefault();
        loginForm.style.display = "none";
        registerForm.style.display = "block";
    };
}

if (document.getElementById("show-login")) {
    document.getElementById("show-login").onclick = function (event) {
        event.preventDefault();
        registerForm.style.display = "none";
        loginForm.style.display = "block";
    };
}

loginForm.onsubmit = function () {
    var email = document.getElementById("login-email").value;
    var password = document.getElementById("login-password").value;

    if (!checkText(email, "Please enter your email.")) return false;
    if (!checkEmail(email)) return false;
    if (!checkText(password, "Please enter your password.")) return false;

    return true;
};

var usernameAvailable = true;
var emailAvailable = true;

function checkRegistrationUser() {
    var username = document.getElementById("register-username").value.trim();
    var email = document.getElementById("register-email").value.trim();

    if (username == "" && email == "") return;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../../controller/auth/check_user.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var data = JSON.parse(xhr.responseText);

            usernameAvailable = !data.username_exists;
            emailAvailable = !data.email_exists;

            document.getElementById("username-status").innerHTML = data.username_exists ? "Username already exists." : "";
            document.getElementById("email-status").innerHTML = data.email_exists ? "Email already exists." : "";
        }
    };

    xhr.send("username=" + encodeURIComponent(username) + "&email=" + encodeURIComponent(email));
}

document.getElementById("register-username").onblur = checkRegistrationUser;
document.getElementById("register-email").onblur = checkRegistrationUser;

registerForm.onsubmit = function () {
    var username = document.getElementById("register-username").value;
    var name = document.getElementById("register-name").value;
    var email = document.getElementById("register-email").value;
    var role = document.getElementById("register-role").value;
    var password = document.getElementById("register-password").value;
    var confirmPassword = document.getElementById("confirm-password").value;

    if (!checkText(username, "Please enter a username.")) return false;
    if (username.length < 3) {
        alert("Username must contain at least 3 characters.");
        return false;
    }
    if (!checkText(name, "Please enter your full name.")) return false;
    if (!checkEmail(email)) return false;
    if (role == "") {
        alert("Please select a role.");
        return false;
    }
    if (!checkPassword(password)) return false;
    if (password != confirmPassword) {
        alert("Passwords do not match.");
        return false;
    }
    if (!usernameAvailable || !emailAvailable) {
        alert("Please choose a different username or email.");
        return false;
    }

    return true;
};
