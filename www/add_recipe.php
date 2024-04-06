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
    
    session_start();
    
    if (isset($_SESSION["username"]) && $_SESSION["loggedin"] == TRUE) {
        $username = $_SESSION["username"];
        $query = "SELECT user_id FROM users WHERE username = ?";
        $stmt = mysqli_prepare($connect, $query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($creator_id);
        $stmt->fetch();
        $stmt->close();
        echo $creator_id;
    } else {
       header("Location: index.php");
       exit;
    }
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
    $title = htmlspecialchars($_POST["title"]);
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

    // Insert recipe into the database
    $query = "INSERT INTO recipes (title, description, category, cuisine, ingredients, instructions, prep_time, cook_time, total_time, servings, creator_id)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "ssssssssss", $title, $description, $category, $cuisine, $ingredients, $instructions, $prep_time, $cook_time, $total_time, $servings, $creator_id);
    mysqli_stmt_execute($stmt);
    // Close the statement for recipe insertion
    mysqli_stmt_close($stmt);

    // Close the database connection
    mysqli_close($connect);

    // Display success message
    echo '<p>Recipe added successfully!</p>';
    print("<form><p><input type=\"submit\" formaction=\"./index.php\" value=\"Return Home\"</p></form>");
}
