<?php

include_once("users.php");

// sends a message, entering it into the "pending" database
// this is a LOW LEVEL function that does NOT check for authorizatrion. it should only ever be called when authorization has already been verified
function enterPendingMessage($to, $from, $message) {

    $timestamp = time();

    $id = hash("sha256", $to . $from . $message . $timestamp);

    $to = "$to";
    $from = "$from";
    $message = "$message";
    $timestamp = "$timestamp";
    $id = "$id";

    $sql = "INSERT into pending (to_user, from_user, message, timestamp, message_id) VALUES ('$to', '$from', '$message', '$timestamp', '$id')";

    run($sql);

}

// TOP LEVEL FUNCTIONS

// this is a HIGH LEVEL function that DOES CHECK for authorization
function sendMessage($to, $from, $message, $password) {

    // first verify the user
    if (!verify($from, $password))
        return false;

    enterPendingMessage($to, $from, $message);

}

// this is a HIGH LEVEL function which DOES check for authorization
function acceptMessage($identifier, $adminname, $adminpassword) {

    if (!verifyAdmin($adminname, $adminpassword))
        return false;
    
    $identifier = "$identifier";

    $retrieveMessageSQL = "SELECT * from pending where message_id = '$identifier'";
    echo $retrieveMessageSQL;

    // now move the pending message into the "messages" database

    // get information from the message
    $to         = getSQLResult($retrieveMessageSQL, "to_user"   );
    $from       = getSQLResult($retrieveMessageSQL, "from_user" );
    $message    = getSQLResult($retrieveMessageSQL, "message"   );
    $timestamp  = getSQLResult($retrieveMessageSQL, "timestamp" );

    $to         = "$to";
    $from       = "$from";
    $message    = "$message";
    $timestamp  = "$timestamp";

    $removePendingMessageSQL = "DELETE from pending where message_id = '$identifier'";
    run($removePendingMessageSQL);

    $addToMessagesSQL = "INSERT into messages (to_user, from_user, message, timestamp) VALUES ('$to', '$from', '$message', '$timestamp')";
    run($addToMessagesSQL);

}

function blockMessage($identifier, $adminname, $adminpassword) {

    if (!verifyAdmin($adminname, $adminpassword))
        return false;

    $identifier = "$identifier";

    $removePendingMessageSQL = "DELETE from pending where message_id = \"$identifier\"";
    
    run($removePendingMessageSQL);

}

// Recieving end of messages

// returns a mysqli object containing all messages to this user
// 
// this is a LOW LEVEL function that does not check for verification of a user
function retrieveMessagesTo($user, $password) {

    if (!verify($user, $password))
        return false;

    $user = "$user";

    $sql = "SELECT * FROM messages where to_user = \"$user\"";

    return json_encode(getAllSQL($sql));

}

function retrieveAllPendingMessages($adminname, $adminpassword) {
    if (!verifyAdmin($adminname, $adminpassword))
        return false;

    $sql = "SELECT * FROM `pending`";

    return json_encode(getAllSQL($sql));
}

// returns all messages from a certain user
function retrieveMessagesFrom($user, $password) {

    if (!verify($user, $password))
        return false;

    $user = "$user";

    $sql = "SELECT * FROM messages where from_user = \"$user\"";

    return json_encode(getAllSQL($sql));
}

?>