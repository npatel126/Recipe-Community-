<?php
session_start();
if (isset($_SESSION["username"]) && $_SESSION["loggedin"] == TRUE) {
    // echo "Welcome, " . $_SESSION["username"];
} else {
    header("Location: index.php");
    exit;
}

// Toggle style session variable
if ($_SESSION['darkmode']) {
    $style = "css/view_list(dark).css";
} else {
    $style = "css/view_list.css";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cookbooks</title>
    <link rel="stylesheet" href="<?php echo $style; ?>">
</head>

<body>
    <header>
        <h1>Welcome to Your Cookbooks, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>!</h1>
    </header>
    <?php
    // Database connection details
    $server = "db";
    $user = "admin";
    $pw = "pwd";
    $db = "rc";

    $connect = mysqli_connect($server, $user, $pw, $db) or die('Could not connect to the database server' . mysqli_connect_error());

    // Retrieve the user ID from the session
    $user_id = $_SESSION['user_id'];
    $kitchen_name = '';
    $cookbook_id = null;
    $cookbook_name = '';
    $query = "SELECT cookbooks.cookbook_id, cookbooks.name, kitchens.name FROM cookbooks JOIN kitchens ON kitchens.owner_id  = $user_id ; ";

    $stmt = mysqli_prepare($connect, $query);
    if ($stmt = $connect->prepare($query)) {
        $stmt->execute();
        $stmt->bind_result($cookbook_id, $cookbook_name, $kitchen_name);
    }

    $uname = $_SESSION["username"];
    $cookbook_ids = array();
    while ($stmt->fetch()) {
        $cookbook_ids[$cookbook_id] = $cookbook_name;
    }

    $stmt->close();
    mysqli_close($connect);
    ?>

<main>
        <h1>Cookbooks</h1>
        <table>
            <thead>
                <tr>
                    <th>Cookbook Name</th>
                    <th>View Cookbook</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cookbook_ids as $cookbook_id => $cookbook_name) : ?>
                    <tr>
                        <td><?php echo $cookbook_name; ?></td>
                        <td><a href="view_cookbook.php?link=<?php echo $cookbook_id; ?>">View this Cookbook!</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <form>
    <input type="submit" formaction="./dashboard.php" value="Return to Dashboard">
    </form>
</body>

</html>
