<?php
$isDoctor = $accountType === 'Doctor';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarePlus Hospital - <?= $isDoctor ? 'Doctor' : 'Patient' ?> Registration</title>
    <link rel="stylesheet" href="public/assets/css/global.css">
    <link rel="stylesheet" href="public/assets/css/auth.css">
    <link rel="stylesheet" href="public/assets/css/forms.css">
    <link rel="stylesheet" href="public/assets/css/responsive.css">
    <script src="public/assets/js/validation.js" defer></script>
</head>
<body class="auth-page signup-page">
    <div class="signup-wrapper">
        <section class="auth-card signup-card">
            <div class="brand-logo centered">✚ <strong>CarePlus</strong><span>HOSPITAL</span></div>
            <div class="account-badge <?= $isDoctor ? 'doctor' : 'patient' ?>">
                <?= $isDoctor ? 'Doctor Registration' : 'Patient Registration' ?>
            </div>
            <h2>Create <?= $isDoctor ? 'Doctor' : 'Patient' ?> Account</h2>
            <p class="auth-subtitle">Enter the required information below.</p>

            <?php if ($errors): ?>
                <div class="alert error">
                    <?php foreach ($errors as $error): ?>
                        <div><?= htmlspecialchars($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="index.php?action=signup" class="form-grid" novalidate>
                <input type="hidden" name="account_type" value="<?= htmlspecialchars($accountType) ?>">

                <div class="form-group">
                    <label for="full_name">Full Name *</label>
                    <input id="full_name" name="full_name" value="<?= htmlspecialchars($old['full_name'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone No. *</label>
                    <input id="phone" name="phone" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="username">Username *</label>
                    <input id="username" name="username" value="<?= htmlspecialchars($old['username'] ?? '') ?>" required>
                    <span id="usernameMessage"></span>
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input id="email" type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Password *</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password *</label>
                    <input id="confirm_password" type="password" name="confirm_password" required>
                </div>

                <div class="form-group">
                    <label for="date_of_birth">Date of Birth</label>
                    <input id="date_of_birth" type="date" name="date_of_birth" value="<?= htmlspecialchars($old['date_of_birth'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="gender">Gender *</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <?php foreach (['Male', 'Female', 'Other'] as $gender): ?>
                            <option value="<?= $gender ?>" <?= (($old['gender'] ?? '') === $gender) ? 'selected' : '' ?>><?= $gender ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php if ($isDoctor): ?>
                    <div class="form-group full">
                        <label for="specialization">Specialization *</label>
                        <input id="specialization" name="specialization" value="<?= htmlspecialchars($old['specialization'] ?? '') ?>" placeholder="e.g. Cardiology, Neurology, General Medicine" required>
                    </div>
                <?php endif; ?>

                <div class="form-group full">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" rows="3"><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
                </div>

                <div class="form-actions full">
                    <button type="submit" class="primary-btn">Create <?= $isDoctor ? 'Doctor' : 'Patient' ?> Account</button>
                </div>
            </form>

            <div class="registration-switch">
                <?php if ($isDoctor): ?>
                    Need a patient account? <a href="index.php?action=signup&amp;type=Patient">Register as Patient</a>
                <?php else: ?>
                    Need a doctor account? <a href="index.php?action=signup&amp;type=Doctor">Register as Doctor</a>
                <?php endif; ?>
            </div>
            <p class="centered"><a href="index.php?action=login">← Back to Login</a></p>
        </section>
    </div>
</body>
</html>
