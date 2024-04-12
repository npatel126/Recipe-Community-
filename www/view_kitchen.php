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
    <title>Kitchens view</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
    // Database connection details
    $server = "db";
    $user = "admin";
    $pw = "pwd";
    $db = "rc";

    $connect = mysqli_connect($server, $user, $pw, $db) or die('Could not connect to the database server' . mysqli_connect_error());

    // Retrieve the user ID from the session
    $kitchen_id = $_GET['link'];
    $kitchen_name = '';
    $cookbook_id = null;
    $cookbook_name = '';
    $query = "SELECT cookbooks.cookbook_id, cookbooks.name, kitchens.name FROM cookbooks JOIN kitchens ON kitchens.kitchen_id  = $kitchen_id WHERE cookbooks.kitchen_id = $kitchen_id ; ";

    $stmt = mysqli_prepare($connect, $query);
    if ($stmt = $connect->prepare($query)) {
        $stmt->execute();
        $stmt->bind_result($cookbook_id, $cookbook_name, $kitchen_name);
    }

    $uname = $_SESSION["username"];
    $cookbook_ids = array();
    while ($stmt->fetch()) {
        $cookbook_ids[$cookbook_id] = $cookbook_name;
    }

    $stmt->close();
    mysqli_close($connect);

    print("<h1>Cookbooks in $uname's $kitchen_name kitchen</h1>");
    ?>

    <main>

        <?php
        foreach ($cookbook_ids as $cookbook_id => $cookbook_name) {
            print("<p>$cookbook_name <a href=\"view_cookbook.php?link=$cookbook_id\">View this Cookbook!</a></p>");
        }
        ?>


    </main>
    <button onclick="window.location.href = 'user_kitchens.php';">Return to Kitchens</button>
    <button onclick="window.location.href = 'dashboard.php';">Return to Dashboard</button>
</body>

</html>
