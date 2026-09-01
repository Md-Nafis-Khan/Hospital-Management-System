<?php
$title = 'My Profile';
require 'app/views/partials/header.php';
?>
<h1>My Profile</h1>

<div class="panel">
    <form method="post" action="?action=patient_profile_save" class="grid">
        <div>
            <label>Full Name</label>
            <input name="full_name" value="<?= htmlspecialchars($profile['full_name'] ?? '') ?>" required>
        </div>
        <div>
            <label>Username</label>
            <input value="<?= htmlspecialchars($profile['username'] ?? '') ?>" disabled>
        </div>
        <div>
            <label>Phone</label>
            <input name="phone" value="<?= htmlspecialchars($profile['phone'] ?? '') ?>">
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($profile['email'] ?? '') ?>" required>
        </div>
        <div>
            <label>Date of Birth</label>
            <input type="date" name="date_of_birth" value="<?= htmlspecialchars($profile['date_of_birth'] ?? '') ?>">
        </div>
        <div>
            <label>Gender</label>
            <select name="gender">
                <?php foreach (['Male', 'Female', 'Other'] as $g): ?>
                    <option value="<?= $g ?>" <?= (($profile['gender'] ?? '') === $g) ? 'selected' : '' ?>>
                        <?= $g ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Blood Group</label>
            <input name="blood_group" value="<?= htmlspecialchars($profile['blood_group'] ?? '') ?>">
        </div>
        <div>
            <label>Emergency Contact</label>
            <input name="emergency_contact" value="<?= htmlspecialchars($profile['emergency_contact'] ?? '') ?>">
        </div>
        <div class="full">
            <label>Address</label>
            <textarea name="address" rows="4"><?= htmlspecialchars($profile['address'] ?? '') ?></textarea>
        </div>
        <div class="full">
            <button type="submit">Save Changes</button>
        </div>
    </form>
</div>

<?php require 'app/views/partials/footer.php'; ?>
