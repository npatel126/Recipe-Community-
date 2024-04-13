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
$server = "db";
$user = "admin";
$pw = "pwd";
$db = "rc";
$connect = mysqli_connect($server, $user, $pw, $db) or die('Could not connect to the database server' . mysqli_connect_error());

$owner_id = $_SESSION['user_id'];
$kitchen_id = $_GET['link'];

// DELETE KITCHEN
//
if (isset($_POST['delete'])) {
    // DELETE kitchen
    $delete_query = "DELETE FROM kitchens WHERE kitchen_id=? AND owner_id=?";

    $delete_stmt = mysqli_prepare($connect, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, "ii", $kitchen_id, $owner_id);
    mysqli_stmt_execute($delete_stmt);

    if (mysqli_stmt_affected_rows($delete_stmt) > 0) {
        header("Location: user_kitchens.php");
        exit;
    } else {
        echo "Failed to delete the kitchen.";
    }

    mysqli_stmt_close($delete_stmt);
}

// UPDATE KITCHEN
//
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kitchen_name = mysqli_real_escape_string($connect, $_POST['name']);
    $empty_arr[0] = 0;
    $kc_cbs = isset($_POST['kc_cb']) ? $_POST['kc_cb'] : $empty_arr; // array of selected cookbooks
    /*
    if ($kc_cbs === null) {
        $kc_cbs[0] = 0;
    };
    */
    //var_dump($kc_cbs);
    $qMark_arr = join(',', array_fill(0, count($kc_cbs), '?')); // turning the array of cookbooks into '?' for stmt

    // Update kitchen's name
    $query = "UPDATE kitchens SET name=? WHERE kitchen_id=? AND owner_id=?;";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "sii", $kitchen_name, $kitchen_id, $owner_id);
    mysqli_stmt_execute($stmt);

    // Update kitchen's cookbooks (ADD SELECTED)
    //$query = "UPDATE cookbooks SET cookbooks.kitchen_id=? WHERE cookbooks.owner_id=? AND cookbooks.cookbook_id IN ($qMark_arr);";
    foreach ($kc_cbs as $i => $cookbook_id) {
        $query = "INSERT IGNORE INTO kitchens_cookbooks (kitchen_id, cookbook_id) VALUES (?,?)";
        $stmt2 = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt2, "ii", $kitchen_id, $cookbook_id);
        mysqli_stmt_execute($stmt2);
    }

    // Update kitchen's cookbooks (REMOVE NON-SELECTED)
    //$query = "UPDATE cookbooks SET cookbooks.kitchen_id=null WHERE cookbooks.owner_id=? AND cookbooks.cookbook_id NOT IN ($qMark_arr);";
    $query = "Delete FROM kitchens_cookbooks WHERE kitchens_cookbooks.kitchen_id = ? AND kitchens_cookbooks.cookbook_id NOT IN ($qMark_arr);";
    $stmt3 = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt3, str_repeat('i', count($kc_cbs) + 1), $kitchen_id, ...$kc_cbs);
    mysqli_stmt_execute($stmt3);

    // we should probably redo this check ???
    if (mysqli_stmt_affected_rows($stmt) > 0 || mysqli_stmt_affected_rows($stmt2) > 0 || mysqli_stmt_affected_rows($stmt3) > 0) {
        echo "kitchen updated successfully!";
    } else {
        echo "Failed to update the kitchen.";
    }

    // Close the statements
    // TODO see if u can close them right after call or not; or maybe just one like below
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($stmt2);
    mysqli_stmt_close($stmt3);
}

// DISPLAY KITCHEN
//
// Query to retrieve the kitchen details (name and cookbooks) based on kitchen_id and owner_id
$kitchen_name = '';
$cookbook_id = null;
$cookbook_name = '';
//$query = "SELECT kitchens.name, cookbooks.cookbook_id, cookbooks.name FROM kitchens LEFT JOIN cookbooks ON cookbooks.kitchen_id = $kitchen_id WHERE kitchens.kitchen_id = $kitchen_id AND kitchens.owner_id = $owner_id;";
//$query = "Select kitchens.name, cookbooks.cookbook_id, cookbooks.name FROM kitchens LEFT JOIN kitchens_cookbooks ON kitchens.kitchen_id = kitchens_cookbooks.kitchen_id RIGHT LEFT JOIN cookbooks on cookbooks.cookbook_id = kitchens_cookbooks.cookbook_id WHERE kitchens.kitchen_id = $kitchen_id AND kitchens.owner_id = $owner_id;";

$query = "Select kitchens.name, cookbooks.cookbook_id, cookbooks.name FROM kitchens LEFT JOIN kitchens_cookbooks ON kitchens.kitchen_id = kitchens_cookbooks.kitchen_id LEFT JOIN cookbooks on cookbooks.cookbook_id = kitchens_cookbooks.cookbook_id WHERE kitchens.kitchen_id = $kitchen_id AND kitchens.owner_id = $owner_id;";

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
            if ($cookbook_ids === null) {
                $cookbook_ids[0] = 0; // define something that will never happen to print all
            }
            foreach ($all_cookbook_ids as $all_cookbook_id => $all_cookbook_name) {
                if (!array_key_exists($all_cookbook_id, $cookbook_ids)) {
                    print("<p><input type=\"checkbox\" id=\"$all_cookbook_id\" name=\"kc_cb[]\" value=\"$all_cookbook_id\" /><lable for=\"$all_cookbook_id\">$all_cookbook_name</p>");
                }
            }
            ?>
            <h4>All checked cookbooks will be placed into this kitchen</h4>
            <button type="submit">Submit</button>
            <?php
            print("<button type=\"button\" onclick=\"window.location.href = 'view_kitchen.php?link=$kitchen_id'\">Go Back</button>");
            ?>
            <button formnovalidate type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this kitchen?');">Delete kitchen </button>
        </form>
    </main>
</body>

</html>
