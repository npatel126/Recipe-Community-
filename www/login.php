<?php
// Start session at the very beginning
session_start();

// Initialize error message variable
$error_message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the form data 
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);

    // Database connection details
    $server = "db";
    $user = "admin";
    $pw = "pwd";
    $db = "rc";

    // This is to gracefully allow the database to finish its initialization
    // it is rather crude and can almost certainly be done better but it will work for now
    try {
        // Open connection with db
        $connect = mysqli_connect($server, $user, $pw, $db) or die('Could not connect to the database server' . mysqli_connect_error());
    } catch (\Throwable $th) {
        $error_message = "Please wait while the server initializes";
    }

    if (empty($error_message)) {
        // Prepare and execute the query
        $query = "SELECT user_id, username, password FROM users WHERE username = ?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $user_id, $dbUsername, $dbPassword);
        mysqli_stmt_fetch($stmt);
        // Verify password
        if ($dbUsername && password_verify($password, $dbPassword)) {
            // Successful login
            // Set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['darkmode'] = false;
            header("Location: dashboard.php");
            exit; // Make sure to exit after header redirection
        } else {
            // Failed login, set error message
            $error_message = "Invalid username or password. Please try again.";
        }

        // Close statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($connect);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login_register.css"> 
    <title>Login</title>
    <style>
        .error-container {
            text-align: center;
            margin-top: 20px;
        }

        .error {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ffcccc;
            color: #ff0000;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <main>
        <?php if (!empty($error_message)) : ?>
            <div class="error-container">
                <div class="error"><?php echo $error_message; ?></div>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h1>Login</h1>
            <div>
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <section>
                <button type="submit">Login</button>
                <a href="register.php">Register</a> 
            </section>
            <br>
            <button type="submit" formaction="./index.php" formnovalidate>Return</button>
        </form>
    </main>
</body>
</html>
