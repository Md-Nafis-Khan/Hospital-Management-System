<?php

function patientAllowed()
{
    if (($_SESSION['user_type'] ?? '') !== 'Patient') {
        header('Location: index.php?action=login');
        exit;
    }
}

function patientDashboard()
{
    patientAllowed();
    $db = db_connect();
    $id = (int) $_SESSION['user_id'];
    $a = countPatientAppointments($db, $id);
    $p = countPatientPrescriptions($db, $id);
    mysqli_close($db);
    $title = 'Patient Dashboard';
    require 'app/views/patient/dashboard.php';
}

function patientProfile()
{
    patientAllowed();
    $db = db_connect();
    $id = (int) $_SESSION['user_id'];
    $profile = getPatientProfile($db, $id);
    mysqli_close($db);
    $title = 'My Profile';
    require 'app/views/patient/profile.php';
}

function savePatientProfile()
{
    patientAllowed();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?action=patient_profile');
        exit;
    }

    $id = (int) $_SESSION['user_id'];
    $name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $dob = trim($_POST['date_of_birth'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $bloodGroup = trim($_POST['blood_group'] ?? '');
    $emergencyContact = trim($_POST['emergency_contact'] ?? '');

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['flash'] = ['Please enter a valid name and email.', 'error'];
        header('Location: index.php?action=patient_profile');
        exit;
    }

    $db = db_connect();
    $ok = updatePatientProfile($db, $id, $name, $phone, $email, $dob, $gender, $address, $bloodGroup, $emergencyContact);
    mysqli_close($db);

    if ($ok) {
        $_SESSION['full_name'] = $name;
        $_SESSION['flash'] = ['Profile updated successfully.', 'success'];
    } else {
        $_SESSION['flash'] = ['Profile update failed.', 'error'];
    }

    header('Location: index.php?action=patient_profile');
    exit;
}

function bookAppointment()
{
    patientAllowed();
    $db = db_connect();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $doctorId = (int) ($_POST['doctor_id'] ?? 0);
        $date = trim($_POST['appointment_date'] ?? '');
        $time = trim($_POST['appointment_time'] ?? '');
        $reason = trim($_POST['reason'] ?? '');
        $patientId = (int) $_SESSION['user_id'];

        if (isAppointmentSlotBusy($db, $doctorId, $date, $time)) {
            mysqli_close($db);
            $_SESSION['flash'] = ['That appointment slot is already booked.', 'error'];
            header('Location: index.php?action=book_appointment');
            exit;
        }

        $ok = createAppointment($db, $patientId, $doctorId, $date, $time, $reason);
        mysqli_close($db);

        $_SESSION['flash'] = [$ok ? 'Appointment booked successfully.' : 'Booking failed.', $ok ? 'success' : 'error'];
        header('Location: index.php?action=appointments');
        exit;
    }

    $doctors = [];
    $result = mysqli_query($db, "SELECT id, full_name FROM users WHERE user_type = 'Doctor' ORDER BY full_name");

    while ($row = mysqli_fetch_assoc($result)) {
        $doctors[] = $row;
    }

    mysqli_close($db);
    $title = 'Book Appointment';
    require 'app/views/patient/book.php';
}

function patientAppointments()
{
    patientAllowed();
    $db = db_connect();
    $id = (int) $_SESSION['user_id'];

    $stmt = mysqli_prepare($db, "SELECT a.*, d.full_name AS doctor_name
                                 FROM appointments a
                                 JOIN users d ON d.id = a.doctor_id
                                 WHERE a.patient_id = ?
                                 ORDER BY a.appointment_date DESC, a.appointment_time DESC");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $items = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($db);
    $title = 'Appointments';
    require 'app/views/patient/appointments.php';
}

function cancelAppointment()
{
    patientAllowed();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?action=appointments');
        exit;
    }

    $db = db_connect();
    $appointmentId = (int) ($_POST['appointment_id'] ?? 0);
    $patientId = (int) $_SESSION['user_id'];

    $stmt = mysqli_prepare($db, "UPDATE appointments
                                 SET status = 'Cancelled'
                                 WHERE id = ? AND patient_id = ? AND status = 'Pending'");
    mysqli_stmt_bind_param($stmt, 'ii', $appointmentId, $patientId);
    mysqli_stmt_execute($stmt);
    $ok = mysqli_stmt_affected_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
    mysqli_close($db);

    $_SESSION['flash'] = [$ok ? 'Appointment cancelled.' : 'Only pending appointments can be cancelled.', $ok ? 'success' : 'error'];
    header('Location: index.php?action=appointments');
    exit;
}

function patientPrescriptions()
{
    patientAllowed();
    $db = db_connect();
    $id = (int) $_SESSION['user_id'];

    $stmt = mysqli_prepare($db, "SELECT p.*, d.full_name AS doctor_name
                                 FROM prescriptions p
                                 JOIN users d ON d.id = p.doctor_id
                                 WHERE p.patient_id = ?
                                 ORDER BY p.created_at DESC");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $items = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($db);
    $title = 'Prescriptions';
    require 'app/views/patient/prescriptions.php';
}
