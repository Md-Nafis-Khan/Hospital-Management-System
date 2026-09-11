<?php

function find_user_by_email($conn, $email)
{
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return $user;
}

function create_user($conn, $username, $name, $email, $password, $role)
{
    $password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, name, email, password, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $username, $name, $email, $password, $role);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $ok;
}

function check_username_or_email($conn, $username, $email)
{
    $sql = "SELECT username, email FROM users WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return $row;
}
