<?php
$title = 'Doctor Dashboard';
require 'app/views/partials/header.php';
?>
<h1>Doctor Dashboard</h1>

<div class="cards">
    <div>
        <span>Total</span>
        <b><?= $s['total'] ?></b>
    </div>
    <div>
        <span>Pending</span>
        <b><?= $s['pending'] ?></b>
    </div>
    <div>
        <span>Confirmed</span>
        <b><?= $s['confirmed'] ?></b>
    </div>
    <div>
        <span>Completed</span>
        <b><?= $s['completed'] ?></b>
    </div>
</div>

<?php require 'app/views/partials/footer.php'; ?>
