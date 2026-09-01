<?php

function showLogin($errors = [], $username = '')
{
    require 'app/views/auth/login.php';
}

function loginUser()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        showLogin();
        return;
    }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $errors = [];

    if ($username === '') {
        $errors[] = 'Username is required.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if (count($errors) > 0) {
        showLogin($errors, $username);
        return;
    }

    $db = db_connect();
    $user = findUserByUsername($db, $username);
    mysqli_close($db);

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['user_type'] = $user['user_type'];

        if ($user['user_type'] === 'Admin') {
            header('Location: index.php?action=admin_dashboard');
        } elseif ($user['user_type'] === 'Doctor') {
            header('Location: index.php?action=doctor_dashboard');
        } else {
            header('Location: index.php?action=patient_dashboard');
        }
        exit;
    }

    $errors[] = 'Invalid username or password.';
    showLogin($errors, $username);
}

function validateSignup($data, $accountType)
{
    $errors = [];

    if ($data['full_name'] === '') {
        $errors[] = 'Full name is required.';
    }

    if ($data['phone'] === '') {
        $errors[] = 'Phone number is required.';
    }

    if ($data['username'] === '') {
        $errors[] = 'Username is required.';
    }

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email.';
    }

    if (strlen($data['password']) < 6) {
        $errors[] = 'Password must contain at least 6 characters.';
    }

    if ($data['password'] !== $data['confirm_password']) {
        $errors[] = 'Passwords do not match.';
    }

    if ($data['gender'] === '') {
        $errors[] = 'Select gender.';
    }

    if ($accountType === 'Doctor' && $data['specialization'] === '') {
        $errors[] = 'Specialization is required for a doctor account.';
    }

    return $errors;
}

function signupUser()
{
    $accountType = $_GET['type'] ?? $_POST['account_type'] ?? 'Patient';

    if ($accountType !== 'Patient' && $accountType !== 'Doctor') {
        $accountType = 'Patient';
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $errors = [];
        $old = [];
        require 'app/views/auth/signup.php';
        return;
    }

    $data = [
        'full_name' => trim($_POST['full_name'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'username' => trim($_POST['username'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'confirm_password' => $_POST['confirm_password'] ?? '',
        'date_of_birth' => trim($_POST['date_of_birth'] ?? ''),
        'gender' => trim($_POST['gender'] ?? ''),
        'address' => trim($_POST['address'] ?? ''),
        'specialization' => trim($_POST['specialization'] ?? '')
    ];

    $errors = validateSignup($data, $accountType);
    $db = db_connect();

    if (count($errors) === 0 && userExists($db, $data['username'], $data['email'])) {
        $errors[] = 'Username or email already exists.';
    }

    if (count($errors) === 0) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $userId = createUser($db, $data, $accountType);

        if ($userId) {
            mysqli_close($db);
            $_SESSION['flash'] = [$accountType . ' account created successfully. Please log in.', 'success'];
            header('Location: index.php?action=login');
            exit;
        }

        $errors[] = 'Could not create the account.';
    }

    mysqli_close($db);
    $old = $_POST;
    require 'app/views/auth/signup.php';
}

function logoutUser()
{
    $_SESSION = [];
    session_destroy();
    header('Location: index.php?action=login');
    exit;
}


function checkUsername($conn)
{
    header('Content-Type: application/json');

    if (!isset($_GET['username'])) {
        echo json_encode([
            'exists' => false,
            'message' => 'Username was not provided.'
        ]);

        exit;
    }

    $username = trim($_GET['username']);

    if ($username === '') {
        echo json_encode([
            'exists' => false,
            'message' => ''
        ]);

        exit;
    }

    $exists = usernameExists($conn, $username);

    if ($exists) {
        echo json_encode([
            'exists' => true,
            'message' => 'This username already exists.'
        ]);
    } else {
        echo json_encode([
            'exists' => false,
            'message' => 'Username is available.'
        ]);
    }

    exit;
}
