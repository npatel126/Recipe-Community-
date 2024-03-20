<?php
// Check if the script is accessed through a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputs = array_map('htmlspecialchars', $_POST);
    $errors = [];
    // Validate required fields
    foreach (['name', 'username', 'password', 'confirm_password'] as $field) {
        if (empty($inputs[$field])) {
            $errors[] = ucfirst($field) . ' is required';
        }
    }
    // Check if passwords match
    if (!empty($inputs['password']) && $inputs['confirm_password'] !== $inputs['password']) {
        $errors[] = 'Passwords do not match';
        displayError('Passwords do not match. Please try again.', true);
    }
    // Display error messages if any
    if ($errors) {
        echo '<div class="error-message">' . implode('<br>', $errors) . '</div>';
    } else {
        // Database connection details
        $server = "db";
        $user = "admin";
        $pw = "pwd";
        $db = "rc";
        // Establish a database connection or exit with an error message
        $connect = mysqli_connect($server, $user, $pw, $db) or die('Could not connect to the database server' . mysqli_connect_error());
         // Check if the username already exists
        $checkQuery = "SELECT username FROM users WHERE username = ?";
        $checkStmt = mysqli_prepare($connect, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, "s", $inputs['username']);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            displayError('Username already exists. Please choose a different username.');
        }
        // Close the statement for username check
        mysqli_stmt_close($checkStmt);
        // Hash the password
        $hashedPassword = password_hash($inputs['password'], PASSWORD_DEFAULT);
        // Insert user into the users table
        $query = "INSERT INTO users (name, username, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "sss", $inputs['name'], $inputs['username'], $hashedPassword);
        mysqli_stmt_execute($stmt);
        // Close the statement for user insertion
        mysqli_stmt_close($stmt);
        // Close the database connection
        mysqli_close($connect);
        // Display success message with a link to the login page
        echo '<p>Registration successful! Click <a href="login.html">here</a> to login.</p>';
    }
    exit();
}
//Display error messages along with optional "Go Back" button.
function displayError($errorMessage, $showBackButton = false) {
    echo '<div class="error-message">' . $errorMessage . '</div>';
    
    if ($showBackButton) {
        echo '<div class="button-container">';
        echo '<button class="back-button" onclick="window.history.back()">Go Back</button>';
        echo '</div>';
    } else {
        echo '<div class="button-container">';
        echo '<button class="login-button" onclick="window.location.href=\'login.html\'">Go to Login</button>';
        echo '<button class="try-again-button" onclick="window.location.href=\'register.html\'">Try Again</button>';
        echo '</div>';
    }

    exit();
}
?>
