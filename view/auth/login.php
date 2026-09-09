<?php

session_start();

if (isset($_SESSION["user_role"])) {
    header("Location: ../../controller/" . $_SESSION["user_role"] . "/dashboard.php");
    exit;
}

$message = $_SESSION["message"] ?? "";
$message_type = $_SESSION["message_type"] ?? "";
unset($_SESSION["message"]);
unset($_SESSION["message_type"]);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Plus Hospital</title>
    <link rel="stylesheet" href="../assets/css/index.css">
</head>

<body>
    <div class="puro-page">
        <div class="chobi-ongsho">
            <img src="../assets/images/hospital.jpg" alt="Hospital">
        </div>

        <div class="form-ongsho">
            <form id="login-form" action="../../controller/auth/login.php" method="post">
                <h1>Care Plus Hospital</h1>
                <p>Login to your account</p>

                <?php if ($message != "") { ?>
                    <div class="notice <?php echo htmlspecialchars($message_type); ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                    <script>
                        alert("<?php echo htmlspecialchars($message, ENT_QUOTES); ?>");
                    </script>
                <?php } ?>

                <label for="login-email">Email</label>
                <input type="email" id="login-email" name="email">

                <label for="login-password">Password</label>
                <input type="password" id="login-password" name="password">

                <button type="submit">Login</button>

                <p class="registration-link">
                    New user? <a href="#register" id="show-register">Register here</a>
                </p>
            </form>

            <form id="register-form" action="../../controller/auth/register.php" method="post" style="display: none;">
                <h1>Create account</h1>
                <p>Register as a patient or doctor</p>

                <label for="register-username">Username</label>
                <input type="text" id="register-username" name="username">
                <small id="username-status"></small>

                <label for="register-name">Full name</label>
                <input type="text" id="register-name" name="name">

                <label for="register-email">Email</label>
                <input type="email" id="register-email" name="email">
                <small id="email-status"></small>

                <label for="register-role">Role</label>
                <select id="register-role" name="role">
                    <option value="">Select role</option>
                    <option value="patient">Patient</option>
                    <option value="doctor">Doctor</option>
                </select>

                <label for="register-password">Password</label>
                <input type="password" id="register-password" name="password">

                <label for="confirm-password">Confirm password</label>
                <input type="password" id="confirm-password" name="confirm_password">

                <button type="submit">Register</button>

                <p class="login-link">
                    Already registered? <a href="#" id="show-login">Login here</a>
                </p>
            </form>
        </div>
    </div>

    <script src="../assets/js/validation.js"></script>
    <script src="../assets/js/login.js"></script>
</body>

</html>
