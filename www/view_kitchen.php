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
    $user_id = $_SESSION['user_id'];
    $kitchen_id = $_GET['link'];
    $kitchen_name = '';
    $cookbook_id = null;
    $cookbook_name = '';
    $cookbook_names = array();
    $query = "SELECT cookbooks.cookbook_id, cookbooks.name, kitchens.name FROM cookbooks JOIN kitchens ON kitchens.kitchen_id  = $kitchen_id WHERE cookbooks.kitchen_id = $kitchen_id ; ";

    $stmt = mysqli_prepare($connect, $query);
    if ($stmt = $connect->prepare($query)) {
        $stmt->execute();
        $stmt->bind_result($cookbook_id, $cookbook_name, $kitchen_name);
    }

    $uname = $_SESSION["username"];
    while ($stmt->fetch()) {
        array_push($cookbook_names, $cookbook_name);
    }

    $stmt->close();
    mysqli_close($connect);

    print("<h1>Cookbooks in $uname's $kitchen_name kitchen</h1>");
    for ($i = 0; $i < sizeof($cookbook_names); $i++) {
        print("<p>$cookbook_names[$i]</p>");
    }

    ?>

    </main>
    <button onclick="window.location.href = 'dashboard.php';">Return to dashboard</button>
</body>

</html>
