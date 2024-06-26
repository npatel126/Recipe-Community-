<?php
    session_start();
?>
<!DOCTYPE html>

<html lang="en">

<head>
    <title>Recipe Community</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <div class="home">

        <h1>Welcome to Recipe Community!</h1>

        <!--
        TODO:
        We need a welcome blurb about the app. 
        I'm just putting this as a place holder for now, its basically the product vision.
        NOTE:
        "Share culinary creations through pictures and videos."
        This is sounding an awful lot like social media, do we want that?
    -->
        <h2>
            About Recipe Community
        </h2>
        <p>
            Recipe Community aims to be an app for cooking enthusiasts and food lovers who are looking for a
            user-friendly
            way to discover, save, and share recipes with other like-minded chefs. Recipe Community is a collaborative
            culinary platform that simplifies the process of finding and curating delicious dishes.
        </p>

        <p>
            Unlike standalone recipe finders, Recipe Community's focus is on building a vibrant community where users
            can
            add their favorite recipes and share culinary creations through pictures and videos. Recipe Community
            aspires to
            foster a sense of belonging by allowing users to connect with other chefs, be they professionals, home
            cooks, or
            simply good food enthusiasts, to exchange recipes, cooking tips, and inspire each other in their culinary
            adventures.
        </p>

        <p>
            Recipe Community hopes to become <i>your</i> go-to place for both finding recipes and sharing your passions
            about food with others!
        </p>
        
        <?php if (isset($_SESSION["username"]) && $_SESSION["loggedin"] == TRUE): ?>
        <!-- Button for Dashboard -->
        <h2>Dashboard</h2>
        <form action="dashboard.php">
            <input type="submit" value="Go to Dashboard">
        </form>
        <h2>
            Search recipe
        </h2>
        <form action="./recipe_search.php">
            <input type="submit" value="Click here to search recipe!">
        </form>
        <?php else: ?>

        <h2>
            Don't have an account and would like to join the Recipe Community?
        </h2>
        <form action="./register.php">
            <input type="submit" value="Click here to create an account!">
        </form>

        <h2>
            Already have an account?
        </h2>
        <form action="./login.php">
            <input type="submit" value="Click here to login to your account!">
        </form>
        <?php endif; ?>

    </div>
</body>

</html>
