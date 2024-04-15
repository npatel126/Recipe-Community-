<?php
session_start();
if (isset($_SESSION["username"]) && $_SESSION["loggedin"] == TRUE) {
    //echo "Welcome, " . $_SESSION["username"];
} else {
    header("Location: index.php");
    exit;
}

// Toggle style session variable
if ($_SESSION['darkmode']) {
    $style = "css/dashboard(dark).css";
} else {
    $style = "css/dashboard.css";
}

// Database connection details
$server = "db";
$user = "admin";
$pw = "pwd";
$db = "rc";
$connect = mysqli_connect($server, $user, $pw, $db) or die('Could not connect to the database server' . mysqli_connect_error());
// Retrieve the user's name from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT name FROM users WHERE user_id = $user_id";
$result = mysqli_query($connect, $query);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $user_name = $row['name'];
} else {
    $user_name = "Guest";
}



// get recipes that are not created by the user, in the user's cookbook, or in the user's favorites
$recipe_id = $recipe_name = $recipe_desc = null;
$query = "SELECT recipe_id, name FROM recipes WHERE recipe_id NOT IN ( SELECT recipe_id FROM recipes WHERE creator_id = 2 UNION SELECT recipe_id FROM favorites_recipes WHERE favorite_id IN ( SELECT favorite_id FROM favorites WHERE owner_id = 2 ) UNION SELECT recipe_id FROM cookbooks_recipes WHERE cookbook_id IN ( SELECT cookbook_id FROM cookbooks WHERE owner_id = 2 ) )";

$stmt = mysqli_prepare($connect, $query);
if ($stmt = $connect->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($recipe_id, $recipe_name);
}

$discovery_arr = array();
while ($stmt->fetch()) {
    $discovery_arr[$recipe_id] = $recipe_name;
}

$stmt->close();

$qMark_arr = join(',', array_fill(0, count($discovery_arr), '?')); // turning the array of cookbooks into '?' for stmt
$query = "SELECT recipe_id, description FROM recipes WHERE recipe_id IN ($qMark_arr)";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, str_repeat('i', count($discovery_arr)), ...array_keys($discovery_arr));
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $recipe_id, $recipe_desc);

$desc_arr = array();
while ($stmt->fetch()) {
    $desc_arr[$recipe_id] = $recipe_desc;
}

mysqli_stmt_close($stmt);

mysqli_close($connect);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="<?php echo $style; ?>">
</head>

<body>
    <header>
        <h1>Welcome to Your Dashboard, <?php echo $user_name?>!</h1>
        <?php if (!isset($_SESSION["username"])) : ?>
            <button onclick="window.location.href = 'login.php';">Login</button>
        <?php else : ?>
            <p>Username: <?php echo $_SESSION['username']; ?></p>
            <button onclick="window.location.href = 'logout.php';">Logout</button>
        <?php endif; ?>
    </header>
    <main>
        <section>
            <h2>Recipes for you to discover!</h2>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>View!</th>
                </tr>
                <?php
                // randomly select a recipe(s) from the array for display & only display each recipe once
                $count = 0;
                $already_discovered = array();
                do {
                    $discovery = array_rand($discovery_arr);
                    if (!in_array($discovery, $already_discovered)) {
                        array_push($already_discovered, $discovery);
                        print("<tr><td>$discovery_arr[$discovery]</td><td>$desc_arr[$discovery]</td><td><a href=\"view_recipe.php?link=$discovery\">View this recipe!</a></td></tr>");
                        $count++;
                    }
                } while ($count < 5);
                ?>
            </table>
        </section>
        <section>
            <h2>Actions</h2>
            <ul>
                <?php if (isset($_SESSION["username"])) : ?>
                    <li><button onclick="window.location.href = 'user_kitchens.php';">My Kitchens</button></li>
                    <li><button onclick="window.location.href = 'user_cookbooks.php';">My Cookbooks</button></li>
                    <li><button onclick="window.location.href = 'recipe_search.php';">Search Recipes</button></li>
                    <li><button onclick="window.location.href = 'add_recipe.php';">Add Recipe</button></li>
                    <li><button onclick="window.location.href = 'user_recipes.php';">View Your Recipes</button></li>
                    <li><button onclick="window.location.href = 'view_favorites.php';">View Your Favorite Recipes</button></li>
                    <li><button onclick="window.location.href = 'user_settings.php';">My Settings</button></li>
                <?php endif; ?>
            </ul>
        </section>
    </main>
</body>

</html>
