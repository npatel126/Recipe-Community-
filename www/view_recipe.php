<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    header("Location: index.php");
    exit; // Exit the script if the user is not logged in
}

$user_id = $_SESSION["user_id"];

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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recipe Search Results</title>
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
    $creator_id = "";
    $creator_username = "";

    // Search DB for recipe(s) matching description
    $query = "SELECT r.recipe_id, r.title, r.description, r.category, r.cuisine, r.ingredients, r.instructions, r.prep_time, r.cook_time, r.total_time, r.servings, r.creator_id, u.username
              FROM recipes r
              INNER JOIN users u ON r.creator_id = u.user_id
              WHERE r.recipe_id = '$search'; ";

    $stmt = mysqli_prepare($connect, $query);

    if ($stmt = $connect->prepare($query)) {
        $stmt->execute();
        $stmt->bind_result($recipe_id, $title, $description, $category, $cuisine, $ingredients, $instructions, $prep_time, $cook_time, $total_time, $servings, $creator_id, $creator_username);
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
        print("<h2>Creator: $creator_username </h2>");
        print("<h3>Description: $description </h3>");
        print("<h3>Category: $category </h3>");
        print("<h3>Cuisine: $cuisine </h3>");
        print("<h3>Prep time: $formatted_prep </h3>");
        print("<h3>Cook time: $formatted_cook </h3>");
        print("<h3>Total time: $formatted_total </h3>");
        print("<h3>Servings: $servings</h3>");
        print("<h4>Ingredients:</h4>");
        for ($i = 0; $i < sizeof($ingredients_list); $i++) {
            print("<p><input type=\"checkbox\"/> $ingredients_list[$i]</p>");
        }
        print("<h4>Instructions:</h4>");
        // TODO: consider checking if the first position in each string is a number and if not display one??
        for ($i = 0; $i < sizeof($instructions_list); $i++) {
            print("<p>$instructions_list[$i]</p>");
        }
    }
    // close statement
    $stmt->close();
    

    // add/remove from favorites
    // if not in favorites display add 
    // if in favorites display remove

    function check_favorite($connect, $u_id, $r_id)
    {
        $is_favorite = false;
        $is_owner = null;
        $query = "SELECT owner_id FROM favorites WHERE recipe_id = $r_id ;";
        $stmt = "";
        if ($stmt = $connect->prepare($query)) {
            $stmt->execute();
            $stmt->bind_result($is_owner);
        }
        while ($stmt->fetch()) {
            if ($is_owner != null && $is_owner == $u_id) {
                $is_favorite = true;
            }
        }
        $stmt->close();
        return $is_favorite;
    }

    // TODO: These two functions (add/remove favorite) probably need better checks for db stuff

    function add_favorite($connect, $u_id, $r_id)
    {
        $query = "INSERT INTO favorites (owner_id, recipe_id) VALUES (?, ?); ";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "ii", $u_id, $r_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    function remove_favorite($connect, $u_id, $r_id)
    {
        $query = "DELETE FROM favorites WHERE owner_id = $u_id AND recipe_id = $r_id; ";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    if(isset($_POST['favorite'])) {
        add_favorite($connect, $user_id, $recipe_id);
    } 
    if(isset($_POST['unfavorite'])){
        remove_favorite($connect, $user_id, $recipe_id);
    }

    if (check_favorite($connect, $user_id, $recipe_id)) {
        # is in favorites
        print("<p> <i>This recipe is in your favorites.</i> </p>");
        print("<form method=\"post\"><p><input type=\"submit\" name=\"unfavorite\" value=\"Unfavorite!\"></p></form>");
    } else {
        # is not in favorites
        print("<p> <i>This recipe is not in your favorites.</i> </p>");
        print("<form method=\"post\"><p><input type=\"submit\" name=\"favorite\" value=\"Favorite!\"></p></form>");
    }


    // close connection with db
    mysqli_close($connect);

    ?>
    <form>
        <p>
            <input type="submit" formaction="./recipe_search.php" value="Search Again!">
            <input type="submit" formaction="./dashboard.php" value="Return to Dashboard">
        </p>
    </form>

</body>

</html>
