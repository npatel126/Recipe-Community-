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
        <table>
            <thead>
                <tr>
                    <th>Recipe Name</th>
                    <th>View Recipe</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recipe_ids as $recipe_id => $recipe_name) : ?>
                    <tr>
                        <td><?php echo $recipe_name; ?></td>
                        <td><a href="view_recipe.php?link=<?php echo $recipe_id; ?>">View this Recipe!</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <form>
    <input type="submit" formaction="./user_cookbooks.php" value="Return to Cookbooks">
    <input type="submit" formaction="./user_kitchens.php" value="Return to Kitchens">
    </form>
</body>

</html>
