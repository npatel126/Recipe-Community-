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
    $style = "css/login_register(dark).css";
} else {
    $style = "css/login_register.css";
}

// DB CONNECTION AND GLOBAL VARS
// Database connection details
$server = "db";
$user = "admin";
$pw = "pwd";
$db = "rc";

// Establish a database connection
$connect = mysqli_connect($server, $user, $pw, $db) or die('Could not connect to the database server' . mysqli_connect_error());

// Retrieve the user ID from the session
$owner_id = $_SESSION['user_id'];

// Get cookbook_id from incoming link
$cookbook_id = $_GET['link'];

// DELETE COOKBOOK
//
// Check if the delete button is clicked
if (isset($_POST['delete'])) {
    // Prepare SQL DELETE statement
    $delete_query = "DELETE FROM cookbook_id WHERE cookbook_id=? AND owner_id=?";

    // Prepare the delete query
    $delete_stmt = mysqli_prepare($connect, $delete_query);

    // Bind parameters
    mysqli_stmt_bind_param($delete_stmt, "ii", $cookbook_id, $owner_id);

    // Execute the delete query
    mysqli_stmt_execute($delete_stmt);

    // Check if any rows were affected
    if (mysqli_stmt_affected_rows($delete_stmt) > 0) {
        // Redirect to user_cookbooks.php
        header("Location: user_cookbooks.php");
        exit;
    } else {
        echo "Failed to delete the cookbook.";
    }

    // Close the delete statement
    mysqli_stmt_close($delete_stmt);
}

// UPDATE cookbook
//
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $cookbook_name = mysqli_real_escape_string($connect, $_POST['name']);
    $cb_rcs = $_POST['cb_rc'];
    $qMark_arr = join(',', array_fill(0, count($cb_rcs), '?'));

    // Prepare SQL UPDATE statement to update cookbook name
    $query = "UPDATE cookbooks SET name=? WHERE cookbook_id=? AND owner_id=?;";

    // Prepare the query
    $stmt = mysqli_prepare($connect, $query);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "sii", $cookbook_name, $cookbook_id, $owner_id);

    // Execute the query
    mysqli_stmt_execute($stmt);

    // Prepare SQL UPDATE statement to update cookbook's recipes (ADD SELECTED)
    $query = "UPDATE recipes SET recipes.cookbook_id=? WHERE cookbooks.owner_id=? AND recipes.recipe_id IN ($qMark_arr);";

    // Prepare the query
    $stmt2 = mysqli_prepare($connect, $query);

    // Bind parameters
    mysqli_stmt_bind_param($stmt2, str_repeat('i', count($cb_rcs) + 2), $cookbook_id, $owner_id, ...$cb_rcs);

    // Execute the query
    mysqli_stmt_execute($stmt2);

    // Prepare SQL UPDATE statement to update cookbook's recipes (REMOVE UNSELECTED)
    $query = "UPDATE recipes SET recipes.cookbook_id=null WHERE cookbooks.owner_id=? AND recipes.recipes_id NOT IN ($qMark_arr);";

    // Prepare the query
    $stmt3 = mysqli_prepare($connect, $query);

    // Bind parameters
    mysqli_stmt_bind_param($stmt3, str_repeat('i', count($cb_rcs) + 1), $owner_id, ...$cb_rcs);

    // Execute the query
    mysqli_stmt_execute($stmt3);

    // Check if any rows were affected
    // we should probably redo this check ???
    if (mysqli_stmt_affected_rows($stmt) > 0 || mysqli_stmt_affected_rows($stmt2) > 0 || mysqli_stmt_affected_rows($stmt3) > 0) {
        echo "cookbook updated successfully!";
    } else {
        echo "Failed to update the cookbook.";
    }

    // Close the statement
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($stmt2);
}

// DISPLAY cookbook
//
// Query to retrieve the cookbook details (name and cookbooks) based on cookbook_id and owner_id
$cookbook_name = '';
$recipe_id = null;
$recipe_name = '';
$query = "SELECT cookbooks.name, recipes.recipe_id, recipes.title FROM recipes JOIN cookbooks ON cookbooks.cookbook_id = $cookbook_id WHERE recipes.cookbook_id = $cookbook_id AND cookbooks.owner_id = $owner_id;";

$stmt = mysqli_prepare($connect, $query);
if ($stmt = $connect->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($cookbook_name, $recipe_id, $recipe_name);
}

$recipe_ids = array();
while ($stmt->fetch()) {
    $recipe_ids[$recipe_id] = $recipe_name;
}


// get info to display all of the users cookbooks
$all_recipe_ids = array();
$all_recipe_id = null;
$all_recipe_name = '';
$query = "SELECT DISTINCT recipes.recipe_id, recipes.title FROM recipes LEFT JOIN cookbooks ON recipes.cookbook_id = cookbooks.cookbook_id LEFT JOIN favorites ON recipes.recipe_id = favorites.recipe_id WHERE cookbooks.owner_id = $owner_id OR favorites.owner_id = $owner_id OR recipes.creator_id = $owner_id;";

$stmt = mysqli_prepare($connect, $query);
if ($stmt = $connect->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($all_recipe_id, $all_recipe_name);
}

while ($stmt->fetch()) {
    $all_recipe_ids[$all_recipe_id] = $all_recipe_name;
}

$stmt->close();
mysqli_close($connect);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit cookbook</title>
    <link rel="stylesheet" href="<?php echo $style; ?>">
</head>

<body>
    <main>
        <?php print("<h1>Edit $cookbook_name cookbook</h1>"); ?>
        <form action="" method="post">
            <label for="name">Cookbook name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($cookbook_name); ?>" required>
            <!-- todo: put cookbooks here to add/remove. see about using check box list -->
            <h3> Recipes in this cookbook </h3>
            <?php
            foreach ($recipe_ids as $recipe_id => $recipe_name) {
                print("<p><input type=\"checkbox\" id=\"$recipe_id\" name=\"cb_rc[]\" value=\"$recipe_id\" checked/><lable for=\"$recipe_id\">$recipe_name</lable></p>");
            }
            ?>
            <h3> My recipes </h3>
            <?php
            foreach ($all_recipe_ids as $all_recipe_id => $all_recipe_name) {
                print("<p><input type=\"checkbox\" id=\"$all_recipe_id\" name=\"cb_rc[]\" value=\"$all_recipe_id\" /><lable for=\"$all_recipe_id\">$all_recipe_name</p>");
            }
            ?>
            <h4>All checked recipes will be placed into this cookbook</h4>
            <button type="submit">Submit</button>
            <button type="button" onclick="window.location.href = 'user_cookbooks.php';">Go Back</button>
            <button formnovalidate type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this cookbook?');">Delete cookbook </button>
        </form>
    </main>
</body>

</html>
