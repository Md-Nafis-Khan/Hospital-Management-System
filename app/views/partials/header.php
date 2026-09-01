<?php
$role = $_SESSION['user_type'] ?? '';
$name = $_SESSION['full_name'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'CarePlus Hospital') ?></title>
    <link rel="stylesheet" href="public/assets/css/global.css">
    <link rel="stylesheet" href="public/assets/css/header.css">
    <link rel="stylesheet" href="public/assets/css/dashboard.css">
    <link rel="stylesheet" href="public/assets/css/forms.css">
    <link rel="stylesheet" href="public/assets/css/responsive.css">
    <script src="public/assets/js/validation.js" defer></script>
</head>
<body>
<header class="site-header">
    <a class="brand" href="index.php">✚ <strong>CarePlus</strong> <small>HOSPITAL</small></a>
    <div class="user-info">
        <span><?= htmlspecialchars($name) ?> · <?= htmlspecialchars($role) ?></span>
        <a href="index.php?action=logout">Logout</a>
    </div>
</header>
<div class="layout">
    <nav class="sidebar">
        <?php if ($role === 'Patient'): ?>
            <a href="index.php?action=patient_dashboard">Dashboard</a>
            <a href="index.php?action=patient_profile">My Profile</a>
            <a href="index.php?action=book_appointment">Book Appointment</a>
            <a href="index.php?action=appointments">Appointments</a>
            <a href="index.php?action=prescriptions">Prescriptions</a>
        <?php elseif ($role === 'Doctor'): ?>
            <a href="index.php?action=doctor_dashboard">Dashboard</a>
            <a href="index.php?action=doctor_appointments">Appointments</a>
            <a href="index.php?action=add_prescription">Add Prescription</a>
            <a href="index.php?action=doctor_prescriptions">Prescriptions</a>
        <?php elseif ($role === 'Admin'): ?>
            <a href="index.php?action=admin_dashboard">Dashboard</a>
            <a href="index.php?action=admin_users">Users</a>
            <a href="index.php?action=admin_appointments">Appointments</a>
        <?php endif; ?>
    </nav>
    <main class="content">
        <?php if (!empty($_SESSION['flash'])): ?>
            <?php $flash = $_SESSION['flash']; unset($_SESSION['flash']); ?>
            <div class="alert <?= htmlspecialchars($flash[1]) ?>">
                <?= htmlspecialchars($flash[0]) ?>
            </div>
        <?php endif; ?>
