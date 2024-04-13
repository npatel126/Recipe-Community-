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
    // Check if current password, new name, and confirm name are provided
    if (
        isset($_POST["current_password"], $_POST["new_name"], $_POST["confirm_name"]) &&
        !empty($_POST["current_password"]) && !empty($_POST["new_name"]) && !empty($_POST["confirm_name"])
    ) {

        // Sanitize input
        $currentPassword = htmlspecialchars($_POST["current_password"]);
        $newName = htmlspecialchars($_POST["new_name"]);
        $confirmName = htmlspecialchars($_POST["confirm_name"]);

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
            // Verify new name matches itself
            if ($newName == $confirmName) {
                // Update the name in the database
                $updateStmt = $connect->prepare("UPDATE users SET name = ? WHERE username = ?");
                $updateStmt->bind_param("ss", $newName, $_SESSION["username"]);
                if ($updateStmt->execute()) {
                    $_SESSION["name"] = $newName; // Update session with new name
                    $success = "Name changed successfully.";
                } else {
                    $error = "Error updating name: " . $connect->error;
                }
                $updateStmt->close();
            } else {
                $error = "New names do not match.";
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
    <title>Change Name</title>
    <link rel="stylesheet" href="<?php echo $style; ?>">
</head>

<body>
    <main>
        <?php if (isset($error)) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (isset($success)) : ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required>
            <label for="new_name">New Name:</label>
            <input type="text" id="new_name" name="new_name" required>
            <label for="confirm_name">Confirm New Name:</label>
            <input type="text" id="confirm_name" name="confirm_name" required>
            <button type="submit">Change Name</button>
        </form>
        <form action="user_settings.php">
            <button type="submit">Back to Settings</button>
        </form>
    </main>
</body>

</html>
