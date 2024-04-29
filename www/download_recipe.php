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

$recipe = $_GET['link'];

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

$connect = mysqli_connect($server, $user, $pw, $db) or die('Could not connect to the database server' . mysqli_connect_error());

$search = $_GET['link'];
// init vars for query return
$name = "";
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
$query = "SELECT r.name, r.description, r.category, r.cuisine, r.ingredients, r.instructions, r.prep_time, r.cook_time, r.total_time, r.servings, r.creator_id, u.username
            FROM recipes r
            INNER JOIN users u ON r.creator_id = u.user_id
            WHERE r.recipe_id = '$search'; ";

$stmt = mysqli_prepare($connect, $query);

if ($stmt = $connect->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($name, $description, $category, $cuisine, $ingredients, $instructions, $prep_time, $cook_time, $total_time, $servings, $creator_id, $creator_username);
}

while ($stmt->fetch()) {
    $export_arr = array('name' => $name, 'description' => $description, 'category' => $category, 'cuisine' => $cuisine, 'ingredients' => $ingredients, 'instructions' => $instructions, 'prep_time' => $prep_time, 'cook_time' => $cook_time, 'total_time' => $total_time, 'servings' => $servings, 'creator_id' => $creator_id);
}

// close statement
$stmt->close();

$export_json = json_encode($export_arr);
header_remove("Content-Type");
header_remove("Contenet-Disposition");
header('Content-Type: application/json');
$filename = 'Contenet-Disposition: attachement; filename=' . urlencode($name) . '.json';
header($filename);
echo $export_json;
