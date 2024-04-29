<?php
// Start the session
session_start();

// Check if the user is logged in
if (isset($_SESSION["username"]) && $_SESSION["loggedin"] == TRUE) {
    //echo "Welcome, " . $_SESSION["username"];
} else {
    header("Location: index.php");
    exit;
}

// Toggle style session variable
if ($_SESSION['darkmode']) {
    $style = "css/upload_recipe(dark).css";
} else {
    $style = "css/upload_recipe.css";
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

    // TODO: check is set?
    // htmlspecialchars seems to mess with the json
    //$raw = htmlspecialchars($_POST["recipe_import"]);
    //
    // get raw json
    $raw = $_POST["recipe_import"];

    // TODO: im not sure this errors out correctly
    //var_dump(json_validate($raw, 1, JSON_INVALID_UTF8_IGNORE));
    //
    // validate its valid json
    if (json_validate($raw)) {
        // decode into associative array
        $recipe_import = json_decode($raw, true);

        // set params
        $name = $recipe_import['name'];
        $description = $recipe_import['description'];
        $category = $recipe_import['category'];
        $cuisine = $recipe_import['cuisine'];
        $ingredients = $recipe_import['ingredients'];
        $instructions = $recipe_import['instructions'];
        $prep_time = $recipe_import['prep_time'];
        $cook_time = $recipe_import['cook_time'];
        $total_time = $recipe_import['total_time'];
        $servings = $recipe_import['servings'];
        $creator_id = $recipe_import['creator_id'];

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

        // unset json post var
        // TODO: this should prevent page reloading from re-entering the recipe but its not
        if (isset($_POST["recipe_import"])) {
            unset($_POST["recipe_import"]);
        }

        // !!!BUG!!! currently refresting re-enters the json recipe
        // nothing i tried resolved it
        // to reset the auto inc in the db after deleting erroneous entries, run
        // ALTER TABLE recipes AUTO_INCREMENT = n
        //// where n is the last valid recipe_id (AUTO_INCREMENT PRIMARY KEY)

    } else {
        // error
        array_push($errors, "NOT VALID JSON");
    }
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
            <h1>Upload Recipe</h1>
            <style>
                label {
                    display: block;
                }

                textarea {
                    display: block;
                }
            </style>
            <label for="recipe_import">Paste in a JSON recipe!</label>
            <textarea id="recipe_import" name="recipe_import" rows="4" cols="50" required></textarea>

            <br>
            <button type="submit">Submit</button>
            <button type="submit" formaction="./dashboard.php" formnovalidate>Return</button>
        </form>
    </main>
</body>

</html>
