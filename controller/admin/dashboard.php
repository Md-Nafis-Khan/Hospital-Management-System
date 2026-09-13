<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "admin") {
    header("Location: ../../view/auth/login.php");
    exit;
}

require "../../model/database/database.php";
require "../../model/admin/user.php";
require "../../model/admin/dashboard.php";

$_SESSION["admin_users"] = get_all_users($conn);
$_SESSION["admin_leaves"] = get_all_leaves($conn);
$_SESSION["admin_user_count"] = count(get_all_users($conn));
$_SESSION["admin_appointment_count"] = count_appointments($conn);
$_SESSION["admin_pending_leave_count"] = count_pending_leaves($conn);

header("Location: ../../view/admin/dashboard.php");
exit;
?>
