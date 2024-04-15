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
    <title>My Cookbooks</title>
    <link rel="stylesheet" href="<?php echo $style; ?>">
</head>

<body>
    <header>
        <h1>Welcome to Your Cookbooks, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>!</h1>
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
    $cookbook_id = null;
    $cookbook_name = '';
    $query = "SELECT cookbooks.cookbook_id, cookbooks.name FROM cookbooks WHERE cookbooks.owner_id=$user_id ; ";

    $stmt = mysqli_prepare($connect, $query);
    if ($stmt = $connect->prepare($query)) {
        $stmt->execute();
        $stmt->bind_result($cookbook_id, $cookbook_name);
    }

    $cookbook_ids = array();
    while ($stmt->fetch()) {
        $cookbook_ids[$cookbook_id] = $cookbook_name;
    }

    $stmt->close();
    mysqli_close($connect);
    ?>

    <main>
        <section>
            <h1>Cookbooks</h1>

            <?php
            if (($cookbook_ids !== null) && (sizeof($cookbook_ids) > 0)) {
                // TODO: maybe display what kitchen the cookbook belongs to
                natcasesort($cookbook_ids);
                print("<table border=1>");
                print("<tr> <th>Name</th> <th>View</th> <th>Edit</th> </tr>");
                foreach ($cookbook_ids as $cookbook_id => $cookbook_name) {
                    print("<tr><td>$cookbook_name</td><td><a href=\"view_cookbook.php?link=$cookbook_id\">View this Cookbook!</a></td><td><a href=\"edit_cookbook.php?link=$cookbook_id\">Edit this Cookbook!</a></td><tr>");
                }
            } else {
                print("Create a cookbook!");
            }
            ?>
            </table>
        </section>

        <section>
            <h1>Cookbook actions</h1>
            <form>
            <input type="submit" formaction="./add_cookbook.php" value="Add a Cookbook">
            </form>
        </section>
    </main>
    <form>
        <input type="submit" formaction="./dashboard.php" value="Return to Dashboard">
    </form>
</body>

</html>
