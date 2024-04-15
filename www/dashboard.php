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
