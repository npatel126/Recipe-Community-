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
    $style = "css/search(dark).css";
    } else {
    $style = "css/search.css";
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recipe Search</title>
    <link rel="stylesheet" href="<?php echo $style; ?>">
</head>

<body>
    <h1>Search for a recipe</h1>

    <form action="./search_recipe.php" method="post">
        <label for="search">Search for:</label>
        <input type="text" id="search" name="search" required>

        <label for="type">Search type:</label>
        <select name="type">
            <option value="title">Title</option>
            <option value="category">Category</option>
            <option value="cuisine">Cuisine</option>
            <option value="ingredients">Ingredient</option>
            <option value="prep_time">Prep Time</option>
            <option value="cook_time">Cook Time</option>
            <option value="total_time">Total Time</option>
            <option value="servings">Servings</option>
        </select>

        <!-- TODO: this will probably need to change with user sessions -->
        <!-- TODO: later maybe change view all to view favorites or something similar (wouldn't want to view all of a super large db)-->
        <p>
            <input type="submit" value="Search!">
            <input type="submit" formaction="./view_all.php" value="View all Recipes!" formnovalidate>
            <input type="submit" formaction="./dashboard.php" value="Cancel" formnovalidate>
        </p>
    </form>

</body>

</html>
