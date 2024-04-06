<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["username"]) || empty($_SESSION["username"])) {
    // Redirect to login page if not logged in
    header("Location: login.html");
    exit;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if current password, new username, and confirm password are provided
    if (isset($_POST["current_password"], $_POST["new_username"], $_POST["confirm_password"]) && 
        !empty($_POST["current_password"]) && !empty($_POST["new_username"]) && !empty($_POST["confirm_password"])) {

        // Sanitize input
        $currentPassword = htmlspecialchars($_POST["current_password"]);
        $newUsername = htmlspecialchars($_POST["new_username"]);
        $confirmPassword = htmlspecialchars($_POST["confirm_password"]);

        // Database connection details
        $server = "db";
        $user = "admin";
        $pw = "pwd";
        $db = "rc";

        $connect = mysqli_connect($server, $user, $pw, $db) or die('Could not connect to the database server' . mysqli_connect_error());

        // Prepare SQL statement to retrieve hashed password for the current user
        $stmt = $connect->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $_SESSION["username"]);
        $stmt->execute();
        $stmt->store_result();

        // Bind result variables
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        // Verify password
        if (password_verify($currentPassword, $hashedPassword)) {
            // Update the username in the database
            $updateStmt = $connect->prepare("UPDATE users SET username = ? WHERE username = ?");
            $updateStmt->bind_param("ss", $newUsername, $_SESSION["username"]);
            if ($updateStmt->execute()) {
                $_SESSION["username"] = $newUsername; // Update session with new username
            } else {
                $error = "Error updating username: " . $connect->error;
            }
            $updateStmt->close();
        } else {
            $error = "Incorrect current password.";
        }

        // Close statement and connection
        $stmt->close();
        $connect->close();
    } else {
        $error = "Please fill in all fields.";
    }
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Username</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main>
        <?php if (isset($error)) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required>
            <label for="new_username">New Username:</label>
            <input type="text" id="new_username" name="new_username" required>
            <label for="confirm_password">Confirm New Username:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <button type="submit">Change Username</button>
        </form>
        <form action="dashboard.php">
            <button type="submit">Back to Dashboard</button>
        </form>
    </main>
</body>
</html>
