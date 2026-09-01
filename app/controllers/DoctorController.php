<?php

function doctorAllowed()
{
    if (($_SESSION['user_type'] ?? '') !== 'Doctor') {
        header('Location: index.php?action=login');
        exit;
    }
}

function doctorDashboard()
{
    doctorAllowed();
    $db = db_connect();
    $id = (int) $_SESSION['user_id'];
    $s = getDoctorStats($db, $id);
    mysqli_close($db);
    $title = 'Doctor Dashboard';
    require 'app/views/doctor/dashboard.php';
}

function doctorAppointments()
{
    doctorAllowed();
    $db = db_connect();
    $id = (int) $_SESSION['user_id'];

    $stmt = mysqli_prepare($db, "SELECT a.*, p.full_name AS patient_name
                                 FROM appointments a
                                 JOIN users p ON p.id = a.patient_id
                                 WHERE a.doctor_id = ?
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
    $title = 'Doctor Appointments';
    require 'app/views/doctor/appointments.php';
}

function updateAppointmentStatus()
{
    doctorAllowed();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?action=doctor_appointments');
        exit;
    }

    $status = $_POST['status'] ?? '';
    $appointmentId = (int) ($_POST['appointment_id'] ?? 0);
    $doctorId = (int) $_SESSION['user_id'];

    if ($status !== 'Pending' && $status !== 'Confirmed' && $status !== 'Completed' && $status !== 'Cancelled') {
        $_SESSION['flash'] = ['Invalid status.', 'error'];
        header('Location: index.php?action=doctor_appointments');
        exit;
    }

    $db = db_connect();
    $stmt = mysqli_prepare($db, "UPDATE appointments SET status = ? WHERE id = ? AND doctor_id = ?");
    mysqli_stmt_bind_param($stmt, 'sii', $status, $appointmentId, $doctorId);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($db);

    $_SESSION['flash'] = [$ok ? 'Appointment status updated.' : 'Could not update appointment.', $ok ? 'success' : 'error'];
    header('Location: index.php?action=doctor_appointments');
    exit;
}

function addPrescription()
{
    doctorAllowed();
    $db = db_connect();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $patientId = (int) ($_POST['patient_id'] ?? 0);
        $doctorId = (int) $_SESSION['user_id'];
        $diagnosis = trim($_POST['diagnosis'] ?? '');
        $medicines = trim($_POST['medicines'] ?? '');
        $instructions = trim($_POST['instructions'] ?? '');

        $stmt = mysqli_prepare($db, "INSERT INTO prescriptions
                                     (patient_id, doctor_id, diagnosis, medicines, instructions)
                                     VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'iisss', $patientId, $doctorId, $diagnosis, $medicines, $instructions);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($db);

        $_SESSION['flash'] = [$ok ? 'Prescription added.' : 'Could not add prescription.', $ok ? 'success' : 'error'];
        header('Location: index.php?action=doctor_prescriptions');
        exit;
    }

    $patients = [];
    $result = mysqli_query($db, "SELECT id, full_name FROM users WHERE user_type = 'Patient' ORDER BY full_name");

    while ($row = mysqli_fetch_assoc($result)) {
        $patients[] = $row;
    }

    mysqli_close($db);
    $title = 'Add Prescription';
    require 'app/views/doctor/add_prescription.php';
}

function doctorPrescriptions()
{
    doctorAllowed();
    $db = db_connect();
    $id = (int) $_SESSION['user_id'];

    $stmt = mysqli_prepare($db, "SELECT p.*, u.full_name AS patient_name
                                 FROM prescriptions p
                                 JOIN users u ON u.id = p.patient_id
                                 WHERE p.doctor_id = ?
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
    require 'app/views/doctor/prescriptions.php';
}
