<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "doctor") {
    header("Location: ../../controller/auth/logout.php");
    exit;
}

$appointments = $_SESSION["doctor_appointments"] ?? array();
$leaves = $_SESSION["doctor_leaves"] ?? array();

$message = $_SESSION["message"] ?? "";
$message_type = $_SESSION["message_type"] ?? "";
unset($_SESSION["message"]);
unset($_SESSION["message_type"]);

$edit = $_SESSION["edit_leave"] ?? array();
unset($_SESSION["edit_leave"]);

$edit_mode = !empty($edit);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="../assets/css/portal.css">
</head>

<body>
    <header>
        <div>
            <strong>Care Plus Hospital</strong>
            <span>Doctor dashboard</span>
        </div>
        <div>
            <?php echo htmlspecialchars($_SESSION["user_name"]); ?> |
            <a href="../../controller/auth/logout.php">Logout</a>
        </div>
    </header>

    <main>
        <h1>Doctor dashboard</h1>

        <?php if ($message != "") { ?>
            <div class="notice <?php echo htmlspecialchars($message_type); ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
            <script>
                alert("<?php echo htmlspecialchars($message, ENT_QUOTES); ?>");
            </script>
        <?php } ?>

        <nav class="tabs">
            <button class="tab-link active" data-tab="tab-appointments">Appointments</button>
            <button class="tab-link" data-tab="tab-leaves">Leave Applications</button>
        </nav>

        <section id="tab-appointments" class="tab-section active">
            <section class="card">
                <h2>Appointments</h2>

                <table>
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Notes</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (count($appointments) == 0) { ?>
                            <tr>
                                <td colspan="6">No appointments yet.</td>
                            </tr>
                        <?php } ?>

                        <?php foreach ($appointments as $appointment) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($appointment["patient_name"]); ?></td>
                                <td><?php echo htmlspecialchars($appointment["appointment_date"]); ?></td>
                                <td><?php echo htmlspecialchars($appointment["appointment_time"]); ?></td>
                                <td><?php echo htmlspecialchars($appointment["notes"]); ?></td>
                                <td>
                                    <span class="status <?php echo htmlspecialchars($appointment["status"]); ?>">
                                        <?php echo htmlspecialchars($appointment["status"]); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($appointment["status"] == "pending") { ?>
                                        <button type="button" onclick="updateAppointmentStatus(<?php echo (int)$appointment["id"]; ?>, 'approved', this)">Approve</button>
                                        <button type="button" class="danger" onclick="updateAppointmentStatus(<?php echo (int)$appointment["id"]; ?>, 'cancelled', this)">Cancel</button>
                                    <?php } elseif ($appointment["status"] == "approved") { ?>
                                        <button type="button" onclick="updateAppointmentStatus(<?php echo (int)$appointment["id"]; ?>, 'completed', this)">Complete</button>
                                        <button type="button" class="danger" onclick="updateAppointmentStatus(<?php echo (int)$appointment["id"]; ?>, 'cancelled', this)">Cancel</button>
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

        <section id="tab-leaves" class="tab-section">
            <section class="card">
                <h2><?php echo $edit_mode ? "Edit leave application" : "Apply for leave"; ?></h2>

                <form id="leave-form"
                      action="<?php echo $edit_mode ? "../../controller/doctor/update_leave.php" : "../../controller/doctor/apply_leave.php"; ?>"
                      method="post"
                      class="grid">

                    <?php if ($edit_mode) { ?>
                        <input type="hidden" name="leave_id" value="<?php echo (int)$edit["id"]; ?>">
                    <?php } ?>

                    <label>
                        Start date
                        <input type="date" name="start_date" id="start-date"
                               value="<?php echo $edit_mode ? htmlspecialchars($edit["start_date"]) : ""; ?>">
                    </label>

                    <label>
                        End date
                        <input type="date" name="end_date" id="end-date"
                               value="<?php echo $edit_mode ? htmlspecialchars($edit["end_date"]) : ""; ?>">
                    </label>

                    <label class="wide">
                        Reason
                        <textarea name="reason" id="leave-reason" rows="3"><?php
                            echo $edit_mode ? htmlspecialchars($edit["reason"]) : "";
                        ?></textarea>
                    </label>

                    <button type="submit">
                        <?php echo $edit_mode ? "Update leave request" : "Submit leave request"; ?>
                    </button>

                    <?php if ($edit_mode) { ?>
                        <button type="button" class="danger"
                                onclick="window.location.href='../../controller/doctor/dashboard.php'">
                            Cancel edit
                        </button>
                    <?php } ?>
                </form>

                <h3>My leave applications</h3>

                <table>
                    <thead>
                        <tr>
                            <th>Start date</th>
                            <th>End date</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (count($leaves) == 0) { ?>
                            <tr>
                                <td colspan="5">No leave applications yet.</td>
                            </tr>
                        <?php } ?>

                        <?php foreach ($leaves as $leave) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($leave["start_date"]); ?></td>
                                <td><?php echo htmlspecialchars($leave["end_date"]); ?></td>
                                <td><?php echo htmlspecialchars($leave["reason"]); ?></td>
                                <td>
                                    <span class="status <?php echo htmlspecialchars($leave["status"]); ?>">
                                        <?php echo htmlspecialchars($leave["status"]); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($leave["status"] == "pending") { ?>
                                        <div class="inline">
                                            <a class="button-link"
                                               href="../../controller/doctor/edit_leave.php?id=<?php echo (int)$leave["id"]; ?>">
                                                Edit
                                            </a>

                                            <form class="table-form"
                                                  action="../../controller/doctor/delete_leave.php"
                                                  method="post"
                                                  onsubmit="return confirmDeleteLeave();">
                                                <input type="hidden" name="leave_id"
                                                       value="<?php echo (int)$leave["id"]; ?>">
                                                <button type="submit" class="danger">Delete</button>
                                            </form>
                                        </div>
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
    <script src="../assets/js/doctor.js"></script>
</body>

</html>
