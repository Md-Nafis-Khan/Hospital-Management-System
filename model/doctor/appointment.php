<?php

function get_doctor_appointments($conn, $doctor_id)
{
    $sql = "SELECT appointments.*, users.name AS patient_name, users.email AS patient_email
            FROM appointments
            JOIN users ON appointments.patient_id = users.id
            WHERE appointments.doctor_id = ?
            ORDER BY appointment_date DESC, appointment_time DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $doctor_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    return $rows;
}

function update_appointment_status($conn, $id, $doctor_id, $status)
{
    $sql = "UPDATE appointments SET status = ? WHERE id = ? AND doctor_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sii", $status, $id, $doctor_id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $ok;
}
