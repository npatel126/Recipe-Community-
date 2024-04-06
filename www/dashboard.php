<?php
    session_start();
    if (isset($_SESSION["username"]) && $_SESSION["loggedin"] == TRUE) {
        echo "Welcome, " . $_SESSION["username"];
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
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to the external CSS file -->
</head>
<body>
    <header>
        <h1>Welcome to Your Dashboard, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>!</h1>
        <?php if (!isset($_SESSION["username"])): ?>
            <button onclick="window.location.href = 'login.html';">Login</button>
        <?php else: ?>
            <button onclick="window.location.href = 'logout.php';">Logout</button>
        <?php endif; ?>
    </header>
    <main>
        <section>
            <h2>Profile Information</h2>
            <?php if (isset($_SESSION["username"])): ?>
                <p>Username: <?php echo $_SESSION['username']; ?></p>
                <button onclick="window.location.href = 'change_username.php';">Change Username</button>
                <button onclick="window.location.href = 'change_password.php';">Change Password</button>
            <?php endif; ?>
        </section>
        <section>
            <h2>Actions</h2>
            <ul>
                <?php if (isset($_SESSION["username"])): ?>
                    <li><button onclick="window.location.href = 'add_recipe.html';">Add Recipe</button></li>
                    <li><button onclick="window.location.href = 'search_recipe.html';">Search Recipes</button></li>
                    <li><button onclick="window.location.href = 'user_recipes.php';">View Your Recipes</button></li>
                <?php endif; ?>
            </ul>
        </section>
    </main>
</body>
</html>
