<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Plus Hospital</title>
    <link rel="stylesheet" href="styles/index.css">
</head>

<body>
    <div class="puro-page">
        <div class="chobi-ongsho"><img src="assets/hospital.jpg" alt="Hospital"></div>
        <div class="form-ongsho">
            <form id="login-form">
                <h1>Care Plus Hospital</h1>
                <p>Login to your account</p>
                <label>Email</label><input type="email" id="login-email" required>
                <label>Password</label><input type="password" id="login-password" required>
                <button type="submit">Login</button>
                <p id="login-message"></p>
                <p class="registration-link">New user? <a href="#" id="show-register">Register here</a></p>
            </form>
            <form id="register-form" style="display: none;">
                <h1>Create account</h1>
                <p>Register as a patient or doctor</p>
                <label>Full name</label><input type="text" id="register-name" required>
                <label>Email</label><input type="email" id="register-email" required>
                <label>Role</label><select id="register-role" required>
                    <option value="">Select role</option>
                    <option value="patient">Patient</option>
                    <option value="doctor">Doctor</option>
                </select>
                <label>Password</label><input type="password" id="register-password" required>
                <label>Confirm password</label><input type="password" id="confirm-password" required>
                <button type="submit">Register</button>
                <p id="register-message"></p>
                <p class="login-link">Already registered? <a href="#" id="show-login">Login here</a></p>
            </form>
        </div>
    </div>
    <script src="scripts/login-ajax.js"></script>
</body>

</html>