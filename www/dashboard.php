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
        <h1>Welcome to Your Dashboard, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>!</h1>
        <?php if (!isset($_SESSION["username"])) : ?>
            <button onclick="window.location.href = 'login.html';">Login</button>
        <?php else : ?>
            <button onclick="window.location.href = 'logout.php';">Logout</button>
        <?php endif; ?>
    </header>
    <main>
        <section>
            <h2>Profile Information</h2>
            <?php if (isset($_SESSION["username"])) : ?>
                <p>Username: <?php echo $_SESSION['username']; ?></p>
                <button onclick="window.location.href = 'user_kitchens.php';">My Kitchens</button>
                <button onclick="window.location.href = 'user_cookbooks.php';">My Cookbooks</button>
                <button onclick="window.location.href = 'user_settings.php';">My Settings</button>
            <?php endif; ?>
        </section>
        <section>
            <h2>Actions</h2>
            <ul>
                <?php if (isset($_SESSION["username"])) : ?>
                    <li><button onclick="window.location.href = 'recipe_search.php';">Search Recipes</button></li>
                    <li><button onclick="window.location.href = 'add_recipe.php';">Add Recipe</button></li>
                    <li><button onclick="window.location.href = 'user_recipes.php';">View Your Recipes</button></li>
                    <li><button onclick="window.location.href = 'view_favorites.php';">View Your Favorite Recipes</button></li>
                <?php endif; ?>
            </ul>
        </section>
    </main>
</body>

</html>
