<?php

function getPatientProfile($db, $id)
{
    $sql = "SELECT u.*, p.blood_group, p.emergency_contact
            FROM users u
            LEFT JOIN patient_profiles p ON p.user_id = u.id
            WHERE u.id = ?";

    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $profile = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return $profile;
}

function updatePatientProfile($db, $id, $name, $phone, $email, $dob, $gender, $address, $bloodGroup, $emergencyContact)
{
    mysqli_begin_transaction($db);

    $sql = "UPDATE users
            SET full_name = ?, phone = ?, email = ?, date_of_birth = NULLIF(?, ''),
                gender = ?, address = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssssi', $name, $phone, $email, $dob, $gender, $address, $id);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_rollback($db);
        return false;
    }
    mysqli_stmt_close($stmt);

    $sql = "INSERT INTO patient_profiles (user_id, blood_group, emergency_contact)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE blood_group = VALUES(blood_group), emergency_contact = VALUES(emergency_contact)";

    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, 'iss', $id, $bloodGroup, $emergencyContact);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_rollback($db);
        return false;
    }
    mysqli_stmt_close($stmt);

    mysqli_commit($db);
    return true;
}
