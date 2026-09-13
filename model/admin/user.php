<?php

function get_all_users($conn)
{
    $result = mysqli_query($conn, "SELECT id, name, email, role, created_at FROM users ORDER BY id DESC");
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function update_user($conn, $id, $name, $email, $role)
{
    $sql = "UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $role, $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $ok;
}

function delete_user($conn, $id)
{
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $ok;
}

function email_exists_for_other_user($conn, $email, $id)
{
    $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $email, $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return $row;
}
