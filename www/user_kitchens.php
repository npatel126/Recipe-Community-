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
    $style = "css/view_list(dark).css";
} else {
    $style = "css/view_list.css";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Kitchens</title>
    <link rel="stylesheet" href="<?php echo $style; ?>">
</head>

<body>
    <header>
        <h1>Welcome to Your Kitchens, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>!</h1>
    </header>
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

    $kitchen_ids = array();
    while ($stmt->fetch()) {
        $kitchen_ids[$kitchen_id] = $kitchen_name;
    }

    $stmt->close();
    mysqli_close($connect);

    ?>
    <main>
        <section>
            <h1>Kitchens</h1>

            <?php
            if (($kitchen_ids !== null) && (sizeof($kitchen_ids) > 0)) {
                natcasesort($kitchen_ids);
                print("<table border=1>");
                print("<tr> <th>Name</th> <th>View</th> <th>Edit</th> </tr>");
                foreach ($kitchen_ids as $kitchen_id => $kitchen_name) {
                    print("<tr><td>$kitchen_name</td><td><a href=\"view_kitchen.php?link=$kitchen_id\">View this Kitchen!</a></td><td><a href=\"edit_kitchen.php?link=$kitchen_id\">Edit this Kitchen!</a></td></td>");
                }
            } else {
                print("Create a kitchen!");
            }

            ?>
            </table>
        </section>

        <section>
            <h1>Kitchen Actions</h1>
            <button onclick="window.location.href = 'add_kitchen.php'; ">Add a kitchen</button>
        </section>
    </main>
    <form>
    <input type="submit" formaction="./dashboard.php" value="Return to Dashboard">
    </form>
</body>

</html>
