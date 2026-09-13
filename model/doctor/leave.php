<?php

function create_leave($conn, $doctor_id, $start, $end, $reason)
{
    $sql = "INSERT INTO leaves (doctor_id, start_date, end_date, reason)
            VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isss", $doctor_id, $start, $end, $reason);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $ok;
}

function get_doctor_leaves($conn, $doctor_id)
{
    $sql = "SELECT * FROM leaves WHERE doctor_id = ? ORDER BY id DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $doctor_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    return $rows;
}

function update_leave($conn, $id, $doctor_id, $start, $end, $reason)
{
    $sql = "UPDATE leaves
            SET start_date = ?, end_date = ?, reason = ?
            WHERE id = ? AND doctor_id = ? AND status = 'pending'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssii", $start, $end, $reason, $id, $doctor_id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $ok;
}

function delete_leave($conn, $id, $doctor_id)
{
    $sql = "DELETE FROM leaves
            WHERE id = ? AND doctor_id = ? AND status = 'pending'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id, $doctor_id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $ok;
}

function get_doctor_leave($conn, $id, $doctor_id)
{
    $sql = "SELECT * FROM leaves WHERE id = ? AND doctor_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id, $doctor_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return $row;
}
