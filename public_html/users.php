<?php

$function = $_GET['f'];

$username = $_GET['user'];
$password = $_GET['pass'];

if ($function == "c") {
    createUser();
} 
else if ($function == "v") {
    verify();
}
else if ($function == "d") {
    delete();
}

include_once("sql.php");

// Verifies a user
//
// Returns TRUE if the username and password are correct
function verify() {

    $sql = "SELECT * FROM users WHERE username = '$username'";
    
    $savedHash = getSQLResult($sql, "checkpass");

    $hash = hash("sha256", $password);

    return $hash == $savedHash;
    
}

// Creates a user
function createUser() {

    $check = hash("sha256", $password);

    $sql = "INSERT INTO users (username, checkpass) VALUES ('$username', '$check')";

    run($sql);

}

function delete() {

    // gotta make sure they are a real person
    if (!verify())
        return false;

    $sql = "DELETE FROM users WHERE username = '$username'";

    run($sql);

}

?>