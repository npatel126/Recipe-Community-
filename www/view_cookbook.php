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
    <title>Cookbook view</title>
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
    $cookbook_id = $_GET['link'];
    $cookbook_name = '';
    $recipe_name = '';
    //$query = "SELECT recipe_id, recipes.title, cookbooks.name FROM cookbooks LEFT JOIN recipes ON cookbooks.cookbook_id = recipes.cookbook_id WHERE cookbooks.owner_id = $owner_id AND cookbooks.cookbook_id  = $cookbook_id; ";
    $query = "SELECT cookbooks.name, recipes.recipe_id, recipes.name FROM cookbooks LEFT JOIN cookbooks_recipes ON cookbooks_recipes.cookbook_id = cookbooks.cookbook_id LEFT JOIN recipes ON cookbooks_recipes.recipe_id = recipes.recipe_id WHERE cookbooks.owner_id = $owner_id AND cookbooks.cookbook_id = $cookbook_id;";

    $stmt = mysqli_prepare($connect, $query);
    if ($stmt = $connect->prepare($query)) {
        $stmt->execute();
        $stmt->bind_result($cookbook_name, $recipe_id, $recipe_name);
    }

    $uname = $_SESSION["username"];
    $recipe_ids = array();
    while ($stmt->fetch()) {
        $recipe_ids[$recipe_id] = $recipe_name;
        //$cookbook_name = $cookbook_name;
    }

    // Cookbooks with no recipes will still return a null one
    if (current($recipe_ids) === null) {
        $recipe_ids = null;
    }
   

    $stmt->close();
    mysqli_close($connect);

    print("<h1>Recipes in $uname's $cookbook_name cookbook</h1>");
    ?>

    <main>
        <section>
            <h1>Recipes</h1>
            <?php
            if ($recipe_ids !== null && (sizeof($recipe_ids) > 0)) {
                natcasesort($recipe_ids);
                print("<table border=1>");
                print("<tr> <th>Name</th> <th>View</th> <th>Edit</th> </tr>");
                foreach ($recipe_ids as $recipe_id => $recipe_name) {
                    print("<tr><td>$recipe_name</td><td><a href=\"view_recipe.php?link=$recipe_id\">View this Recipe!</a></td><td><a href=\"edit_recipe.php?link=$recipe_id\">Edit this Recipe!</a></td></tr>");
                }
            } else {
                print("Edit this cookbook to add recipes!");
            }
            ?>
            </table>
        </section>
        <section>
            <h1>Cookbook actions</h1>
            <?php print("<button onclick=\"window.location.href = 'edit_cookbook.php?link=$cookbook_id';\">Edit this cookbook</button>"); ?>
        </section>

    </main>
    <button onclick="window.location.href = 'user_cookbooks.php';">Return to Cookbooks</button>
    <button onclick="window.location.href = 'dashboard.php';">Return to dashboard</button>
</body>

</html>
