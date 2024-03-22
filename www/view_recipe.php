<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recipe Search Results</title>
    <!--
    <link href="style.css" rel="stylesheet">
    -->
</head>

<body>

    <?php
    // Database connection details
    $server = "db";
    $user = "admin";
    $pw = "pwd";
    $db = "rc";

    $connect = mysqli_connect($server, $user, $pw, $db) or die('Could not connect to the database server' . mysqli_connect_error());

    $search = $_GET['link'];

    // init vars for query return
    $recipe_id = "";
    $title = "";
    $description = "";
    $category = "";
    $ingredients = "";
    $instructions = "";
    $prep_time = "";
    $cook_time = "";
    $total_time = "";
    $servings = "";
    // TODO: figure out how to display author by name
    $creator_id = "";

    // Search DB for recipe(s) matching description
    $query = "SELECT * FROM recipes WHERE title = '$search'; ";
    $stmt = mysqli_prepare($connect, $query);

    if ($stmt = $connect->prepare($query)) {
        $stmt->execute();
        $stmt->bind_result($recipe_id, $title, $description, $category, $ingredients, $instructions, $prep_time, $cook_time, $total_time, $servings, $creator_id);
    }

    // Is this really how this has to be done?
    // TODO: figure out how to "pretty print" things like the ingredients and instructions
    // TODO; either do everything in mins or add the ability to convert to hours
    while ($stmt->fetch()) {
        $list = explode(',', $ingredients);
        print("<h1>$title</h1>");
        print("<h2>Description: $description</h2>");
        print("<h3>Category: $category</h3>");
        print("<h3>Prep time: $prep_time min</h3>");
        print("<h3>Cook time: $cook_time min</h3>");
        print("<h3>Total time: $total_time min</h3>");
        print("<h3>Servings: $servings</h3>");
        print("<h2>Ingredients:</h2>");
        for ($i = 0; $i < sizeof($list); $i++) {
            print("<p><input type=\"checkbox\"/> $list[$i]</p>");
        }
        //print("<p>$ingredients</p>");
        print("<h2>Instructions:</h2>");
        print("<p>$instructions</p>");
    }

    // TODO This can probably be moved up??? testing
    // close statement
    $stmt->close();
    // close connection with db
    mysqli_close($connect);

    ?>

    <!-- TODO: this will probably need to change with user sessions -->
    <form>
        <p>
            <input type="submit" formaction="./search_recipe.html" value="Search Again!">
            <input type="submit" formaction="./index.html" value="Return Home">
        </p>
    </form>

</body>

</html>
