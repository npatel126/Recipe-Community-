<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["username"]) || empty($_SESSION["username"])) {
    // Redirect to login page if not logged in
    header("Location: login.html");
    exit;
}

// Toggle style session variable
if ($_SESSION['darkmode']) {
    $style = "css/login_register(dark).css";
} else {
    $style = "css/login_register.css";
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if current password, new password, and confirm password are provided
    if (
        isset($_POST["current_password"], $_POST["new_password"], $_POST["confirm_password"]) &&
        !empty($_POST["current_password"]) && !empty($_POST["new_password"]) && !empty($_POST["confirm_password"])
    ) {

        // Sanitize input
        $currentPassword = htmlspecialchars($_POST["current_password"]);
        $newPassword = htmlspecialchars($_POST["new_password"]);
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
            // Check if new password is different from current password
            if ($newPassword !== $currentPassword) {
                // Verify if new password matches confirm password
                if ($newPassword === $confirmPassword) {
                    // Hash the new password
                    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                    // Update the password in the database
                    $updateStmt = $connect->prepare("UPDATE users SET password = ? WHERE username = ?");
                    $updateStmt->bind_param("ss", $hashedNewPassword, $_SESSION["username"]);
                    if ($updateStmt->execute()) {
                        $success = "Password updated successfully.";
                    } else {
                        $error = "Error updating password: " . $connect->error;
                    }
                    $updateStmt->close();
                } else {
                    $error = "New password and confirm password do not match.";
                }
            } else {
                $error = "New password cannot be the same as the current password.";
            }
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
    <title>Change Password</title>
    <link rel="stylesheet" href="<?php echo $style; ?>">
</head>

<body>
    <main>
        <?php if (isset($error)) : ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (isset($success)) : ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <form method="post">
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required>
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>
            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <button type="submit">Change Password</button>
        </form>
        <form action="user_settings.php">
            <button type="submit">Back to Settings</button>
        </form>
    </main>
</body>

</html>
