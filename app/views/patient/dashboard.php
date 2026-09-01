<?php
$title = 'Patient Dashboard';
require 'app/views/partials/header.php';
?>
<h1>Patient Dashboard</h1>
<p>Welcome back, <?= htmlspecialchars($_SESSION['full_name']) ?>.</p>

<div class="cards">
    <div>
        <span>Appointments</span>
        <b><?= $a ?></b>
    </div>
    <div>
        <span>Prescriptions</span>
        <b><?= $p ?></b>
    </div>
</div>

<div class="panel">
    <h2>Quick Actions</h2>
    <a class="btn" href="?action=book_appointment">Book Appointment</a>
    <a class="btn" href="?action=patient_profile">My Profile</a>
</div>

<?php require 'app/views/partials/footer.php'; ?>
