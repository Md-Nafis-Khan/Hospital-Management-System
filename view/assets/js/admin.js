var userForm = document.getElementById("user-form");

userForm.onsubmit = function () {
    var name = document.getElementById("new-user-name").value;
    var email = document.getElementById("new-user-email").value;
    var password = document.getElementById("new-user-password").value;
    var role = document.getElementById("new-user-role").value;

    if (!checkText(name, "Please enter the user's name.")) {
        return false;
    }

    if (!checkEmail(email)) {
        return false;
    }

    if (!checkPassword(password)) {
        return false;
    }

    if (role == "") {
        alert("Please select a role.");
        return false;
    }

    return true;
};

function validateUserUpdate(form) {
    var name = form.elements["name"].value;
    var email = form.elements["email"].value;

    if (!checkText(name, "Please enter the user's name.")) {
        return false;
    }

    if (!checkEmail(email)) {
        return false;
    }

    alert("User information is ready to update.");
    return confirm("Do you want to save these changes?");
}

function deleteUser(id) {
    alert("You are about to delete user ID " + id + ".");
    if (!confirm("Are you sure you want to delete this user?")) {
        return;
    }

    var form = document.createElement("form");
    form.method = "post";
    form.action = "../../controller/admin/delete_user.php";

    var input = document.createElement("input");
    input.type = "hidden";
    input.name = "user_id";
    input.value = id;

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}

function reviewLeave(action) {
    alert("You selected: " + action + " leave request.");
    return confirm("Are you sure you want to " + action + " this leave request?");
}
