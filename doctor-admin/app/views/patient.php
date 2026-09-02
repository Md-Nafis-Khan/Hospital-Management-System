<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="styles/portal.css">
</head>

<body>
    <header>
        <div><strong>Care Plus Hospital</strong><span>Patient dashboard</span></div>
        <div><?php echo $_SESSION["user_name"]; ?> | <a href="index.php?page=logout">Logout</a></div>
    </header>
    <main>
        <h1>Appointments</h1>
        <p id="message"></p>

        <nav class="tabs">
            <button class="tab-link active" data-tab="tab-book">Book Appointment</button>
            <button class="tab-link" data-tab="tab-my">My Appointments</button>
        </nav>

        <section id="tab-book" class="tab-section active">
            <section class="card">
                <h2 id="appointment-form-title">Book an appointment</h2>
                <form id="appointment-form" class="grid">
                    <input type="hidden" id="appointment-id" value="">
                    <label>Doctor
                        <select id="doctor-id" required>
                            <option value="">Choose doctor</option>
                        </select>
                    </label>
                    <label>Date<input type="date" id="appointment-date" required></label>
                    <label>Time<input type="time" id="appointment-time" required></label>
                    <label class="wide">Notes<textarea id="appointment-notes" rows="3"></textarea></label>
                    <button type="submit" id="appointment-submit">Book appointment</button>
                    <button type="button" id="appointment-cancel" style="display:none;">Cancel edit</button>
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
                    <tbody id="appointment-list"></tbody>
                </table>
            </section>
        </section>
    </main>
    <script src="scripts/nav.js"></script>
    <script src="scripts/patient-ajax.js"></script>
</body>

</html>