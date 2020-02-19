<?php

ini_set('display_errors', 1); 
error_reporting(E_ALL);

// Run an SQL command
function run($sql) {
    try {

        // get json contents of login
        $jsonStr  = file_get_contents("/storage/ssd4/283/12575283/configuration/cybadmindb_login.json");
        $login    = json_decode($jsonStr, true);

        $servername = strval($login["servername"]);
        $username   = strval($login["username"]);
        $password   = strval($login["password"]);
        $database   = strval($login["database"]);

        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $a = $conn->exec($sql);
    } catch (PDOException $ex) {
        echo "Failed SQL execution: ".$ex->getMessage();
    }
}

// Get result of SQL Command
function getSQLResult($sql, $item) {
    // get json contents of login
    $jsonStr  = file_get_contents("/storage/ssd4/283/12575283/configuration/cybadmindb_login.json");
    $login    = json_decode($jsonStr, true);

    $servername = strval($login["servername"]);
    $username   = strval($login["username"]);
    $password   = strval($login["password"]);
    $database   = strval($login["database"]);

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $conn->close();
            return $row[$item];
        }
    } else {
        $conn->close();
        return null;
    }
}

function getSQLRow($sql) {
    // get json contents of login
    $jsonStr  = file_get_contents("/storage/ssd4/283/12575283/configuration/cybadmindb_login.json");
    $login    = json_decode($jsonStr, true);

    $servername = strval($login["servername"]);
    $username   = strval($login["username"]);
    $password   = strval($login["password"]);
    $database   = strval($login["database"]);

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $result = $conn->query($sql);

    $conn->close();
    return $result->fetch_assoc();
}

function recordCount($sql) {
    // get json contents of login
    $jsonStr  = file_get_contents("/storage/ssd4/283/12575283/configuration/cybadmindb_login.json");
    $login    = json_decode($jsonStr, true);

    $servername = strval($login["servername"]);
    $username   = strval($login["username"]);
    $password   = strval($login["password"]);
    $database   = strval($login["database"]);

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $result = $conn->query($sql);

    return is_object($result);
}

function getAllSQL($sql) {
    // get json contents of login
    $jsonStr  = file_get_contents("/storage/ssd4/283/12575283/configuration/cybadmindb_login.json");
    $login    = json_decode($jsonStr, true);

    $servername = strval($login["servername"]);
    $username   = strval($login["username"]);
    $password   = strval($login["password"]);
    $database   = strval($login["database"]);

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $result = $conn->query($sql);

    return $result;
}

?>