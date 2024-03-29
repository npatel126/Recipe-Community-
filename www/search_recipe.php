<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recipe Search Results</title>
    <!--
    <link href="css/style.css" rel="stylesheet">
    -->
</head>

<body>

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

        //TODO figure out how to determine the category before search, that way we dont search for the wrong thing? i think? idk i have to get it to work first

        // Sanitize and validate form inputs
        $search = htmlspecialchars($_POST["search"]);
        $type = $_POST["type"];

        // init vars for query return
        $title = "";
        $description = "";
        $category = "";

        // Search DB for recipe(s) matching description
        $query = "SELECT title, description, category FROM recipes WHERE $type LIKE '%$search%' OR $type LIKE '%$search' OR $type LIKE '$search%'; ";
        $stmt = mysqli_prepare($connect, $query);
    }

    if ($stmt = $connect->prepare($query)) {
        $stmt->execute();
        $stmt->bind_result($title, $description, $category);
    }

    if ($stmt->fetch()) {

        print("<h1> Search Results for $search </h1>");

        print("<table border=1>");
        print("<tr> <th>Title</th> <th>Description</th> <th>Category</th> <th>View</th> </tr>");

        do {
            print("<tr><td>$title</td><td>$description</td><td>$category</td><td><a href=\"view_recipe.php?link=$title\">View this Recipe!</a></td></tr>");
        } while ($stmt->fetch());

        print("</table>");
    } else {
        print("<h1> $search returned no results </h1>");
    }

    // close statement
    $stmt->close();
    // close connection with db
    mysqli_close($connect);
    ?>


    <!-- TODO: this will probably need to change with user sessions -->
    <form>
        <p>
            <input type="submit" formaction="./search_recipe.html" value="Search Again!">
            <input type="submit" formaction="./index.html" value="Return Home">
        </p>
    </form>

</body>

</html>
