<?php

include_once("sql.php");

// Verifies a user
//
// Returns TRUE if the username and password are correct
function verify($username, $password) {

    $sql = "SELECT * FROM users WHERE username = '$username'";
    
    $savedHash = getSQLResult($sql, "checkpass");

    $hash = hash("sha256", $password);

    return $hash == $savedHash;
    
}

function userExists($user) {
    $sql = "SELECT from users where username = '$user'";
    return recordCount($sql) == 1;
}

// Creates a user
function createUser($username, $password) {
    $check = hash("sha256", $password);

    $sql = "INSERT INTO users (username, checkpass, is_admin) VALUES ('$username', '$check', 0)";

    run($sql);

}

function delete($username, $password) {

    // gotta make sure they are a real person
    if (!verify($username, $password))
        return false;

    $sql = "DELETE FROM users WHERE username = '$username'";

    run($sql);

}

function verifyAdmin($username, $password) {
    
    if (!verify($username, $password))
        return false;

    $sql = "SELECT from users where username = '$username'";
    return getSQLResult($sql, "is_admin");

}

?>