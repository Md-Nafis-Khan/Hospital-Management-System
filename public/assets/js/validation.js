document.addEventListener('DOMContentLoaded', function () {
    var forms = document.querySelectorAll('form');

    forms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            var isValid = true;
            var message = '';

            if (form.matches('form[action*="action=login"]')) {
                var username = form.querySelector('[name="username"]');
                var password = form.querySelector('[name="password"]');

                if (username && username.value.trim() === '') {
                    message = 'Please enter your username.';
                    isValid = false;
                    username.focus();
                } else if (password && password.value === '') {
                    message = 'Please enter your password.';
                    isValid = false;
                    password.focus();
                }
            }

            if (form.matches('form[action*="action=signup"]')) {
                var fullName = form.querySelector('[name="full_name"]');
                var phone = form.querySelector('[name="phone"]');
                var signupUsername = form.querySelector('[name="username"]');
                var email = form.querySelector('[name="email"]');
                var signupPassword = form.querySelector('[name="password"]');
                var confirmPassword = form.querySelector('[name="confirm_password"]');
                var gender = form.querySelector('[name="gender"]');
                var specialization = form.querySelector('[name="specialization"]');

                if (fullName && fullName.value.trim().length < 2) {
                    message = 'Full name must contain at least 2 characters.';
                    isValid = false;
                    fullName.focus();
                } else if (phone && !/^01[3-9]\d{8}$/.test(phone.value.trim())) {
                    message = 'Enter a valid Bangladesh phone number, for example 01712345678.';
                    isValid = false;
                    phone.focus();
                } else if (signupUsername && !/^[A-Za-z0-9_]{3,20}$/.test(signupUsername.value.trim())) {
                    message = 'Username must be 3-20 characters and contain only letters, numbers, or underscores.';
                    isValid = false;
                    signupUsername.focus();
                } else if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
                    message = 'Please enter a valid email address.';
                    isValid = false;
                    email.focus();
                } else if (signupPassword && signupPassword.value.length < 6) {
                    message = 'Password must contain at least 6 characters.';
                    isValid = false;
                    signupPassword.focus();
                } else if (confirmPassword && signupPassword && confirmPassword.value !== signupPassword.value) {
                    message = 'Passwords do not match.';
                    isValid = false;
                    confirmPassword.focus();
                } else if (gender && gender.value === '') {
                    message = 'Please select your gender.';
                    isValid = false;
                    gender.focus();
                } else if (specialization && specialization.value.trim() === '') {
                    message = 'Please enter the doctor specialization.';
                    isValid = false;
                    specialization.focus();
                }
            }

            if (form.matches('form[action*="action=patient_profile_save"]')) {
                var profileName = form.querySelector('[name="full_name"]');
                var profileEmail = form.querySelector('[name="email"]');
                var profilePhone = form.querySelector('[name="phone"]');

                if (profileName && profileName.value.trim().length < 2) {
                    message = 'Full name must contain at least 2 characters.';
                    isValid = false;
                    profileName.focus();
                } else if (profilePhone && profilePhone.value.trim() !== '' && !/^01[3-9]\d{8}$/.test(profilePhone.value.trim())) {
                    message = 'Enter a valid Bangladesh phone number.';
                    isValid = false;
                    profilePhone.focus();
                } else if (profileEmail && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(profileEmail.value.trim())) {
                    message = 'Please enter a valid email address.';
                    isValid = false;
                    profileEmail.focus();
                }
            }

            if (form.matches('form[action*="action=book_appointment"]')) {
                var doctor = form.querySelector('[name="doctor_id"]');
                var appointmentDate = form.querySelector('[name="appointment_date"]');
                var appointmentTime = form.querySelector('[name="appointment_time"]');
                var reason = form.querySelector('[name="reason"]');

                if (doctor && doctor.value === '') {
                    message = 'Please select a doctor.';
                    isValid = false;
                    doctor.focus();
                } else if (appointmentDate && appointmentDate.value === '') {
                    message = 'Please select an appointment date.';
                    isValid = false;
                    appointmentDate.focus();
                } else if (appointmentDate && appointmentDate.value < new Date().toISOString().split('T')[0]) {
                    message = 'Appointment date cannot be in the past.';
                    isValid = false;
                    appointmentDate.focus();
                } else if (appointmentTime && appointmentTime.value === '') {
                    message = 'Please select an appointment time.';
                    isValid = false;
                    appointmentTime.focus();
                } else if (reason && reason.value.trim().length > 200) {
                    message = 'Reason must not exceed 200 characters.';
                    isValid = false;
                    reason.focus();
                }
            }

            if (form.matches('form[action*="action=add_prescription"]')) {
                var patient = form.querySelector('[name="patient_id"]');
                var diagnosis = form.querySelector('[name="diagnosis"]');
                var medicines = form.querySelector('[name="medicines"]');

                if (patient && patient.value === '') {
                    message = 'Please select a patient.';
                    isValid = false;
                    patient.focus();
                } else if (diagnosis && diagnosis.value.trim() === '') {
                    message = 'Please enter the diagnosis.';
                    isValid = false;
                    diagnosis.focus();
                } else if (medicines && medicines.value.trim() === '') {
                    message = 'Please enter the medicines.';
                    isValid = false;
                    medicines.focus();
                }
            }

            if (!isValid) {
                event.preventDefault();
                alert(message);
            }
        });
    });

    var dateFields = document.querySelectorAll('input[type="date"]');

    dateFields.forEach(function (field) {
        if (field.name === 'appointment_date') {
            var today = new Date();
            var year = today.getFullYear();
            var month = String(today.getMonth() + 1).padStart(2, '0');
            var day = String(today.getDate()).padStart(2, '0');

            field.min = year + '-' + month + '-' + day;
        }

        if (field.name === 'date_of_birth') {
            var today = new Date();
            var year = today.getFullYear();
            var month = String(today.getMonth() + 1).padStart(2, '0');
            var day = String(today.getDate()).padStart(2, '0');

            field.max = year + '-' + month + '-' + day;
        }
    });


    var confirmForms = document.querySelectorAll('form[data-confirm]');

    confirmForms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            var message = form.getAttribute('data-confirm');

            if (!confirm(message)) {
                event.preventDefault();
            }
        });
    });

    var password = document.querySelector('[name="password"]');
    var confirmPassword = document.querySelector('[name="confirm_password"]');

    if (password && confirmPassword) {
        confirmPassword.addEventListener('input', function () {
            if (confirmPassword.value !== password.value) {
                confirmPassword.setCustomValidity('Passwords do not match.');
            } else {
                confirmPassword.setCustomValidity('');
            }
        });
    }
});

const usernameInput = document.getElementById("username");
const usernameMessage = document.getElementById("usernameMessage");

if (usernameInput) {

    usernameInput.addEventListener("blur", function () {

        const username = usernameInput.value.trim();

        if (username === "") {
            usernameMessage.textContent = "";
            return;
        }

        fetch(
            "index.php?action=check_username&username="
            + encodeURIComponent(username)
        )
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {

                usernameMessage.textContent = data.message;

                if (data.exists) {
                    usernameMessage.className = "error-message";
                } else {
                    usernameMessage.className = "success-message";
                }
            })
            .catch(function () {

                usernameMessage.textContent =
                    "Unable to check username.";

                usernameMessage.className = "error-message";
            });
    });
}