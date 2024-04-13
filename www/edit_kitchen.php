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

// Get kitchen_id from incoming link
$kitchen_id = $_GET['link'];

// DELETE KITCHEN
//
// Check if the delete button is clicked
if (isset($_POST['delete'])) {
    // Prepare SQL DELETE statement
    $delete_query = "DELETE FROM kitchens WHERE kitchen_id=? AND owner_id=?";

    // Prepare the delete query
    $delete_stmt = mysqli_prepare($connect, $delete_query);

    // Bind parameters
    mysqli_stmt_bind_param($delete_stmt, "ii", $kitchen_id, $owner_id);

    // Execute the delete query
    mysqli_stmt_execute($delete_stmt);

    // Check if any rows were affected
    if (mysqli_stmt_affected_rows($delete_stmt) > 0) {
        // Redirect to user_kitchens.php
        header("Location: user_kitchens.php");
        exit;
    } else {
        echo "Failed to delete the kitchen.";
    }

    // Close the delete statement
    mysqli_stmt_close($delete_stmt);
}

// UPDATE KITCHEN
//
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $kitchen_name = mysqli_real_escape_string($connect, $_POST['name']);
    $kc_cbs = $_POST['kc_cb'];
    //var_dump($kc_cbs);
    $qMark_arr = join(',', array_fill(0, count($kc_cbs), '?'));

    // Prepare SQL UPDATE statement to update kitchen name
    $query = "UPDATE kitchens SET name=? WHERE kitchen_id=? AND owner_id=?;";

    // Prepare the query
    $stmt = mysqli_prepare($connect, $query);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "sii", $kitchen_name, $kitchen_id, $owner_id);

    // Execute the query
    mysqli_stmt_execute($stmt);

    // Prepare SQL UPDATE statement to update kitchen's cookbooks (ADD SELECTED)
    $query = "UPDATE cookbooks SET cookbooks.kitchen_id=? WHERE cookbooks.owner_id=? AND cookbooks.cookbook_id IN ($qMark_arr);";

    // Prepare the query
    $stmt2 = mysqli_prepare($connect, $query);

    // Bind parameters
    mysqli_stmt_bind_param($stmt2, str_repeat('i', count($kc_cbs) + 2), $kitchen_id, $owner_id, ...$kc_cbs);

    // Execute the query
    mysqli_stmt_execute($stmt2);

    // Prepare SQL UPDATE statement to update kitchen's cookbooks (REMOVE UNSELECTED)
    //$query = "UPDATE cookbooks SET cookbooks.kitchen_id=null WHERE cookbooks.owner_id=? AND cookbooks.cookbook_id NOT IN ($qMark_arr);";

    // Prepare the query
    //$stmt3 = mysqli_prepare($connect, $query);

    // Bind parameters
    //mysqli_stmt_bind_param($stmt3, str_repeat('i', count($kc_cbs) + 1), $owner_id, ...$kc_cbs);

    // Execute the query
    //mysqli_stmt_execute($stmt3);

    // Check if any rows were affected
    // we should probably redo this check ???
    if (mysqli_stmt_affected_rows($stmt) > 0 || mysqli_stmt_affected_rows($stmt2) > 0 || mysqli_stmt_affected_rows($stmt3) > 0) {
        echo "kitchen updated successfully!";
    } else {
        echo "Failed to update the kitchen.";
    }

    // Close the statement
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($stmt2);
}

// DISPLAY KITCHEN
//
// Query to retrieve the kitchen details (name and cookbooks) based on kitchen_id and owner_id
$kitchen_name = '';
$cookbook_id = null;
$cookbook_name = '';
$query = "SELECT kitchens.name, cookbooks.cookbook_id, cookbooks.name FROM kitchens LEFT JOIN cookbooks ON cookbooks.kitchen_id = $kitchen_id WHERE kitchens.kitchen_id = $kitchen_id AND kitchens.owner_id = $owner_id;";

$stmt = mysqli_prepare($connect, $query);
if ($stmt = $connect->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($kitchen_name, $cookbook_id, $cookbook_name);
}

$cookbook_ids = array();
while ($stmt->fetch()) {
    $cookbook_ids[$cookbook_id] = $cookbook_name;
}


// kitchens with no cookbooks will still return a null one
if (current($cookbook_ids) === null) {
    $cookbook_ids = null;
}


// get info to display all of the users cookbooks
$all_cookbook_ids = array();
$all_cookbook_id = null;
$all_cookbook_name = '';
$query = "SELECT cookbooks.cookbook_id, cookbooks.name FROM cookbooks WHERE cookbooks.owner_id = $owner_id ;";

$stmt = mysqli_prepare($connect, $query);
if ($stmt = $connect->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($all_cookbook_id, $all_cookbook_name);
}

while ($stmt->fetch()) {
    $all_cookbook_ids[$all_cookbook_id] = $all_cookbook_name;
}

$stmt->close();
mysqli_close($connect);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kitchen</title>
    <link rel="stylesheet" href="<?php echo $style; ?>">
</head>

<body>
    <main>
        <?php print("<h1>Edit $kitchen_name kitchen</h1>"); ?>
        <form action="" method="post">
            <label for="name">Kitchen name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($kitchen_name); ?>" required>
            <h3> Cookbooks in this kitchen </h3>
            <?php
            if ($cookbook_ids !== null) {
                foreach ($cookbook_ids as $cookbook_id => $cookbook_name) {
                    print("<p><input type=\"checkbox\" id=\"$cookbook_id\" name=\"kc_cb[]\" value=\"$cookbook_id\" checked/><lable for=\"$cookbook_id\">$cookbook_name</lable></p>");
                }
            } else {
                print("Select a cookbook to add it to your kitchen!");
            }
            ?>
            <h3> My cookbooks </h3>
            <?php
            foreach ($all_cookbook_ids as $all_cookbook_id => $all_cookbook_name) {
                print("<p><input type=\"checkbox\" id=\"$all_cookbook_id\" name=\"kc_cb[]\" value=\"$all_cookbook_id\" /><lable for=\"$all_cookbook_id\">$all_cookbook_name</p>");
            }
            ?>
            <h4>All checked cookbooks will be placed into this kitchen</h4>
            <button type="submit">Submit</button>
            <button type="button" onclick="window.location.href = 'user_kitchens.php';">Go Back</button>
            <button formnovalidate type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this kitchen?');">Delete kitchen </button>
        </form>
    </main>
</body>

</html>
