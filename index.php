<?php
include("database.php");
ini_set("session.use_only_cookies",1);
ini_set("session.use_strict_mode",1);
session_set_cookie_params([
    "lifetime"=> 1800,
    "domain"=> "localhost",
    "path"=> "/",
    "secure"=> true,
    "httponly"=> true
]);

session_start();


if(isset($_SESSION["last_regeneration"])){

    session_regenerate_id(true);
    $_SESSION["last_regeneration"] = time();
}
else
{
    $interval = 60 * 30; 
    if (time() - $_SESSION["last_regeneration"] >= $interval)
    {
        session_regenerate_id(true);
        $_SESSION["last_regeneration"] = time();
    }
}


function isSessionExpired() {
    $timeout = 60 * 30; // Set timeout to 30 minutes (adjust as needed)

    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
        // Session has expired, destroy the session
        session_unset();
        session_destroy();
        return true;
    }


    
    // Update last activity timestamp
    $_SESSION['last_activity'] = time();
    return false;
}

$status="";

// checking for session varaibles
if (isset($_SESSION["username"]) && isset($_SESSION["user_id"])) {

    if (isSessionExpired())
    {
        // Redirect to login if session has expired
        header("Location: index.php");
        exit();
    }
    // If the session variables are set, redirect to the home page
    header("Location: home.php");
    exit(); 

}




if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["submit"])) 
{
    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    // sanitize the input
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


   if(!empty($_POST["username"]) && !empty($_POST["password"]))
   {
    $password = hash("sha256", $password);
    if(login($username, $password))
    {
        echo "log in succesful!";
        $username = getuserName($username);
        echo $username;
        $_SESSION["username"]= $username;
        $_SESSION["user_id"] = getuserID($username);
        header("Location: home.php");
        exit();
    }
   }
   else
   {
    $status="Please fill in  all the fields";
   }
   $_SESSION['last_activity'] = time();
}
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

    <div class="container">
    <H1>Login</H1>
        <form action="<?php $_SERVER['PHP_SELF']?>" method="post">
    
        <div class="coolinput">
        <label for="input" class="text">Email or Username:</label>
        <input type="text" placeholder="email/username..." name="username" class="input">
    </div>

        
    <div class="coolinput">
        <label for="input" class="text">Password:</label>
        <input type="text" placeholder="password..." name="password" class="input">
    </div>

<br>

        <div class="buttons">
            <button name = "submit">log in</button>
            <button><a href="./register.php">register</a></button>
            </div>

          <h3 style="margin-left: 10%;"><?php echo $status ?></h3>


        </form>
        </div>
    </body>
</html>
