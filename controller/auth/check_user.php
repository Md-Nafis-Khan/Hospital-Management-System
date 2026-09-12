<?php
session_start();

require "../../model/database/database.php";
require "../../model/auth/user.php";

$username = trim($_POST["username"] ?? "");
$email = trim($_POST["email"] ?? "");

$existing = check_username_or_email($conn, $username, $email);

$response = array(
    "username_exists" => false,
    "email_exists" => false
);

if ($existing) {
    if ($existing["username"] == $username && $username != "") {
        $response["username_exists"] = true;
    }
    if ($existing["email"] == $email && $email != "") {
        $response["email_exists"] = true;
    }
}

header("Content-Type: application/json");
echo json_encode($response);
exit;
?>
