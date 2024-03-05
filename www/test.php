<!DOCTYPE html>
<html lang="en">

<head>
    <title>TEST DB INFORMATION</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

<?php

    // db variables
    $server = "db";
    //$port = "3306";
    $user = "admin";
    //$user = "root";
    $pw = "pwd";
    //$pw = "";
    $db = "rc";

    // local vars
    $id = "";
    $title = "";
    $body = "";

    // open connection with db
    $connect = mysqli_connect($server, $user, $pw, $db) or die('Could not connect ot the databse server' . mysqli_connect_error());
    //$connect = mysqli_connect($server, $user, $pw, $db, $port);

    $query = "SELECT * FROM test;";

    $stmt = "";

    if ($stmt = $connect->prepare($query)) {
        $stmt->execute();
        $stmt->bind_result($id, $title, $body);
    }
    
    while ($stmt->fetch()) {
        print("<h1>$title</h1>");
        print("<h2>ID number: $id</h2>");
        print("<p>$body</p>");
    }

    // close statement
    $stmt->close();

    // close connection with db
    mysqli_close($connect);
?>


    <form action="./index.html" method="post">
        <input type="submit" value="Click here to return!">
    </form>
</body>

</html>
