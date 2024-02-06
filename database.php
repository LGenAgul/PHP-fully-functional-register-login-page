<?php
function connectDatabase() 
{
// Create connection
$servername = "localhost";
$username = "root";
$password = "root"; 
$database = "test";
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
return $conn;
}

function register($username,$hashed_password,$email)
{
    $conn = connectDatabase();

    $statement = $conn->prepare("INSERT INTO users (username, email, hashed_password) VALUES (?, ?, ?)");
    $statement->bind_param("sss", $username, $email, $hashed_password);

    // Execute the statement
    $result = $statement->execute();

    // Close the statement and connection
    $statement->close();
    $conn->close();

    return $result;
}



function isRegistered($username, $email) {
    $conn = connectDatabase();

    // Check if username or email exists
    $statement = $conn->prepare("SELECT username, email FROM users WHERE username = ? OR email = ?");
    $statement->bind_param("ss", $username, $email);
    $statement->execute();
    $statement->store_result();
    $result = $statement->num_rows > 0;
    $statement->close();

    $conn->close();

    return $result;
}

function login($username, $hashed_password)
{
   
    $conn = connectDatabase();

    $statement = $conn->prepare("SELECT * FROM users 
    WHERE( username = ? 
    OR email = ?)
    AND hashed_password= ? "
    );

    $statement->bind_param("sss", $username, $username,$hashed_password);
    $statement->execute();
    $statement->store_result();
    $result = $statement->num_rows > 0;
    $statement->close();

    return $result;
}
    function getuserID(string $username)
    {
        $conn = connectDatabase();
    
        $statement = $conn->prepare("SELECT ID FROM users WHERE username = ? OR email = ?");
    
        $statement->bind_param("ss", $username,$username);
        $statement->execute();
        $statement->store_result();
    
        if ($statement->num_rows > 0) {
            $statement->bind_result($userID);
            $statement->fetch();
            $statement->close();
    
            return $userID;
        } else {
            $statement->close();
            return null; 
        }
    }
    



function getuserName(string $username)
{
    $conn = connectDatabase();

    $statement = $conn->prepare("SELECT username FROM users WHERE username = ? OR email = ?");

    $statement->bind_param("ss", $username,$username);
    $statement->execute();
    $statement->store_result();

    $statement->bind_result($username);

    if ($statement->fetch()) {
        // Fetch was successful, return the username
        $statement->close();
        return $username;
    } else {
        // No matching record found
        $statement->close();
        return null;
    }
}
?>
