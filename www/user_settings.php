<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["username"]) || empty($_SESSION["username"])) {
    // Redirect to login page if not logged in
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
        <h1>Account Settings</h1>
    </header>
    <main>
        <h1>Settings</h1>
        <div class="settings-options">
            <button onclick="window.location.href='change_password.php'">Change Password</button>
            <button onclick="window.location.href='change_username.php'">Change Username</button>
        </div>
        <form action="dashboard.php">
            <button type="submit">Back to Dashboard</button>
        </form>
    </main>
</body>
</html>
