<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    header("Location: index.php");
    exit; // Exit the script if the user is not logged in
}

// Database connection details
$server = "db";
$user = "admin";
$pw = "pwd";
$db = "rc";

// Establish a database connection
$connect = mysqli_connect($server, $user, $pw, $db) or die('Could not connect to the database server' . mysqli_connect_error());

function br2pipe($input)
    {
        $pattern = '/&lt;br \/&gt;/';
        $replacement = '|';
        return preg_replace($pattern, $replacement, $input, -1);
    }

// Retrieve the user ID from the session
$user_id = $_SESSION['user_id'];

// Initialize variables to store recipe details
$title = $description = $category = $cuisine = $ingredients = $instructions = "";
$prep_time = $cook_time = $total_time = $servings = 0;

// Check if the recipe_id parameter is set in the URL
if (isset($_GET['recipe_id'])) {
    $recipe_id = $_GET['recipe_id'];

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve and sanitize form data
        $title = mysqli_real_escape_string($connect, $_POST['title']);
        $description = mysqli_real_escape_string($connect, $_POST['description']);
        $category = mysqli_real_escape_string($connect, $_POST['category']);
        $cuisine = mysqli_real_escape_string($connect, $_POST['cuisine']);
        $ingredients = br2pipe(htmlspecialchars(nl2br($_POST["ingredients"])));
        $instructions = br2pipe(htmlspecialchars(nl2br($_POST["instructions"])));
        $prep_time = intval($_POST['prep_time']);
        $cook_time = intval($_POST['cook_time']);
        $total_time = intval($_POST['total_time']);
        $servings = intval($_POST['servings']);

        // Prepare SQL UPDATE statement
        $query = "UPDATE recipes SET title=?, description=?, category=?, cuisine=?, ingredients=?, instructions=?, prep_time=?, cook_time=?, total_time=?, servings=? WHERE recipe_id=? AND creator_id=?";

        // Prepare the query
        $stmt = mysqli_prepare($connect, $query);

        // Bind parameters
        mysqli_stmt_bind_param($stmt, "ssssssiiiiii", $title, $description, $category, $cuisine, $ingredients, $instructions, $prep_time, $cook_time, $total_time, $servings, $recipe_id, $user_id);

        // Execute the query
        mysqli_stmt_execute($stmt);

        // Check if any rows were affected
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Recipe updated successfully!";
        } else {
            echo "Failed to update the recipe.";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    }

    // Query to retrieve the recipe details based on recipe_id and creator_id
    $query = "SELECT title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings FROM recipes WHERE recipe_id = ? AND creator_id = ?";
    
    // Prepare the query
    $stmt = mysqli_prepare($connect, $query);

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, "ii", $recipe_id, $user_id);

    // Execute the query
    mysqli_stmt_execute($stmt);

    // Bind the result variables
    mysqli_stmt_bind_result($stmt, $title, $description, $category, $cuisine, $ingredients, $instructions, $prep_time, $cook_time, $total_time, $servings);

    // Fetch the result
    mysqli_stmt_fetch($stmt);

    // Close the statement
    mysqli_stmt_close($stmt);
}

// Close the connection
mysqli_close($connect);

// Remove the pipe character from ingredients and instructions
$ingredients = str_replace('|', '', $ingredients);
$instructions = str_replace('|', '', $instructions);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recipe</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main>
        <h1>Edit Recipe</h1>
        <form action="" method="post">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" cols="50" required><?php echo htmlspecialchars($description); ?></textarea>

            <label for="category">Category:</label>
            <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($category); ?>" required>

            <label for="cuisine">Cuisine:</label>
            <input type="text" id="cuisine" name="cuisine" value="<?php echo htmlspecialchars($cuisine); ?>" required>

            <label for="ingredients">Ingredients (enter each new ingredient on a new line):</label>
            <textarea id="ingredients" name="ingredients" rows="4" cols="50" required><?php echo htmlspecialchars($ingredients); ?></textarea>

            <label for="instructions">Instructions (enter each new instruction on a new line):</label>
            <textarea id="instructions" name="instructions" rows="6" cols="50" required><?php echo htmlspecialchars($instructions); ?></textarea>

            <label for="prep_time">Prep Time (minutes):</label>
            <input type="number" id="prep_time" name="prep_time" min="0" value="<?php echo htmlspecialchars($prep_time); ?>" required>

            <label for="cook_time">Cook Time (minutes):</label>
            <input type="number" id="cook_time" name="cook_time" min="0" value="<?php echo htmlspecialchars($cook_time); ?>" required>

            <label for="total_time">Total Time (minutes):</label>
            <input type="number" id="total_time" name="total_time" min="0" value="<?php echo htmlspecialchars($total_time); ?>" required>

            <label for="servings">Servings:</label>
            <input type="number" id="servings" name="servings" min="1" value="<?php echo htmlspecialchars($servings); ?>" required>

            <button type="submit">Submit</button>
            <button type="button" onclick="window.location.href = 'user_recipes.php';">Go Back</button>
        </form>
    </main>
</body>
</html>
