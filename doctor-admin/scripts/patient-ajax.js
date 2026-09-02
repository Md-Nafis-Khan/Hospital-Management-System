var ENDPOINT = "app/controllers/PatientController.php";

function statusBadge(status) {
  return '<span class="status ' + status + '">' + status + "</span>";
}

var patientAppointments = [];

function loadPatientData() {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", ENDPOINT + "?action=patient_data", true);
  xhr.onload = function () {
    var result = JSON.parse(xhr.responseText);
    var doctors = document.getElementById("doctor-id");
    doctors.innerHTML = '<option value="">Choose doctor</option>';
    for (var i = 0; i < result.doctors.length; i++) {
      doctors.innerHTML += '<option value="' + result.doctors[i].id + '">' + result.doctors[i].name + "</option>";
    }

    patientAppointments = result.appointments;
    var appointments = document.getElementById("appointment-list");
    appointments.innerHTML = "";
    for (var j = 0; j < result.appointments.length; j++) {
      var item = result.appointments[j];
      var action = "-";
      if (item.status == "pending") {
        action = '<div class="inline">' +
          '<button onclick="editAppointment(' + item.id + ')">Edit</button>' +
          '<button class="danger" onclick="deleteAppointment(' + item.id + ')">Cancel</button>' +
          "</div>";
      }
      appointments.innerHTML += "<tr><td>" + item.doctor_name + "</td><td>" + item.appointment_date + "</td><td>" + item.appointment_time + "</td><td>" + item.notes + "</td><td>" + statusBadge(item.status) + "</td><td>" + action + "</td></tr>";
    }
    if (result.appointments.length == 0) appointments.innerHTML = '<tr><td colspan="6">No appointments yet.</td></tr>';
  };
  xhr.send();
}

function findAppointment(id) {
  for (var i = 0; i < patientAppointments.length; i++) {
    if (patientAppointments[i].id == id) return patientAppointments[i];
  }
  return null;
}

function editAppointment(id) {
  var item = findAppointment(id);
  if (!item) return;
  document.getElementById("appointment-id").value = item.id;
  document.getElementById("doctor-id").value = item.doctor_id;
  document.getElementById("appointment-date").value = item.appointment_date;
  document.getElementById("appointment-time").value = item.appointment_time;
  document.getElementById("appointment-notes").value = item.notes;
  document.getElementById("appointment-form-title").innerHTML = "Edit appointment";
  document.getElementById("appointment-submit").innerHTML = "Update appointment";
  document.getElementById("appointment-cancel").style.display = "inline-block";
  document.querySelector('[data-tab="tab-book"]').click();
}

function resetAppointmentForm() {
  document.getElementById("appointment-form").reset();
  document.getElementById("appointment-id").value = "";
  document.getElementById("appointment-form-title").innerHTML = "Book an appointment";
  document.getElementById("appointment-submit").innerHTML = "Book appointment";
  document.getElementById("appointment-cancel").style.display = "none";
}

document.getElementById("appointment-cancel").onclick = function () {
  resetAppointmentForm();
};

function deleteAppointment(id) {
  if (!confirm("Cancel this appointment?")) return;
  var data = new FormData();
  data.append("appointment_id", id);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", ENDPOINT + "?action=delete_appointment", true);
  xhr.onload = function () {
    var result = JSON.parse(xhr.responseText);
    document.getElementById("message").innerHTML = result.message;
    loadPatientData();
  };
  xhr.send(data);
}

document.getElementById("appointment-form").onsubmit = function (event) {
  event.preventDefault();
  var appointmentId = document.getElementById("appointment-id").value;
  var confirmMessage = appointmentId == "" ? "Book this appointment?" : "Save changes to this appointment?";
  if (!confirm(confirmMessage)) return;
  var data = new FormData();
  data.append("doctor_id", document.getElementById("doctor-id").value);
  data.append("date", document.getElementById("appointment-date").value);
  data.append("time", document.getElementById("appointment-time").value);
  data.append("notes", document.getElementById("appointment-notes").value);

  var xhr = new XMLHttpRequest();
  if (appointmentId == "") {
    xhr.open("POST", ENDPOINT + "?action=book_appointment", true);
  } else {
    data.append("appointment_id", appointmentId);
    xhr.open("POST", ENDPOINT + "?action=update_appointment_patient", true);
  }
  xhr.onload = function () {
    var result = JSON.parse(xhr.responseText);
    document.getElementById("message").innerHTML = result.message;
    if (result.success) resetAppointmentForm();
    loadPatientData();
  };
  xhr.send(data);
};

loadPatientData();
