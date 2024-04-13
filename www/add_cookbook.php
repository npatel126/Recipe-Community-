<?php
session_start();
if (isset($_SESSION["username"]) && $_SESSION["loggedin"] == TRUE) {
    // echo "Welcome, " . $_SESSION["username"];
} else {
    header("Location: index.php");
    exit;
}

// Toggle style session variable
if ($_SESSION['darkmode']) {
    $style = "css/login_register(dark).css";
} else {
    $style = "css/login_register.css";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add cookbook</title>
    <link rel="stylesheet" href="<?php echo $style; ?>">
</head>

<body>
    <main>
        <h1>Add cookbook</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="name">Cookbook Name:</label>
            <input type="text" id="name" name="name" required>

            <button type="submit">Submit</button>
            <button type="submit" formaction="./user_cookbooks.php" formnovalidate>Return to cookbooks</button>

        </form>
    </main>

    <?php

    // Database connection details
    $server = "db";
    $user = "admin";
    $pw = "pwd";
    $db = "rc";

    // Check if the script is accessed through a POST request
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Establish a database connection or exit with an error message
        $connect = mysqli_connect($server, $user, $pw, $db) or die('Could not connect to the database server' . mysqli_connect_error());

        // Sanitize and validate form inputs
        $name = htmlspecialchars($_POST["name"]);
        $owner_id = $_SESSION["user_id"];

        // Insert recipe into the database
        $query = "INSERT INTO cookbooks (name, owner_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "si", $name, $owner_id);
        mysqli_stmt_execute($stmt);
        // Close the statement for recipe insertion
        mysqli_stmt_close($stmt);

        // Close the database connection
        mysqli_close($connect);

        // Display success message
        echo '<p>cookbook added successfully!</p>';
    }

    ?>

</body>

</html>
