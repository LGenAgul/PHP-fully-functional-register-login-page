<?php
session_start();
include("database.php");
$status="";
$status2="";



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["submit"])) {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    if (!empty($email) && !empty($username) && !empty($password)) {
        if (isEmail($email)) {
            if (isRegistered($username, $email)) {
                echo "A user with this email or username already exists.";
            } else {
                if (!isComplex($password)) {
                    $status2="The password must have at least 8 characters,have at least 1 uppercase and lowercase letter,a number and a symbol.";
                } else {
                    // Successfully created an account, now we add the values to the database
                    // But first, we hash the password
                    $password = hash("sha256", $password);
                    
                    $_SESSION["username"]= $username;
                    $_SESSION["user_id"] = getuserID($username);
                  
    
                    register($username, $password, $email);
                    header("Location: index.php");
                }
            }
        }
        else
        {
            $status= "such an email doesn't exist";
        }
    } else {
        $status="Fill in all the fields.";
    }
    }
    
    
    function isComplex(string $password)
    {
    if (
        strlen($password) < 8 ||
        !preg_match("/[A-Z]/", $password) ||
        !preg_match("/[a-z]/", $password) ||
        !preg_match("/\d/", $password) ||
        !preg_match('/[^A-Za-z0-9]/', $password)
    ) {
        return false;
    }
    return true;
    }
    
    
    // email verification with reacher API
    // COMMENTED OUT BECAUSE THIS API COSTS MONEY
    function isEmail(string $email)
    {
    // $ar = array(
    //     'to_email'=>$email
    // );
    
    // $post_data = json_encode($ar);
    
    // $curl = curl_init("https://api.reacher.email/v0/check_email");
    // curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    // curl_setopt($curl, CURLINFO_HEADER_OUT,true);
    // curl_setopt($curl, CURLOPT_POST,true);
    // curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    // curl_setopt($curl, CURLOPT_HTTPHEADER,array(
    //     'content-Type: application/json',
    //     'authorization: 072c16ee-c2b5-11ee-955c-bd7ca2fb9d56'
    
    // ));
    
    // $response = curl_exec($curl);
    // echo "$response";
    // $decoded = json_decode($response,true);
    
    // if($decoded['is_reachable']=='safe')
    // {
    return true;
    // }
    
    // return false;
    }
    
   
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
<title>Document</title>
</head>
<body >

<div class="container">

<H1>Register</H1>


    <form action="<?php $_SERVER['PHP_SELF']?>" method="POST">

        <div class="coolinput">
        <label for="input" class="text">Email:</label>
        <input type="text" placeholder="email address..." name="email" class="input">
    </div>

       
    <div class="coolinput">
        <label for="input" class="text">Username:</label>
        <input type="text" placeholder="username..." name="username" class="input">
    </div>

    <div class="coolinput">
        <label for="input" class="text">Password:</label>
        <input type="text" placeholder="password..." name="password" class="input">
    </div>
    <br>
 <div class="buttons">
        <button name="submit">Register</button>
        <button><a href="./index.php">login</a></button>
    </div>
    <h2 style="margin-left: 15%;"><?php echo $status ?></h2>
    <h4 style="text-align:center;"><?php echo $status2 ?></h4>
</div>

    </form>
</div>
</div>



</body>
</html>


<?php



?>