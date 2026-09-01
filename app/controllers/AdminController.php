<?php

function adminAllowed()
{
    if (($_SESSION['user_type'] ?? '') !== 'Admin') {
        header('Location: index.php?action=login');
        exit;
    }
}

function adminDashboard()
{
    adminAllowed();
    $db = db_connect();
    $stats = [];

    $result = mysqli_query($db, "SELECT COUNT(*) AS total FROM users");
    $stats['users'] = mysqli_fetch_assoc($result)['total'];

    $result = mysqli_query($db, "SELECT COUNT(*) AS total FROM users WHERE user_type = 'Patient'");
    $stats['patients'] = mysqli_fetch_assoc($result)['total'];

    $result = mysqli_query($db, "SELECT COUNT(*) AS total FROM users WHERE user_type = 'Doctor'");
    $stats['doctors'] = mysqli_fetch_assoc($result)['total'];

    $result = mysqli_query($db, "SELECT COUNT(*) AS total FROM appointments");
    $stats['appointments'] = mysqli_fetch_assoc($result)['total'];

    mysqli_close($db);
    $title = 'Admin Dashboard';
    require 'app/views/admin/dashboard.php';
}

function adminUsers()
{
    adminAllowed();
    $db = db_connect();
    $result = mysqli_query($db, "SELECT id, full_name, username, email, phone, user_type, created_at
                                 FROM users ORDER BY created_at DESC");

    $items = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }

    mysqli_close($db);
    $title = 'Manage Users';
    require 'app/views/admin/users.php';
}

function adminAppointments()
{
    adminAllowed();
    $db = db_connect();
    $result = mysqli_query($db, "SELECT a.*, p.full_name AS patient_name, d.full_name AS doctor_name
                                 FROM appointments a
                                 JOIN users p ON p.id = a.patient_id
                                 JOIN users d ON d.id = a.doctor_id
                                 ORDER BY a.appointment_date DESC, a.appointment_time DESC");

    $items = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }

    mysqli_close($db);
    $title = 'Manage Appointments';
    require 'app/views/admin/appointments.php';
}

function deleteUser()
{
    adminAllowed();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?action=admin_users');
        exit;
    }

    $id = (int) ($_POST['user_id'] ?? 0);

    if ($id === (int) $_SESSION['user_id']) {
        $_SESSION['flash'] = ['You cannot delete yourself.', 'error'];
        header('Location: index.php?action=admin_users');
        exit;
    }

    $db = db_connect();
    $stmt = mysqli_prepare($db, "DELETE FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $ok = mysqli_stmt_affected_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
    mysqli_close($db);

    $_SESSION['flash'] = [$ok ? 'User deleted.' : 'User not found.', $ok ? 'success' : 'error'];
    header('Location: index.php?action=admin_users');
    exit;
}
