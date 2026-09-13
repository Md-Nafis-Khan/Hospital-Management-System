var appointmentForm = document.getElementById("appointment-form");
var appointmentAvailable = true;

function checkAppointmentAvailability() {
    var doctor = document.getElementById("doctor-id").value;
    var date = document.getElementById("appointment-date").value;
    var time = document.getElementById("appointment-time").value;
    var appointmentInput = document.querySelector('input[name="appointment_id"]');
    var appointmentId = appointmentInput ? appointmentInput.value : 0;
    var status = document.getElementById("availability-status");

    if (doctor == "" || date == "" || time == "") {
        appointmentAvailable = true;
        status.innerHTML = "";
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../../controller/patient/check_availability.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var data = JSON.parse(xhr.responseText);
            appointmentAvailable = data.available;
            status.innerHTML = data.message;

            if (!data.available) {
                alert(data.message);
            }
        }
    };

    xhr.send("doctor_id=" + encodeURIComponent(doctor) +
        "&date=" + encodeURIComponent(date) +
        "&time=" + encodeURIComponent(time) +
        "&appointment_id=" + encodeURIComponent(appointmentId));
}

document.getElementById("doctor-id").onchange = checkAppointmentAvailability;
document.getElementById("appointment-date").onchange = checkAppointmentAvailability;
document.getElementById("appointment-time").onchange = checkAppointmentAvailability;

appointmentForm.onsubmit = function () {
    var doctor = document.getElementById("doctor-id").value;
    var date = document.getElementById("appointment-date").value;
    var time = document.getElementById("appointment-time").value;

    if (doctor == "" && !document.querySelector('input[name="appointment_id"]')) {
        alert("Please choose a doctor.");
        return false;
    }

    if (date == "") {
        alert("Please choose an appointment date.");
        return false;
    }

    if (time == "") {
        alert("Please choose an appointment time.");
        return false;
    }

    if (date < new Date().toISOString().split("T")[0]) {
        alert("Appointment date cannot be in the past.");
        return false;
    }

    if (!appointmentAvailable) {
        alert("This appointment time is already booked.");
        return false;
    }

    return true;
};

function confirmCancelAppointment() {
    alert("The appointment will be cancelled.");
    return confirm("Are you sure you want to cancel this appointment?");
}
