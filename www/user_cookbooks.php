<?php
session_start();
if (isset($_SESSION["username"]) && $_SESSION["loggedin"] == TRUE) {
    // echo "Welcome, " . $_SESSION["username"];
} else {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cookbooks</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <h1>Welcome to Your Cookbooks, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>!</h1>
    </header>
    <button onclick="window.location.href = 'dashboard.php';">Return to dashboard</button>
</body>

</html>
