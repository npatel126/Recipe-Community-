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
    <title>My Kitchens</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <h1>Welcome to Your Kitchens, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>!</h1>
    </header>
    <main>
        <h1>Kitchens</h1>

        <section>
            <?php
            // Database connection details
            $server = "db";
            $user = "admin";
            $pw = "pwd";
            $db = "rc";

            $connect = mysqli_connect($server, $user, $pw, $db) or die('Could not connect to the database server' . mysqli_connect_error());

            // Retrieve the user ID from the session
            $user_id = $_SESSION['user_id'];
            $kitchen_id = null;
            $kitchen_name = '';
            $query = "SELECT kitchen_id, name FROM kitchens WHERE owner_id = $user_id ; ";

            $stmt = mysqli_prepare($connect, $query);
            if ($stmt = $connect->prepare($query)) {
                $stmt->execute();
                $stmt->bind_result($kitchen_id, $kitchen_name);
            }

            $uname = $_SESSION["username"];

            while ($stmt->fetch()) {
                print("<p>$kitchen_name </p><a href=\"view_kitchen.php?link=$kitchen_id\">View this Kitchen!</a>");
            }

            $stmt->close();
            mysqli_close($connect);
            ?>
        </section>

        <section>
            <h1>Kitchen Actions</h1>
            <button onclick="window.location.href = 'add_kitchen.php'; ">Add a kitchen</button>
        </section>

    </main>
    <button onclick="window.location.href = 'dashboard.php';">Return to dashboard</button>
</body>

</html>
