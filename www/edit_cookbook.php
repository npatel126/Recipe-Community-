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
$style = ($_SESSION['darkmode']) ? "css/login_register(dark).css" : "css/login_register.css";
} else {
    $style = "css/login_register.css";
}
// DB CONNECTION AND GLOBAL VARS
$server = "db";
$user = "admin";
$pw = "pwd";
$db = "rc";
$connect = mysqli_connect($server, $user, $pw, $db) or die('Could not connect to the database server' . mysqli_connect_error());

$owner_id = $_SESSION['user_id'];
$cookbook_id = $_GET['link'];

// DELETE COOKBOOK
if (isset($_POST['delete'])) {
    $delete_query = "DELETE FROM cookbooks WHERE cookbook_id=? AND owner_id=?";

    $delete_stmt = mysqli_prepare($connect, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, "ii", $cookbook_id, $owner_id);
    mysqli_stmt_execute($delete_stmt);

    if (mysqli_stmt_affected_rows($delete_stmt) > 0) {
        header("Location: user_cookbooks.php");
        exit;
    } else {
        $errors[] = "Failed to delete the cookbook.";
    }

    mysqli_stmt_close($delete_stmt);
}

// UPDATE cookbook
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cookbook_name = mysqli_real_escape_string($connect, $_POST['name']);
    $empty_arr[0] = 0;
    $cb_rcs = isset($_POST['cb_rc']) ? $_POST['cb_rc'] : $empty_arr;
    $qMark_arr = join(',', array_fill(0, count($cb_rcs), '?'));

    // Update cookbook's name
    $query = "UPDATE cookbooks SET name=? WHERE cookbook_id=? AND owner_id=?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "sii", $cookbook_name, $cookbook_id, $owner_id);
    mysqli_stmt_execute($stmt);

    // Update cookbook's recipes (ADD SELECTED)
    foreach ($cb_rcs as $i => $recipe_id) {
        $query = "INSERT IGNORE INTO cookbooks_recipes (cookbook_id, recipe_id) VALUES (?,?)";
        $stmt2 = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt2, "ii", $cookbook_id, $recipe_id);
        mysqli_stmt_execute($stmt2);
    }

    // Update cookbook's recipes (REMOVE UNSELECTED)
    $query = "DELETE FROM cookbooks_recipes WHERE cookbooks_recipes.cookbook_id = ? AND cookbooks_recipes.recipe_id NOT IN ($qMark_arr)";
    $stmt3 = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt3, str_repeat('i', count($cb_rcs) + 1), $cookbook_id, ...$cb_rcs);
    mysqli_stmt_execute($stmt3);

    if (mysqli_stmt_affected_rows($stmt) > 0 || mysqli_stmt_affected_rows($stmt2) > 0 || mysqli_stmt_affected_rows($stmt3) > 0) {
        $success = "Cookbook updated successfully!";
    } else {
        $errors[] = "Failed to update the cookbook.";
    }

    // Close the statement
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($stmt2);
    mysqli_stmt_close($stmt3);
}

// DISPLAY cookbook
$cookbook_name = '';
$recipe_id = null;
$recipe_name = '';
$query = "SELECT cookbooks.name, recipes.recipe_id, recipes.name FROM cookbooks LEFT JOIN cookbooks_recipes ON cookbooks.cookbook_id = cookbooks_recipes.cookbook_id LEFT JOIN recipes ON recipes.recipe_id = cookbooks_recipes.recipe_id WHERE cookbooks.cookbook_id = $cookbook_id AND cookbooks.owner_id = $owner_id";
$stmt = mysqli_prepare($connect, $query);
if ($stmt = $connect->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($cookbook_name, $recipe_id, $recipe_name);
}

$recipe_ids = array();
while ($stmt->fetch()) {
    $recipe_ids[$recipe_id] = $recipe_name;
}

// cookbooks with no recipes will still return a null one
if (current($recipe_ids) === null) {
    $recipe_ids = null;
}

$all_recipe_ids = array();
$all_recipe_id = null;
$all_recipe_name = '';
$query = "SELECT DISTINCT recipes.recipe_id, recipes.name FROM recipes LEFT JOIN cookbooks_recipes ON recipes.recipe_id = cookbooks_recipes.recipe_id LEFT JOIN cookbooks ON cookbooks.cookbook_id = cookbooks_recipes.cookbook_id LEFT JOIN favorites_recipes ON recipes.recipe_id = favorites_recipes.recipe_id LEFT JOIN favorites ON favorites.favorite_id = favorites_recipes.favorite_id WHERE cookbooks.owner_id = $owner_id OR favorites.owner_id = $owner_id OR recipes.creator_id = $owner_id";
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
    <title>Edit Cookbook</title>
    <link rel="stylesheet" href="<?php echo $style; ?>">
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
        <?php print("<h1>Edit $cookbook_name cookbook</h1>"); ?>
        <form action="" method="post">
            <label for="name">Cookbook name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($cookbook_name); ?>" required>
            <h3> Recipes in this cookbook </h3>
            <?php
            if (($recipe_ids !== null)) {
                foreach ($recipe_ids as $recipe_id => $recipe_name) {
                    print("<p><input type=\"checkbox\" id=\"$recipe_id\" name=\"cb_rc[]\" value=\"$recipe_id\" checked/><lable for=\"$recipe_id\">$recipe_name</lable></p>");
                }
            } else {
                print("Select a recipe to add it to you cookbook!");
            }
            ?>
            <h3> My recipes </h3>
            <?php
            if ($recipe_ids === null) {
                $recipe_ids[0] = 0; // define something that will never happen to print all
            }
            foreach ($all_recipe_ids as $all_recipe_id => $all_recipe_name) {
                if (!array_key_exists($all_recipe_id, $recipe_ids)) {
                    print("<p><input type=\"checkbox\" id=\"$all_recipe_id\" name=\"cb_rc[]\" value=\"$all_recipe_id\" /><lable for=\"$all_recipe_id\">$all_recipe_name</p>");
                }
            }
            ?>
            <h4>All checked recipes will be placed into this cookbook</h4>
            <h4>All un-checked recipes will be removed from this cookbook</h4>
            <button type="submit">Submit</button>
            <?php
            print("<button type=\"button\" onclick=\"window.location.href = 'view_cookbook.php?link=$cookbook_id'\">Go Back</button>");
            ?>
            <button formnovalidate type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this cookbook?');">Delete cookbook </button>
        </form>
    </main>
</body>

</html>
