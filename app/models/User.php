<?php

function findUserByUsername($db, $username)
{
    $sql = "SELECT id, full_name, username, password, user_type FROM users WHERE username = ?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return $user;
}

function userExists($db, $username, $email)
{
    $sql = "SELECT id FROM users WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $username, $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return $user ? true : false;
}

function createUser($db, $data, $accountType)
{
    $sql = "INSERT INTO users (full_name, phone, username, email, password, user_type, date_of_birth, gender, address, specialization)
            VALUES (?, ?, ?, ?, ?, ?, NULLIF(?, ''), ?, ?, ?)";

    $stmt = mysqli_prepare($db, $sql);

    $fullName = $data['full_name'];
    $phone = $data['phone'];
    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];
    $dateOfBirth = $data['date_of_birth'];
    $gender = $data['gender'];
    $address = $data['address'];
    $specialization = $data['specialization'];

    mysqli_stmt_bind_param(
        $stmt,
        'ssssssssss',
        $fullName,
        $phone,
        $username,
        $email,
        $password,
        $accountType,
        $dateOfBirth,
        $gender,
        $address,
        $specialization
    );

    mysqli_begin_transaction($db);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_rollback($db);
        return false;
    }

    $userId = mysqli_insert_id($db);
    mysqli_stmt_close($stmt);

    if ($accountType === 'Patient') {
        $profile = mysqli_prepare($db, "INSERT INTO patient_profiles (user_id) VALUES (?)");
        mysqli_stmt_bind_param($profile, 'i', $userId);

        if (!mysqli_stmt_execute($profile)) {
            mysqli_stmt_close($profile);
            mysqli_rollback($db);
            return false;
        }

        mysqli_stmt_close($profile);
    }

    mysqli_commit($db);
    return $userId;
}

function usernameExists($conn, $username)
{
    $sql = "SELECT id FROM users WHERE username = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $username);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        return true;
    }

    return false;
}