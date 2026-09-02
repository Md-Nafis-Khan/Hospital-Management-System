<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="styles/portal.css">
</head>

<body>
    <header>
        <div><strong>Care Plus Hospital</strong><span>Doctor dashboard</span></div>
        <div><?php echo $_SESSION["user_name"]; ?> | <a href="index.php?page=logout">Logout</a></div>
    </header>
    <main>
        <h1>Doctor dashboard</h1>
        <p id="message"></p>

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
                    <tbody id="appointment-list"></tbody>
                </table>
            </section>
        </section>

        <section id="tab-leaves" class="tab-section">
            <section class="card">
                <h2 id="leave-form-title">Apply for leave</h2>
                <form id="leave-form" class="grid">
                    <input type="hidden" id="leave-id" value="">
                    <label>Start date<input type="date" id="start-date" required></label>
                    <label>End date<input type="date" id="end-date" required></label>
                    <label class="wide">Reason<textarea id="leave-reason" rows="3" required></textarea></label>
                    <button type="submit" id="leave-submit">Submit leave request</button>
                    <button type="button" id="leave-cancel" style="display:none;">Cancel edit</button>
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
                    <tbody id="leave-list"></tbody>
                </table>
            </section>
        </section>
    </main>
    <script src="scripts/nav.js"></script>
    <script src="scripts/doctor-ajax.js"></script>
</body>

</html>