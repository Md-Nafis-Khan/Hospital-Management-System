<?php
$title = 'Book Appointment';
require 'app/views/partials/header.php';
?>
<h1>Book Appointment</h1>

<div class="panel narrow">
    <form method="post" action="?action=book_appointment">
        <label>Doctor</label>
        <select name="doctor_id" required>
            <option value="">Select Doctor</option>
            <?php foreach ($doctors as $d): ?>
                <option value="<?= $d['id'] ?>">
                    <?= htmlspecialchars($d['full_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Date</label>
        <input type="date" name="appointment_date" min="<?= date('Y-m-d') ?>" required>

        <label>Time</label>
        <input type="time" name="appointment_time" required>

        <label>Reason</label>
        <textarea name="reason" rows="4"></textarea>

        <button type="submit">Book Appointment</button>
    </form>
</div>

<?php require 'app/views/partials/footer.php'; ?>
