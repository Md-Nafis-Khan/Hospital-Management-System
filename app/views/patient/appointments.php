<?php
$title = 'Appointments';
require 'app/views/partials/header.php';
?>
<h1>My Appointments</h1>

<div class="table">
    <table>
        <tr>
            <th>Doctor</th>
            <th>Date</th>
            <th>Time</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php foreach ($items as $x): ?>
            <tr>
                <td><?= htmlspecialchars($x['doctor_name']) ?></td>
                <td><?= htmlspecialchars($x['appointment_date']) ?></td>
                <td><?= htmlspecialchars($x['appointment_time']) ?></td>
                <td><?= htmlspecialchars($x['reason']) ?></td>
                <td><?= htmlspecialchars($x['status']) ?></td>
                <td>
                    <?php if ($x['status'] === 'Pending'): ?>
                        <form method="post" action="?action=cancel_appointment">
                            <input type="hidden" name="appointment_id" value="<?= $x['id'] ?>">
                            <button type="submit" class="danger">Cancel</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php if (!$items): ?>
        <p>No appointments found.</p>
    <?php endif; ?>
</div>

<?php require 'app/views/partials/footer.php'; ?>
