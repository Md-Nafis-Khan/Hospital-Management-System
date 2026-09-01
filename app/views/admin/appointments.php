<?php
$title = 'All Appointments';
require 'app/views/partials/header.php';
?>
<h1>All Appointments</h1>

<div class="table">
    <table>
        <tr>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Reason</th>
        </tr>

        <?php foreach ($items as $x): ?>
            <tr>
                <td><?= htmlspecialchars($x['patient_name']) ?></td>
                <td><?= htmlspecialchars($x['doctor_name']) ?></td>
                <td><?= htmlspecialchars($x['appointment_date']) ?></td>
                <td><?= htmlspecialchars($x['appointment_time']) ?></td>
                <td><?= htmlspecialchars($x['status']) ?></td>
                <td><?= htmlspecialchars($x['reason']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php if (!$items): ?>
        <p>No appointments found.</p>
    <?php endif; ?>
</div>

<?php require 'app/views/partials/footer.php'; ?>
