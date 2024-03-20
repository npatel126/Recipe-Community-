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

    // Sanitize and validate form inputs
    $title = htmlspecialchars($_POST["title"]);
    $description = htmlspecialchars($_POST["description"]);
    $category = htmlspecialchars($_POST["category"]);
    $ingredients = htmlspecialchars($_POST["ingredients"]);
    $instructions = htmlspecialchars($_POST["instructions"]);
    $prep_time = intval($_POST["prep_time"]);
    $cook_time = intval($_POST["cook_time"]);
    $total_time = intval($_POST["total_time"]);
    $servings = intval($_POST["servings"]);
    // Creator id is admin for now.
    $creator_id = 1;

    // Insert recipe into the database
    $query = "INSERT INTO recipes (title, description, category, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "ssssssssss", $title, $description, $category, $ingredients, $instructions, $prep_time, $cook_time, $total_time, $servings, $creator_id);
    mysqli_stmt_execute($stmt);
    // Close the statement for recipe insertion
    mysqli_stmt_close($stmt);

    // Close the database connection
    mysqli_close($connect);

    // Display success message
    echo '<p>Recipe added successfully!</p>';
}
?>
