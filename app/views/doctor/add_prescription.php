<?php
$title = 'Add Prescription';
require 'app/views/partials/header.php';
?>
<h1>Add Prescription</h1>

<div class="panel narrow">
    <form method="post" action="?action=add_prescription">
        <label>Patient</label>
        <select name="patient_id" required>
            <option value="">Select Patient</option>
            <?php foreach ($patients as $p): ?>
                <option value="<?= $p['id'] ?>">
                    <?= htmlspecialchars($p['full_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Diagnosis</label>
        <input name="diagnosis" required>

        <label>Medicines</label>
        <textarea name="medicines" rows="5" required></textarea>

        <label>Instructions</label>
        <textarea name="instructions" rows="4"></textarea>

        <button type="submit">Save Prescription</button>
    </form>
</div>

<?php require 'app/views/partials/footer.php'; ?>
