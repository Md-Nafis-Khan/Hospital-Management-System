<?php
$title = 'Doctor Appointments';
require 'app/views/partials/header.php';
?>
<h1>Appointments</h1>

<div class="table">
    <table>
        <tr>
            <th>Patient</th>
            <th>Date</th>
            <th>Time</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Update</th>
        </tr>

        <?php foreach ($items as $x): ?>
            <tr>
                <td><?= htmlspecialchars($x['patient_name']) ?></td>
                <td><?= htmlspecialchars($x['appointment_date']) ?></td>
                <td><?= htmlspecialchars($x['appointment_time']) ?></td>
                <td><?= htmlspecialchars($x['reason']) ?></td>
                <td><?= htmlspecialchars($x['status']) ?></td>
                <td>
                    <form method="post" action="?action=doctor_status">
                        <input type="hidden" name="appointment_id" value="<?= $x['id'] ?>">
                        <select name="status">
                            <?php foreach (['Pending', 'Confirmed', 'Completed', 'Cancelled'] as $st): ?>
                                <option value="<?= $st ?>" <?= ($x['status'] === $st) ? 'selected' : '' ?>>
                                    <?= $st ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit">Save</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php if (!$items): ?>
        <p>No appointments found.</p>
    <?php endif; ?>
</div>

<?php require 'app/views/partials/footer.php'; ?>
