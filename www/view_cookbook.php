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
    <title>Cookbook view</title>
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
    $cookbook_id = $_GET['link'];
    $cookbook_name = '';
    $recipe_name = '';
    $query = "SELECT recipe_id, recipes.title, cookbooks.name FROM recipes JOIN cookbooks ON cookbooks.cookbook_id = $cookbook_id WHERE recipes.cookbook_id = $cookbook_id; ";

    $stmt = mysqli_prepare($connect, $query);
    if ($stmt = $connect->prepare($query)) {
        $stmt->execute();
        $stmt->bind_result($recipe_id, $recipe_name, $cookbook_name);
    }

    $uname = $_SESSION["username"];
    $recipe_ids = array();
    while ($stmt->fetch()) {
        $recipe_ids[$recipe_id] = $recipe_name;
        //$cookbook_name = $cookbook_name;
    }

    $stmt->close();
    mysqli_close($connect);
    print("<h1>Recipes in $uname's $cookbook_name cookbook</h1>");
    ?>

    <main>
        <h1>Recipes</h1>

        <?php
        foreach ($recipe_ids as $recipe_id => $recipe_name) {
            print("<p>$recipe_name <a href=\"view_recipe.php?link=$recipe_id\">View this Recipe!</a></p>");
        }
        ?>

    </main>
    <button onclick="window.location.href = 'dashboard.php';">Return to dashboard</button>
</body>

</html>
