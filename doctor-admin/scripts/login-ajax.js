var ENDPOINT = "app/controllers/AuthController.php";

var loginForm = document.getElementById("login-form");
var registerForm = document.getElementById("register-form");

document.getElementById("show-register").onclick = function (event) {
  event.preventDefault();
  loginForm.style.display = "none";
  registerForm.style.display = "block";
};

document.getElementById("show-login").onclick = function (event) {
  event.preventDefault();
  registerForm.style.display = "none";
  loginForm.style.display = "block";
};

loginForm.onsubmit = function (event) {
  event.preventDefault();
  var data = new FormData();
  data.append("email", document.getElementById("login-email").value);
  data.append("password", document.getElementById("login-password").value);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", ENDPOINT + "?action=login", true);
  xhr.onload = function () {
    var result = JSON.parse(xhr.responseText);
    document.getElementById("login-message").innerHTML = result.message;
    if (result.success) window.location.href = "index.php?page=" + result.role;
  };
  xhr.send(data);
};

registerForm.onsubmit = function (event) {
  event.preventDefault();
  var data = new FormData();
  data.append("name", document.getElementById("register-name").value);
  data.append("email", document.getElementById("register-email").value);
  data.append("role", document.getElementById("register-role").value);
  data.append("password", document.getElementById("register-password").value);
  data.append(
    "confirm_password",
    document.getElementById("confirm-password").value,
  );

  var xhr = new XMLHttpRequest();
  xhr.open("POST", ENDPOINT + "?action=register", true);
  xhr.onload = function () {
    var result = JSON.parse(xhr.responseText);
    document.getElementById("register-message").innerHTML = result.message;
    if (result.success) {
      registerForm.reset();
      document.getElementById("show-login").click();
    }
  };
  xhr.send(data);
};
