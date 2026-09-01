<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarePlus Hospital - Login</title>
    <link rel="stylesheet" href="public/assets/css/global.css">
    <link rel="stylesheet" href="public/assets/css/auth.css">
    <link rel="stylesheet" href="public/assets/css/forms.css">
    <link rel="stylesheet" href="public/assets/css/responsive.css">
    <script src="public/assets/js/validation.js" defer></script>
</head>
<body class="auth-page">
    <div class="auth-shell login-shell">
        <section class="auth-intro">
            <div class="brand-logo">✚ <strong>CarePlus</strong><span>HOSPITAL</span></div>
            <h1>Hospital Management<br>System</h1>
            <div class="intro-line"></div>
            <p>A complete solution to manage patients, appointments, doctors and hospital records efficiently.</p>
            <ul class="feature-list">
                <li><strong>Patient Management</strong><span>Register and manage patient records</span></li>
                <li><strong>Appointments</strong><span>Schedule and manage appointments</span></li>
                <li><strong>Doctor Management</strong><span>Manage doctors and clinical records</span></li>
                <li><strong>Reports</strong><span>Keep hospital information organized</span></li>
            </ul>
        </section>

        <section class="auth-card">
            <div class="auth-icon">✚</div>
            <h2>Welcome</h2>
            <p class="auth-subtitle">Login to your Hospital Management System</p>

            <?php if (!empty($_SESSION['flash'])): ?>
                <?php $flash = $_SESSION['flash']; unset($_SESSION['flash']); ?>
                <div class="alert <?= htmlspecialchars($flash[1]) ?>">
                    <?= htmlspecialchars($flash[0]) ?>
                </div>
            <?php endif; ?>

            <?php if ($errors): ?>
                <div class="alert error">
                    <?php foreach ($errors as $error): ?>
                        <div><?= htmlspecialchars($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="index.php?action=login" novalidate>
                <label for="username">Username</label>
                <input id="username" name="username" value="<?= htmlspecialchars($username) ?>" placeholder="Enter your username" required>

                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="Enter your password" required>

                <button type="submit" class="primary-btn">Login</button>
            </form>

            <div class="or-divider"><span>or</span></div>

            <div class="registration-options">
                <a class="outline-btn" href="index.php?action=signup&amp;type=Patient">Create Patient Account</a>
                <a class="outline-btn doctor-btn" href="index.php?action=signup&amp;type=Doctor">Create Doctor Account</a>
            </div>

            <small class="demo-note">Demo: admin / doctor / patient &nbsp;•&nbsp; Password: password</small>
        </section>
    </div>
</body>
</html>
