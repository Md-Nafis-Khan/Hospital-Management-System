<?php

function count_appointments($conn)
{
    $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM appointments");
    $row = mysqli_fetch_assoc($result);
    return $row["total"];
}

function count_pending_leaves($conn)
{
    $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM leaves WHERE status = 'pending'");
    $row = mysqli_fetch_assoc($result);
    return $row["total"];
}

function get_all_leaves($conn)
{
    $sql = "SELECT leaves.*, users.name AS doctor_name
            FROM leaves
            JOIN users ON leaves.doctor_id = users.id
            ORDER BY leaves.id DESC";
    $result = mysqli_query($conn, $sql);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function review_leave($conn, $id, $status)
{
    $sql = "UPDATE leaves SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $status, $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $ok;
}
