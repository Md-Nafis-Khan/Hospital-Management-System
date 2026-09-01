<?php
$title = 'Admin Dashboard';
require 'app/views/partials/header.php';
?>
<h1>Admin Dashboard</h1>

<div class="cards">
    <div>
        <span>Total Users</span>
        <b><?= $stats['users'] ?></b>
    </div>
    <div>
        <span>Patients</span>
        <b><?= $stats['patients'] ?></b>
    </div>
    <div>
        <span>Doctors</span>
        <b><?= $stats['doctors'] ?></b>
    </div>
    <div>
        <span>Appointments</span>
        <b><?= $stats['appointments'] ?></b>
    </div>
</div>

<?php require 'app/views/partials/footer.php'; ?>
