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
    <title>Kitchens view</title>
    <link rel="stylesheet" href="<?php echo $style; ?>">
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
    $owner_id = $_SESSION['user_id'];
    $kitchen_id = $_GET['link'];
    $kitchen_name = '';
    $cookbook_id = null;
    $cookbook_name = '';
    //$query = "SELECT cookbooks.cookbook_id, cookbooks.name, kitchens.name FROM kitchens LEFT JOIN cookbooks ON kitchens.kitchen_id  = cookbooks.kitchen_id WHERE kitchens.owner_id = $owner_id AND (cookbooks.kitchen_id = $kitchen_id OR cookbooks.kitchen_id IS NULL); ";
    $query = "Select kitchens.name, cookbooks.cookbook_id, cookbooks.name FROM kitchens LEFT JOIN kitchens_cookbooks ON kitchens_cookbooks.kitchen_id = kitchens.kitchen_id LEFT JOIN cookbooks ON kitchens_cookbooks.cookbook_id = cookbooks.cookbook_id WHERE kitchens.owner_id = $owner_id AND kitchens.kitchen_id = $kitchen_id";

    $stmt = mysqli_prepare($connect, $query);
    if ($stmt = $connect->prepare($query)) {
        $stmt->execute();
        $stmt->bind_result($kitchen_name, $cookbook_id, $cookbook_name);
    }

    $uname = $_SESSION["username"];
    $cookbook_ids = array();
    while ($stmt->fetch()) {
        $cookbook_ids[$cookbook_id] = $cookbook_name;
    }

    // kitchens with no cookbooks will still return a null one
    if (current($cookbook_ids) === null) {
        $cookbook_ids = null;
    }

    $stmt->close();
    mysqli_close($connect);

    print("<h1>Cookbooks in $uname's $kitchen_name kitchen</h1>");
    ?>

    <main>
        <section>
            <h1>Cookbooks</h1>
            <?php
            //var_dump($cookbook_ids);
            // only display table if cookbooks exist
            if (($cookbook_ids !== null) && (sizeof($cookbook_ids) > 0)) {
                // if a kitchen exists that has no cookbooks we'll have a null entry on the end, remove it
                /*
                if ((end($cookbook_ids) === null) && (count($cookbook_ids) > 1)) {
                    $trash = array_pop($cookbook_ids); // this will be null 
                }
                */
                natcasesort($cookbook_ids);
                print("<table border=1>");
                print("<tr> <th>Name</th> <th>View</th> <th>Edit</th> </tr>");
                foreach ($cookbook_ids as $cookbook_id => $cookbook_name) {
                    print("<tr><td>$cookbook_name</td><td><a href=\"view_cookbook.php?link=$cookbook_id\">View this Cookbook!</a></td><td><a href=\"edit_cookbook.php?link=$cookbook_id\">Edit this Cookbook!</a></td></tr>");
                }
            } else {
                print("Edit this kitchen to add cookbooks!");
            }

            ?>
            </table>
        </section>
        <section>
            <h1>Kitchen actions</h1>
            <?php print("<button onclick=\"window.location.href = 'edit_kitchen.php?link=$kitchen_id' \">Edit this kitchen</button>"); ?>
        </section>
    </main>
    <form>
        <input type="submit" formaction="./user_kitchens.php" value="Return to Kitchens">
    </form>
</body>

</html>
