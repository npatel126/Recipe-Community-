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
    $cuisine = "";
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
        $stmt->bind_result($recipe_id, $title, $description, $category, $cuisine, $ingredients, $instructions, $prep_time, $cook_time, $total_time, $servings, $creator_id);
    }

    function format_time($time)
    {
        $formatted_time = null;
        if ($time > 60) {
            $formatted_time = intdiv($time, 60) . " hr(s) " . $time % 60 . " min";
        } elseif ($time == 60) {
            $formatted_time = "1 hr";
        } else {
            // if time is less than an hr do nothing and display it as is
            $formatted_time = $time . " min";
        }
        return $formatted_time;
    }

    // TODO: trim ???
    while ($stmt->fetch()) {
        $ingredients_list = explode('|', $ingredients);
        $instructions_list = explode('|', $instructions);
        $formatted_prep = format_time($prep_time);
        $formatted_cook = format_time($cook_time);
        $formatted_total = format_time($total_time);
        print("<h1>$title</h1>");
        print("<h2>Description: $description</h2>");
        print("<h3>Category: $category</h3>");
        print("<h3>Cuisine: $cuisine</h3>");
        print("<h3>Prep time: $formatted_prep </h3>");
        print("<h3>Cook time: $formatted_cook </h3>");
        print("<h3>Total time: $formatted_total </h3>");
        print("<h3>Servings: $servings</h3>");
        print("<h2>Ingredients:</h2>");
        for ($i = 0; $i < sizeof($ingredients_list); $i++) {
            print("<p><input type=\"checkbox\"/> $ingredients_list[$i]</p>");
        }
        print("<h2>Instructions:</h2>");
        // TODO: consider checking if the first position in each string is a number and if not display one??
        for ($i = 0; $i < sizeof($instructions_list); $i++) {
            print("<p>$instructions_list[$i]</p>");
        }
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
            <input type="submit" formaction="./index.php" value="Return Home">
        </p>
    </form>

</body>

</html>
