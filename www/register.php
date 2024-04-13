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
    }
    // Display error messages if any
    if ($errors) {
        echo '<div class="error-container"><div class="error">' . implode('<br>', $errors) . '</div></div>';
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
            $errors[] = 'Username already exists. Please choose a different username.';
            // Display the error message
            echo '<div class="error-container"><div class="error">' . implode('<br>', $errors) . '</div></div>';
        } else {
            // Close the statement for username check
            mysqli_stmt_close($checkStmt);

            // If there are no errors, proceed with inserting the user into the database
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
            echo '<div class="success-container"><div class="success">';
            echo '<p>Registration successful!</p>';
            echo '<p>Click <a href="login.php">here</a> to login.</p>';
            echo '</div></div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="css/login_register.css">
    <style>
        .error-container,
        .success-container {
            text-align: center;
            margin-top: 20px;
        }

        .error,
        .success {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .error {
            background-color: #ffcccc;
            color: #ff0000;
        }

        .success {
            background-color: #ccffcc;
            color: #008000;
        }
    </style>
</head>

<body>
    <main>
        <h1>Register</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div>
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required pattern="[a-zA-Z0-9_\-]+">
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <section>
                <button type="submit">Register</button>
                <button type="submit" formaction="./index.php" formnovalidate>Return</button>
            </section>
        </form>
        <p>If you already have an account, <a href="login.php">click here to log in</a>.</p>
    </main>
</body>

</html>
