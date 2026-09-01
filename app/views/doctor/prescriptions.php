<?php
$title = 'Prescriptions';
require 'app/views/partials/header.php';
?>
<h1>Prescriptions</h1>

<div class="table">
    <table>
        <tr>
            <th>Patient</th>
            <th>Diagnosis</th>
            <th>Medicines</th>
            <th>Date</th>
        </tr>

        <?php foreach ($items as $x): ?>
            <tr>
                <td><?= htmlspecialchars($x['patient_name']) ?></td>
                <td><?= htmlspecialchars($x['diagnosis']) ?></td>
                <td><?= nl2br(htmlspecialchars($x['medicines'])) ?></td>
                <td><?= htmlspecialchars($x['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php if (!$items): ?>
        <p>No prescriptions found.</p>
    <?php endif; ?>
</div>

<?php require 'app/views/partials/footer.php'; ?>
