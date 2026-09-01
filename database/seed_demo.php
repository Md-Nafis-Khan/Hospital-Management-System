<?php

require '../config/config.php';
require '../config/database.php';

$db = db_connect();
$password = password_hash('password', PASSWORD_DEFAULT);

$users = [
    ['Administrator', '01000000000', 'admin', 'admin@careplus.test', 'Admin', ''],
    ['Dr. Demo', '01000000001', 'doctor', 'doctor@careplus.test', 'Doctor', 'General Medicine'],
    ['Demo Patient', '01000000002', 'patient', 'patient@careplus.test', 'Patient', '']
];

foreach ($users as $user) {
    $fullName = $user[0];
    $phone = $user[1];
    $username = $user[2];
    $email = $user[3];
    $userType = $user[4];
    $specialization = $user[5];
    $gender = 'Other';

    $check = mysqli_prepare($db, 'SELECT id FROM users WHERE username = ? LIMIT 1');
    mysqli_stmt_bind_param($check, 's', $username);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);
    $exists = mysqli_fetch_assoc($result);
    mysqli_stmt_close($check);

    if ($exists) {
        echo $username . ' already exists.<br>';
        continue;
    }

    $stmt = mysqli_prepare($db, 'INSERT INTO users (full_name, phone, username, email, password, user_type, gender, specialization) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'ssssssss', $fullName, $phone, $username, $email, $password, $userType, $gender, $specialization);
    mysqli_stmt_execute($stmt);
    $id = mysqli_insert_id($db);
    mysqli_stmt_close($stmt);

    if ($userType === 'Patient') {
        $profile = mysqli_prepare($db, 'INSERT INTO patient_profiles (user_id) VALUES (?)');
        mysqli_stmt_bind_param($profile, 'i', $id);
        mysqli_stmt_execute($profile);
        mysqli_stmt_close($profile);
    }

    echo $username . ' created.<br>';
}

mysqli_close($db);

echo '<br>Demo password: password';
echo '<br><strong>Delete seed_demo.php after setup.</strong>';
