<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles/portal.css">
</head>

<body>
    <header>
        <div><strong>Care Plus Hospital</strong><span>Administrator dashboard</span></div>
        <div><?php echo $_SESSION["user_name"]; ?> | <a href="index.php?page=logout">Logout</a></div>
    </header>
    <main>
        <h1>Administrator dashboard</h1>
        <p id="message"></p>

        <nav class="tabs">
            <button class="tab-link active" data-tab="tab-overview">Overview</button>
            <button class="tab-link" data-tab="tab-users">Users</button>
            <button class="tab-link" data-tab="tab-leaves">Leave Requests</button>
        </nav>

        <section id="tab-overview" class="tab-section active">
            <section class="stats">
                <div><b id="user-count">0</b><span>Users</span></div>
                <div><b id="appointment-count">0</b><span>Appointments</span></div>
                <div><b id="leave-count">0</b><span>Pending leaves</span></div>
            </section>
        </section>

        <section id="tab-users" class="tab-section">
            <section class="card">
                <h2>Add new user</h2>
                <form id="user-form" class="grid">
                    <label>Full name<input type="text" id="new-user-name" required></label>
                    <label>Email<input type="email" id="new-user-email" required></label>
                    <label>Password<input type="password" id="new-user-password" required></label>
                    <label>Role
                        <select id="new-user-role" required>
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
                    <tbody id="user-list"></tbody>
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
                    <tbody id="leave-list"></tbody>
                </table>
            </section>
        </section>
    </main>
    <script src="scripts/nav.js"></script>
    <script src="scripts/admin-ajax.js"></script>
</body>

</html>