<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "patient") {
    header("Location: ../../controller/auth/logout.php");
    exit;
}

$doctors = $_SESSION["patient_doctors"] ?? array();
$appointments = $_SESSION["patient_appointments"] ?? array();

$message = $_SESSION["message"] ?? "";
$message_type = $_SESSION["message_type"] ?? "";
unset($_SESSION["message"]);
unset($_SESSION["message_type"]);

$edit = $_SESSION["edit_appointment"] ?? array();
unset($_SESSION["edit_appointment"]);

$edit_mode = !empty($edit);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="../assets/css/portal.css">
</head>

<body>
    <header>
        <div>
            <strong>Care Plus Hospital</strong>
            <span>Patient dashboard</span>
        </div>
        <div>
            <?php echo htmlspecialchars($_SESSION["user_name"]); ?> |
            <a href="../../controller/auth/logout.php">Logout</a>
        </div>
    </header>

    <main>
        <h1>Appointments</h1>

        <?php if ($message != "") { ?>
            <div class="notice <?php echo htmlspecialchars($message_type); ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
            <script>
                alert("<?php echo htmlspecialchars($message, ENT_QUOTES); ?>");
            </script>
        <?php } ?>

        <nav class="tabs">
            <button class="tab-link active" data-tab="tab-book">Book Appointment</button>
            <button class="tab-link" data-tab="tab-my">My Appointments</button>
        </nav>

        <section id="tab-book" class="tab-section active">
            <section class="card">
                <h2>
                    <?php echo $edit_mode ? "Edit appointment" : "Book an appointment"; ?>
                </h2>

                <form id="appointment-form"
                      action="<?php echo $edit_mode ? "../../controller/patient/update_appointment.php" : "../../controller/patient/book_appointment.php"; ?>"
                      method="post"
                      class="grid">

                    <?php if ($edit_mode) { ?>
                        <input type="hidden" name="appointment_id"
                               value="<?php echo (int)$edit["id"]; ?>">
                    <?php } ?>

                    <label>
                        Doctor
                        <select name="doctor_id" id="doctor-id" <?php echo $edit_mode ? "disabled" : ""; ?>>
                            <option value="">Choose doctor</option>
                            <?php foreach ($doctors as $doctor) { ?>
                                <option value="<?php echo (int)$doctor["id"]; ?>"
                                    <?php echo ($edit_mode && $doctor["id"] == $edit["doctor_id"]) ? "selected" : ""; ?>>
                                    <?php echo htmlspecialchars($doctor["name"]); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </label>

                    <?php if ($edit_mode) { ?>
                        <input type="hidden" name="doctor_id" value="<?php echo (int)$edit["doctor_id"]; ?>">
                    <?php } ?>

                    <label>
                        Date
                        <input type="date" name="date" id="appointment-date"
                               value="<?php echo $edit_mode ? htmlspecialchars($edit["appointment_date"]) : ""; ?>">
                    </label>

                    <label>
                        Time
                        <input type="time" name="time" id="appointment-time"
                               value="<?php echo $edit_mode ? htmlspecialchars($edit["appointment_time"]) : ""; ?>">
                        <small id="availability-status"></small>
                    </label>

                    <label class="wide">
                        Notes
                        <textarea name="notes" id="appointment-notes" rows="3"><?php
                            echo $edit_mode ? htmlspecialchars($edit["notes"]) : "";
                        ?></textarea>
                    </label>

                    <button type="submit">
                        <?php echo $edit_mode ? "Update appointment" : "Book appointment"; ?>
                    </button>

                    <?php if ($edit_mode) { ?>
                        <button type="button" class="danger"
                                onclick="window.location.href='../../controller/patient/dashboard.php'">
                            Cancel edit
                        </button>
                    <?php } ?>
                </form>
            </section>
        </section>

        <section id="tab-my" class="tab-section">
            <section class="card">
                <h2>My appointments</h2>

                <table>
                    <thead>
                        <tr>
                            <th>Doctor</th>
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
                                <td><?php echo htmlspecialchars($appointment["doctor_name"]); ?></td>
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
                                        <div class="inline">
                                            <a class="button-link"
                                               href="../../controller/patient/edit_appointment.php?id=<?php echo (int)$appointment["id"]; ?>">
                                                Edit
                                            </a>

                                            <form class="table-form"
                                                  action="../../controller/patient/delete_appointment.php"
                                                  method="post"
                                                  onsubmit="return confirmCancelAppointment();">
                                                <input type="hidden" name="appointment_id"
                                                       value="<?php echo (int)$appointment["id"]; ?>">
                                                <button type="submit" class="danger">Cancel</button>
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
    <script src="../assets/js/patient.js"></script>
</body>

</html>
