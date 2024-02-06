<?php
include("database.php");
session_start();




?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>welcome to the website <?php print($_SESSION["username"]);?></h1>
        <?php print($_SESSION["user_id"])?>
        <form action="<?php $_SERVER["PHP_SELF"]?>" method="post">
            <button name="logout" > log out</button>
        </form>
    </body>
</html>

<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["logout"]))
    {
        session_destroy();
        header("Location: index.php");
    }
?>