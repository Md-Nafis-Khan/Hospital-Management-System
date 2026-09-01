<?php
$title = 'Prescriptions';
require 'app/views/partials/header.php';
?>
<h1>My Prescriptions</h1>

<?php foreach ($items as $x): ?>
    <div class="panel">
        <h3><?= htmlspecialchars($x['diagnosis']) ?></h3>
        <p><b>Doctor:</b> <?= htmlspecialchars($x['doctor_name']) ?></p>
        <p>
            <b>Medicines:</b><br>
            <?= nl2br(htmlspecialchars($x['medicines'])) ?>
        </p>
        <p>
            <b>Instructions:</b><br>
            <?= nl2br(htmlspecialchars($x['instructions'])) ?>
        </p>
    </div>
<?php endforeach; ?>

<?php if (!$items): ?>
    <div class="panel">No prescriptions found.</div>
<?php endif; ?>

<?php require 'app/views/partials/footer.php'; ?>
