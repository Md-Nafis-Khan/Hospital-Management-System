var ENDPOINT = "app/controllers/AdminController.php";

function statusBadge(status) {
  return '<span class="status ' + status + '">' + status + "</span>";
}

function loadAdminData() {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", ENDPOINT + "?action=admin_data", true);
  xhr.onload = function () {
    var result = JSON.parse(xhr.responseText);
    document.getElementById("user-count").innerHTML = result.users.length;
    document.getElementById("appointment-count").innerHTML = result.appointments;
    document.getElementById("leave-count").innerHTML = result.pending_leaves;

    var leaveList = document.getElementById("leave-list");
    leaveList.innerHTML = "";
    for (var i = 0; i < result.leaves.length; i++) {
      var leave = result.leaves[i];
      var action = "-";
      if (leave.status == "pending") {
        action = '<div class="inline">' +
          '<button onclick="reviewLeave(' + leave.id + ', \'approved\')">Approve</button>' +
          '<button class="danger" onclick="reviewLeave(' + leave.id + ', \'rejected\')">Reject</button>' +
          "</div>";
      }
      leaveList.innerHTML += "<tr><td>" + leave.doctor_name + "</td><td>" + leave.start_date + " to " + leave.end_date + "</td><td>" + leave.reason + "</td><td>" + statusBadge(leave.status) + "</td><td>" + action + "</td></tr>";
    }
    if (result.leaves.length == 0) leaveList.innerHTML = '<tr><td colspan="5">No leave applications yet.</td></tr>';

    var userList = document.getElementById("user-list");
    userList.innerHTML = "";
    for (var j = 0; j < result.users.length; j++) {
      var user = result.users[j];
      userList.innerHTML += "<tr>" +
        '<td><input type="text" id="name-' + user.id + '" value="' + user.name + '"></td>' +
        '<td><input type="email" id="email-' + user.id + '" value="' + user.email + '"></td>' +
        '<td><select id="role-' + user.id + '">' +
        '<option value="patient"' + (user.role == "patient" ? " selected" : "") + ">Patient</option>" +
        '<option value="doctor"' + (user.role == "doctor" ? " selected" : "") + ">Doctor</option>" +
        '<option value="admin"' + (user.role == "admin" ? " selected" : "") + ">Admin</option>" +
        "</select></td>" +
        '<td class="inline">' +
        '<button onclick="updateUser(' + user.id + ')">Save</button>' +
        '<button class="danger" onclick="deleteUser(' + user.id + ')">Delete</button>' +
        "</td></tr>";
    }
  };
  xhr.send();
}

document.getElementById("user-form").onsubmit = function (event) {
  event.preventDefault();
  if (!confirm("Add this new user?")) return;
  var data = new FormData();
  data.append("name", document.getElementById("new-user-name").value);
  data.append("email", document.getElementById("new-user-email").value);
  data.append("password", document.getElementById("new-user-password").value);
  data.append("role", document.getElementById("new-user-role").value);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", ENDPOINT + "?action=create_user", true);
  xhr.onload = function () {
    var result = JSON.parse(xhr.responseText);
    document.getElementById("message").innerHTML = result.message;
    if (result.success) {
      document.getElementById("user-form").reset();
      loadAdminData();
    }
  };
  xhr.send(data);
};

function updateUser(userId) {
  if (!confirm("Save changes to this user?")) return;
  var data = new FormData();
  data.append("user_id", userId);
  data.append("name", document.getElementById("name-" + userId).value);
  data.append("email", document.getElementById("email-" + userId).value);
  data.append("role", document.getElementById("role-" + userId).value);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", ENDPOINT + "?action=update_user", true);
  xhr.onload = function () {
    var result = JSON.parse(xhr.responseText);
    document.getElementById("message").innerHTML = result.message;
    loadAdminData();
  };
  xhr.send(data);
}

function deleteUser(userId) {
  if (!confirm("Delete this user?")) return;
  var data = new FormData();
  data.append("user_id", userId);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", ENDPOINT + "?action=delete_user", true);
  xhr.onload = function () {
    var result = JSON.parse(xhr.responseText);
    document.getElementById("message").innerHTML = result.message;
    loadAdminData();
  };
  xhr.send(data);
}

function reviewLeave(leaveId, status) {
  var label = status == "approved" ? "approve" : "reject";
  if (!confirm("Are you sure you want to " + label + " this leave request?")) return;
  var data = new FormData();
  data.append("leave_id", leaveId);
  data.append("status", status);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", ENDPOINT + "?action=review_leave", true);
  xhr.onload = function () {
    var result = JSON.parse(xhr.responseText);
    document.getElementById("message").innerHTML = result.message;
    loadAdminData();
  };
  xhr.send(data);
}

loadAdminData();
