<?php

function countPatientAppointments($db, $patientId)
{
    $stmt = mysqli_prepare($db, "SELECT COUNT(*) AS total FROM appointments WHERE patient_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $patientId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return (int) $row['total'];
}

function getDoctorStats($db, $doctorId)
{
    $stats = [
        'total' => 0,
        'pending' => 0,
        'confirmed' => 0,
        'completed' => 0
    ];

    $stmt = mysqli_prepare($db, "SELECT status, COUNT(*) AS total FROM appointments WHERE doctor_id = ? GROUP BY status");
    mysqli_stmt_bind_param($stmt, 'i', $doctorId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $count = (int) $row['total'];
        $stats['total'] += $count;

        if ($row['status'] === 'Pending') {
            $stats['pending'] = $count;
        } elseif ($row['status'] === 'Confirmed') {
            $stats['confirmed'] = $count;
        } elseif ($row['status'] === 'Completed') {
            $stats['completed'] = $count;
        }
    }

    mysqli_stmt_close($stmt);
    return $stats;
}

function isAppointmentSlotBusy($db, $doctorId, $date, $time)
{
    $sql = "SELECT id FROM appointments
            WHERE doctor_id = ?
            AND appointment_date = ?
            AND appointment_time = ?
            AND status IN ('Pending', 'Confirmed')";

    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, 'iss', $doctorId, $date, $time);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return $row ? true : false;
}

function createAppointment($db, $patientId, $doctorId, $date, $time, $reason)
{
    $sql = "INSERT INTO appointments
            (patient_id, doctor_id, appointment_date, appointment_time, reason, status)
            VALUES (?, ?, ?, ?, ?, 'Pending')";

    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, 'iisss', $patientId, $doctorId, $date, $time, $reason);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $ok;
}
