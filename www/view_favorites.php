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
    <title>Favorites</title>
    <link rel="stylesheet" href="<?php echo $style; ?>">
</head>

<body>

    <?php
    // Database connection details
    $server = "db";
    $user = "admin";
    $pw = "pwd";
    $db = "rc";

    // I removed the "Check if the script is accessed through a POST request." 
    // hopefully that isn't problematic
    //
    // Establish a database connection or exit with an error message
    $connect = mysqli_connect($server, $user, $pw, $db) or die('Could not connect to the database server' . mysqli_connect_error());


    // Get session user's id
    $owner_id = $_SESSION["user_id"];

    // init vars for query return
    $title = "";
    $description = "";
    $category = "";

    // Search DB for the user's favorite recipe(s) 
    //$query = "SELECT recipes.recipe_id, title, description, category FROM recipes JOIN favorites ON favorites.recipe_id = recipes.recipe_id JOIN users ON favorites.owner_id = users.user_id WHERE users.user_id = ?; ";
    $query = "SELECT recipes.recipe_id, recipes.name, recipes.description, recipes.category FROM recipes JOIN favorites_recipes ON recipes.recipe_id = favorites_recipes.recipe_id JOIN favorites on favorites.favorite_id = favorites_recipes.favorite_id WHERE favorites.owner_id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $owner_id);
    $stmt->execute();
    $stmt->bind_result($recipe_id, $title, $description, $category);

    if ($stmt->fetch()) {

        print("<h1> My favortie recipes</h1>");

        print("<table border=1>");
        print("<tr> <th>Title</th> <th>Description</th> <th>Category</th> <th>View</th> </tr>");

        do {
            print("<tr><td>$title</td><td>$description</td><td>$category</td><td><a href=\"view_recipe.php?link=$recipe_id\">View this Recipe!</a></td></tr>");
        } while ($stmt->fetch());

        print("</table>");
    } else {
        print("<h1> You currently have no favorite recipes </h1>");
    }

    // close statement
    $stmt->close();
    // close connection with db
    mysqli_close($connect);
    ?>


    <!-- TODO: this will probably need to change with user sessions -->
    <form>
        <p>
            <input type="submit" formaction="./dashboard.php" value="Return">
        </p>
    </form>

</body>

</html>
