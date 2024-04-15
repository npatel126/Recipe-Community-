<?php
// Start session at the very beginning
session_start();
//Check user is logged in.
if (isset($_SESSION["username"]) && $_SESSION["loggedin"] == TRUE) {
    //echo "Welcome, " . $_SESSION["username"];
} else {
    header("Location: index.php");
    exit;
}
// Toggle style session variable
if ($_SESSION['darkmode']) {
    $style = "css/login_register(dark).css";
} else {
    $style = "css/login_register.css";
}
// Initialize variables for errors and success message
$errors = [];
$success = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection details
    $server = "db";
    $user = "admin";
    $pw = "pwd";
    $db = "rc";

    // Establish a database connection
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
    $name = htmlspecialchars($_POST["name"]);
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
    $query = "INSERT INTO recipes (name, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "sssssssssss", $name, $description, $category, $cuisine, $ingredients, $instructions, $prep_time, $cook_time, $total_time, $servings, $creator_id);
    mysqli_stmt_execute($stmt);
    // Check for errors
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        // Set success message
        $success = 'Recipe added successfully!';
    } else {
        $errors[] = "An error occurred while adding the recipe. Please try again later.";
    }

    // Close the statement for recipe insertion
    mysqli_stmt_close($stmt);

    // Close the database connection
    mysqli_close($connect);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $style; ?>">
    <title>Add Recipe</title>
    <style>
        .error-container,
        .success-container {
            text-align: center;
            margin-top: 20px;
        }

        .error,
        .success {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .error {
            background-color: #ffcccc;
            color: #ff0000;
        }

        .success {
            background-color: #ccffcc;
            color: #008000;
        }
    </style>
</head>
<body>
    <main>
        <?php if (!empty($errors)) : ?>
            <div class="error-container">
                <div class="error"><?php echo implode('<br>', $errors); ?></div>
            </div>
        <?php endif; ?>
        <?php if (!empty($success)) : ?>
            <div class="success-container">
                <div class="success"><?php echo $success; ?></div>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h1>Add Recipe</h1>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

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
</body>
</html>
