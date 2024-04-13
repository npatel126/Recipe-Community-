<?php
session_start();

// Check if user is logged in
if (isset($_SESSION["username"]) && $_SESSION["loggedin"] == TRUE) {
    //echo "Welcome, " . $_SESSION["username"];
} else {
    header("Location: index.php");
    exit;
}

// Toggle dark mode session variable when form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $_SESSION['darkmode'] = !$_SESSION['darkmode']; // Toggle the darkmode variable
}

// Toggle style session variable
if ($_SESSION['darkmode']) {
    $style = "css/settings(dark).css";
} else {
    $style = "css/settings.css";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="<?php echo $style; ?>">
</head>
<body>
<header>
        <h1>Account Settings</h1>
    </header>
    <main>
        <div class="settings-options">
            <button onclick="window.location.href='change_password.php'">Change Password</button>
            <button onclick="window.location.href='change_username.php'">Change Username</button>
            <button onclick="window.location.href='change_name.php'">Change Name</button>
            <form method="post">
                <button type="submit">Toggle Darkmode</button>
            </form>
        </div>
        <form action="dashboard.php">
            <button type="submit">Back to Dashboard</button>
        </form>
    </main>
</body>
</html>
