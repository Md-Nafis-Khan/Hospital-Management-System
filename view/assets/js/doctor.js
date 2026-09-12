var leaveForm = document.getElementById("leave-form");

leaveForm.onsubmit = function () {
    var start = document.getElementById("start-date").value;
    var end = document.getElementById("end-date").value;
    var reason = document.getElementById("leave-reason").value;

    if (start == "") {
        alert("Please choose a start date.");
        return false;
    }

    if (end == "") {
        alert("Please choose an end date.");
        return false;
    }

    if (end < start) {
        alert("End date cannot be before start date.");
        return false;
    }

    if (!checkText(reason, "Please enter the reason for leave.")) {
        return false;
    }

    return true;
};

function confirmAppointment(action) {
    alert("You selected: " + action + " appointment.");
    return confirm("Are you sure you want to " + action + " this appointment?");
}

function confirmDeleteLeave() {
    alert("The leave application will be deleted.");
    return confirm("Are you sure you want to delete this leave application?");
}

function updateAppointmentStatus(appointmentId, status, button) {
    alert("You selected: " + status + " appointment.");

    if (!confirm("Are you sure you want to update this appointment?")) {
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../../controller/doctor/update_appointment_ajax.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var data = JSON.parse(xhr.responseText);

            if (data.success) {
                var row = button.parentElement.parentElement;
                var statusCell = row.children[4];
                var actionCell = row.children[5];

                statusCell.innerHTML = '<span class="status ' + data.status + '">' + data.status + '</span>';
                actionCell.innerHTML = "-";
                alert(data.message);
            } else {
                alert(data.message);
            }
        }
    };

    xhr.send("appointment_id=" + encodeURIComponent(appointmentId) +
        "&status=" + encodeURIComponent(status));
}
