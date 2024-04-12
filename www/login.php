<?php
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
        print("<h2>Please wait while the server initalizes</h2>");
        print("<button onclick=\"window.location.href = 'login.html';\">Return to login page</button>");
        exit;
    }

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
         // Start a session
         session_start();
         // Set session variables
         $_SESSION['loggedin'] = true;
         $_SESSION['username'] = $username;
         $_SESSION['user_id'] = $user_id;

        header("Location: dashboard.php");
    } else {
        // Failed login, display an error message
        echo "Invalid username or password. Please try again.";
        echo '<br><a href="login.html">Return to Login</a>';
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($connect);
}
?>
