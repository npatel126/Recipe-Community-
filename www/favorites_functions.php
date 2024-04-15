<?php
function check_favorite($connect, $u_id, $r_id)
{
    $is_favorite = false;
    $recipe_id = null;

    $query = "SELECT favorites.favorite_id FROM favorites JOIN favorites_recipes ON favorites.favorite_id = favorites_recipes.favorite_id JOIN recipes ON favorites_recipes.recipe_id = recipes.recipe_id WHERE recipes.recipe_id = ? AND favorites.owner_id = ?";
    
    $stmt = mysqli_prepare($connect, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $r_id, $u_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $is_favorite = true;
        }
        
        mysqli_stmt_close($stmt);
    }
    
    return $is_favorite;
}

function add_favorite($connect, $u_id, $r_id)
{
    $query = "INSERT INTO favorites (owner_id) VALUES (?)";
    $stmt = mysqli_prepare($connect, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $u_id);
        mysqli_stmt_execute($stmt);
        
        $fav_id = mysqli_insert_id($connect);
        
        mysqli_stmt_close($stmt);
        
        $query = "INSERT INTO favorites_recipes (favorite_id, recipe_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($connect, $query);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ii", $fav_id, $r_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}

function remove_favorite($connect, $u_id, $r_id)
{
    $query = "DELETE favorites_recipes FROM favorites_recipes JOIN favorites ON favorites_recipes.favorite_id = favorites.favorite_id WHERE favorites.owner_id = ? AND favorites_recipes.recipe_id = ?";
    $stmt = mysqli_prepare($connect, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $u_id, $r_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}
?>
