<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    header("Location: index.php");
    exit; // Exit the script if the user is not logged in
}

 // Toggle style session variable
 if ($_SESSION['darkmode']) {
    $style = "css/view_list(dark).css";
    } else {
    $style = "css/view_list.css";
    }
// Database connection details
$server = "db";
$user = "admin";
$pw = "pwd";
$db = "rc";

// Establish a database connection
$connect = mysqli_connect($server, $user, $pw, $db) or die('Could not connect to the database server' . mysqli_connect_error());

// Retrieve the username from the session
$username = $_SESSION['username'];

// Query to retrieve the user_id based on the username
$query = "SELECT user_id FROM users WHERE username = ?";

// Prepare the query
$stmt = mysqli_prepare($connect, $query);

// Bind the parameters
mysqli_stmt_bind_param($stmt, "s", $username);

// Execute the query
mysqli_stmt_execute($stmt);

// Bind the result variable
mysqli_stmt_bind_result($stmt, $user_id);

// Fetch the result
mysqli_stmt_fetch($stmt);

// Close the statement
mysqli_stmt_close($stmt);

// Query to retrieve recipes belonging to the current user
$query_recipes = "SELECT recipe_id, title, description, category FROM recipes WHERE creator_id = ?";

// Prepare the query for recipes
$stmt_recipes = mysqli_prepare($connect, $query_recipes);

// Bind the user ID parameter
mysqli_stmt_bind_param($stmt_recipes, "i", $user_id);

// Execute the query for recipes
mysqli_stmt_execute($stmt_recipes);

// Bind the result variables
mysqli_stmt_bind_result($stmt_recipes, $recipe_id, $title, $description, $category);

// Display the recipes
if (mysqli_stmt_fetch($stmt_recipes)) {
    echo "<h1> Your Recipes</h1>";
    echo "<table border='1'>";
    echo "<tr> <th>Title</th> <th>Description</th> <th>Category</th> <th>View recipe</th> <th>Edit</th> </tr>";
    do {
        echo "<tr><td>$title</td><td>$description</td><td>$category</td><td><a href=\"view_recipe.php?link=$recipe_id\">View this Recipe!</a></td><td><a href=\"edit_recipes.php?recipe_id=$recipe_id\">Edit</a></td></tr>";
    } while (mysqli_stmt_fetch($stmt_recipes));
    echo "</table>";
} else {
    echo "<h1> You have no recipes yet.</h1>";
}

// Close the statement and connection
mysqli_stmt_close($stmt_recipes);
mysqli_close($connect);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo $style; ?>">
</head>

<body>
<form action="./dashboard.php">
    <input type="submit" value="Return">
</form>
</body>

</html>
