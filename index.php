<?php

session_start();

require 'config/config.php';
require 'config/database.php';
require 'app/models/User.php';
require 'app/models/Patient.php';
require 'app/models/Appointment.php';
require 'app/models/Prescription.php';
require 'app/controllers/AuthController.php';
require 'app/controllers/PatientController.php';
require 'app/controllers/DoctorController.php';
require 'app/controllers/AdminController.php';

$action = $_GET['action'] ?? 'login';

if (!isset($_SESSION['user_id']) && $action !== 'login' && $action !== 'signup') {
    $action = 'login';
}


switch ($action) {
    case 'login':
        loginUser();
        break;

    case 'signup':
        signupUser();
        break;

    case 'logout':
        logoutUser();
        break;

    case 'patient_dashboard':
        patientDashboard();
        break;

    case 'patient_profile':
        patientProfile();
        break;

    case 'patient_profile_save':
        savePatientProfile();
        break;

    case 'book_appointment':
        bookAppointment();
        break;

    case 'appointments':
        patientAppointments();
        break;

    case 'cancel_appointment':
        cancelAppointment();
        break;

    case 'prescriptions':
        patientPrescriptions();
        break;

    case 'doctor_dashboard':
        doctorDashboard();
        break;

    case 'doctor_appointments':
        doctorAppointments();
        break;

    case 'doctor_status':
        updateAppointmentStatus();
        break;

    case 'add_prescription':
        addPrescription();
        break;

    case 'doctor_prescriptions':
        doctorPrescriptions();
        break;

    case 'admin_dashboard':
        adminDashboard();
        break;

    case 'admin_users':
        adminUsers();
        break;

    case 'admin_appointments':
        adminAppointments();
        break;

    case 'admin_delete_user':
        deleteUser();
        break;

    case 'check_username':
        checkUsername();
        break;

    default:
        loginUser();
        break;
        
}
