<?php

function create_appointment($conn, $patient_id, $doctor_id, $date, $time, $notes)
{
    $sql = "INSERT INTO appointments
            (patient_id, doctor_id, appointment_date, appointment_time, notes)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iisss", $patient_id, $doctor_id, $date, $time, $notes);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $ok;
}

function get_patient_appointments($conn, $patient_id)
{
    $sql = "SELECT appointments.*, users.name AS doctor_name
            FROM appointments
            JOIN users ON appointments.doctor_id = users.id
            WHERE appointments.patient_id = ?
            ORDER BY appointment_date DESC, appointment_time DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $patient_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    return $rows;
}

function update_patient_appointment($conn, $id, $patient_id, $date, $time, $notes)
{
    $sql = "UPDATE appointments
            SET appointment_date = ?, appointment_time = ?, notes = ?
            WHERE id = ? AND patient_id = ? AND status = 'pending'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssii", $date, $time, $notes, $id, $patient_id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $ok;
}

function delete_patient_appointment($conn, $id, $patient_id)
{
    $sql = "DELETE FROM appointments
            WHERE id = ? AND patient_id = ? AND status = 'pending'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id, $patient_id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $ok;
}

function get_patient_appointment($conn, $id, $patient_id)
{
    $sql = "SELECT * FROM appointments WHERE id = ? AND patient_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id, $patient_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return $row;
}

function appointment_slot_available($conn, $doctor_id, $date, $time, $appointment_id = 0)
{
    $sql = "SELECT id FROM appointments
            WHERE doctor_id = ?
            AND appointment_date = ?
            AND appointment_time = ?
            AND status IN ('pending', 'approved')
            AND id != ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "issi", $doctor_id, $date, $time, $appointment_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return !$row;
}
