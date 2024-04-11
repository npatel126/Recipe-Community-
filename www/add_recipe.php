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
    <title>Add Recipe</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main>
        <h1>Add Recipe</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" cols="50" required></textarea>

            <label for="category">Category:</label>
            <input type="text" id="category" name="category" required>

            <label for="cuisine">Cuisine:</label>
            <input type="text" id="cuisine" name="cuisine" required>

            <label for="ingredients">Ingredients (enter each new ingredient on a new line):</label>
            <textarea id="ingredients" name="ingredients" rows="4" cols="50" required></textarea>

            <label for="instructions">Instructions (enter each new instruction on a new line):</label>
            <textarea id="instructions" name="instructions" rows="6" cols="50" required></textarea>

            <label for="prep_time">Prep Time (minutes):</label>
            <input type="number" id="prep_time" name="prep_time" min="0" required>

            <label for="cook_time">Cook Time (minutes):</label>
            <input type="number" id="cook_time" name="cook_time" min="0" required>

            <label for="total_time">Total Time (minutes):</label>
            <input type="number" id="total_time" name="total_time" min="0" required>

            <label for="servings">Servings:</label>
            <input type="number" id="servings" name="servings" min="1" required>

            <button type="submit">Submit</button>
            <button type="submit" formaction="./dashboard.php" formnovalidate>Return</button>

        </form>
    </main>

<?php

// Database connection details
$server = "db";
$user = "admin";
$pw = "pwd";
$db = "rc";

// Check if the script is accessed through a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Establish a database connection or exit with an error message
    $connect = mysqli_connect($server, $user, $pw, $db) or die('Could not connect to the database server' . mysqli_connect_error());
    
   
    /*
 * This function replaces the html break with a pipe ("|")
 * In order to get the ingredients and instructions to display correctly we are replacing new lines with breaks and then converting those breaks into pipes so that they can be stored in the db as a single line but exploded upon retrieval for formatted display
 */
    function br2pipe($input)
    {
        // this nasty pattern is what's needed to replace "<br />" with "|" for some reason
        // edit: the reason is because the htmlspecialchars func call is replacing < & > with &lt; and &gt; respectively  
        // TODO: see about going straight from newline to pipe?
        $pattern = '/&lt;br \/&gt;/';
        $replacement = '|';
        return preg_replace($pattern, $replacement, $input, -1);
    }

    // Sanitize and validate form inputs
    $title = htmlspecialchars($_POST["title"]);
    $description = htmlspecialchars($_POST["description"]);
    $category = htmlspecialchars($_POST["category"]);
    $cuisine = htmlspecialchars($_POST["cuisine"]);
    // Here ingredient's & instruction's inputs are undergoing the following chain:
    // raw in -> new lines => html breaks -> html breaks retained -> html breaks => pipes
    //
    // TODO: TBH I'm not sure if the htmlspecialchars call is necessary, look into
    $ingredients = br2pipe(htmlspecialchars(nl2br($_POST["ingredients"])));
    $instructions = br2pipe(htmlspecialchars(nl2br($_POST["instructions"])));
    $prep_time = intval($_POST["prep_time"]);
    $cook_time = intval($_POST["cook_time"]);
    $total_time = intval($_POST["total_time"]);
    $servings = intval($_POST["servings"]);
    $creator_id = $_SESSION["user_id"];

    // Insert recipe into the database
    $query = "INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "sssssssssss", $title, $description, $category, $cuisine, $ingredients, $instructions, $prep_time, $cook_time, $total_time, $servings, $creator_id);
    mysqli_stmt_execute($stmt);
    // Close the statement for recipe insertion
    mysqli_stmt_close($stmt);

    // Close the database connection
    mysqli_close($connect);

    // Display success message
    echo '<p>Recipe added successfully!</p>';
    print("<form><p><input type=\"submit\" formaction=\"./index.php\" value=\"Return Home\"</p></form>");
}

?>

</body>
</html>
