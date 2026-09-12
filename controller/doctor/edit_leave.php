<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "doctor") {
    header("Location: ../../view/auth/login.php");
    exit;
}

require "../../model/database/database.php";
require "../../model/doctor/leave.php";

$id = (int)($_GET["id"] ?? 0);
$leave = get_doctor_leave($conn, $id, $_SESSION["user_id"]);

if ($leave && $leave["status"] == "pending") {
    $_SESSION["edit_leave"] = $leave;
} else {
    $_SESSION["message"] = "This leave application cannot be edited.";
    $_SESSION["message_type"] = "error";
}

header("Location: dashboard.php");
exit;
?>
