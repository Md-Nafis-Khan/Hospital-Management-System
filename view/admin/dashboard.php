<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "admin") {
    header("Location: ../../controller/auth/logout.php");
    exit;
}

$users = $_SESSION["admin_users"] ?? array();
$leaves = $_SESSION["admin_leaves"] ?? array();
$user_count = $_SESSION["admin_user_count"] ?? 0;
$appointment_count = $_SESSION["admin_appointment_count"] ?? 0;
$pending_leave_count = $_SESSION["admin_pending_leave_count"] ?? 0;

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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/portal.css">
</head>

<body>
    <header>
        <div>
            <strong>Care Plus Hospital</strong>
            <span>Administrator dashboard</span>
        </div>
        <div>
            <?php echo htmlspecialchars($_SESSION["user_name"]); ?> |
            <a href="../../controller/auth/logout.php">Logout</a>
        </div>
    </header>

    <main>
        <h1>Administrator dashboard</h1>

        <?php if ($message != "") { ?>
            <div class="notice <?php echo htmlspecialchars($message_type); ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
            <script>
                alert("<?php echo htmlspecialchars($message, ENT_QUOTES); ?>");
            </script>
        <?php } ?>

        <nav class="tabs">
            <button class="tab-link active" data-tab="tab-overview">Overview</button>
            <button class="tab-link" data-tab="tab-users">Users</button>
            <button class="tab-link" data-tab="tab-leaves">Leave Requests</button>
        </nav>

        <section id="tab-overview" class="tab-section active">
            <section class="stats">
                <div>
                    <b><?php echo (int)$user_count; ?></b>
                    <span>Users</span>
                </div>
                <div>
                    <b><?php echo (int)$appointment_count; ?></b>
                    <span>Appointments</span>
                </div>
                <div>
                    <b><?php echo (int)$pending_leave_count; ?></b>
                    <span>Pending leaves</span>
                </div>
            </section>
        </section>

        <section id="tab-users" class="tab-section">
            <section class="card">
                <h2>Add new user</h2>

                <form id="user-form"
                      action="../../controller/admin/create_user.php"
                      method="post"
                      class="grid">

                    <label>
                        Full name
                        <input type="text" id="new-user-name" name="name">
                    </label>

                    <label>
                        Email
                        <input type="email" id="new-user-email" name="email">
                    </label>

                    <label>
                        Password
                        <input type="password" id="new-user-password" name="password">
                    </label>

                    <label>
                        Role
                        <select id="new-user-role" name="role">
                            <option value="">Select role</option>
                            <option value="patient">Patient</option>
                            <option value="doctor">Doctor</option>
                            <option value="admin">Admin</option>
                        </select>
                    </label>

                    <button type="submit">Add user</button>
                </form>
            </section>

            <section class="card">
                <h2>Users</h2>

                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($users as $user) { ?>
                            <tr>
                                <td colspan="4">
                                    <form class="grid user-form"
                                          action="../../controller/admin/update_user.php"
                                          method="post"
                                          onsubmit="return validateUserUpdate(this);">

                                        <input type="hidden" name="user_id"
                                               value="<?php echo (int)$user["id"]; ?>">

                                        <label>
                                            Name
                                            <input type="text" name="name"
                                                   value="<?php echo htmlspecialchars($user["name"]); ?>">
                                        </label>

                                        <label>
                                            Email
                                            <input type="email" name="email"
                                                   value="<?php echo htmlspecialchars($user["email"]); ?>">
                                        </label>

                                        <label>
                                            Role
                                            <select name="role">
                                                <option value="patient" <?php echo $user["role"] == "patient" ? "selected" : ""; ?>>Patient</option>
                                                <option value="doctor" <?php echo $user["role"] == "doctor" ? "selected" : ""; ?>>Doctor</option>
                                                <option value="admin" <?php echo $user["role"] == "admin" ? "selected" : ""; ?>>Admin</option>
                                            </select>
                                        </label>

                                        <button type="submit">Update</button>

                                        <button type="button" class="danger"
                                                onclick="deleteUser(<?php echo (int)$user["id"]; ?>)">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>

                        <?php if (count($users) == 0) { ?>
                            <tr>
                                <td colspan="4">No users found.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </section>
        </section>

        <section id="tab-leaves" class="tab-section">
            <section class="card">
                <h2>Leave applications</h2>

                <table>
                    <thead>
                        <tr>
                            <th>Doctor</th>
                            <th>Dates</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (count($leaves) == 0) { ?>
                            <tr>
                                <td colspan="5">No leave applications.</td>
                            </tr>
                        <?php } ?>

                        <?php foreach ($leaves as $leave) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($leave["doctor_name"]); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($leave["start_date"]); ?>
                                    to
                                    <?php echo htmlspecialchars($leave["end_date"]); ?>
                                </td>
                                <td><?php echo htmlspecialchars($leave["reason"]); ?></td>
                                <td>
                                    <span class="status <?php echo htmlspecialchars($leave["status"]); ?>">
                                        <?php echo htmlspecialchars($leave["status"]); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($leave["status"] == "pending") { ?>
                                        <form class="inline table-form"
                                              action="../../controller/admin/review_leave.php"
                                              method="post"
                                              onsubmit="return reviewLeave('approve');">
                                            <input type="hidden" name="leave_id"
                                                   value="<?php echo (int)$leave["id"]; ?>">
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit">Approve</button>
                                        </form>

                                        <form class="inline table-form"
                                              action="../../controller/admin/review_leave.php"
                                              method="post"
                                              onsubmit="return reviewLeave('reject');">
                                            <input type="hidden" name="leave_id"
                                                   value="<?php echo (int)$leave["id"]; ?>">
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="danger">Reject</button>
                                        </form>
                                    <?php } else { ?>
                                        -
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </section>
        </section>
    </main>

    <script src="../assets/js/nav.js"></script>
    <script src="../assets/js/validation.js"></script>
    <script src="../assets/js/admin.js"></script>
</body>

</html>
