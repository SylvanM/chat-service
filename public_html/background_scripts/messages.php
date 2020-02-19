<?php

include_once("users.php");

// sends a message, entering it into the "pending" database
// this is a LOW LEVEL function that does NOT check for authorizatrion. it should only ever be called when authorization has already been verified
function enterPendingMessage($to, $from, $message) {

    $timestamp = time();

    $sql = "INSERT into pending (to_user, from_user, message, timestamp) VALUES ('$to', '$from', '$message', '$timestamp')";

    run($sql);

}

// TOP LEVEL FUNCTIONS

// this is a HIGH LEVEL function that DOES CHECK for authorization
function sendMessage($to, $from, $message, $password) {

    // first verify the user
    if (!verify($from, $password))
        return false;

    enterPendingMessage($to, $fro, $message);

}

// this is a HIGH LEVEL function which DOES check for authorization
function acceptMessage($to, $from, $message, $timestamp, $adminname, $adminpassword) {

    if (!verifyAdmin($adminname, $adminpassword))
        return false;

    $retrieveMessageSQL = "SELECT from pending where to_user = '$to' and from_user = '$from' and message = '$message' and timestamp = '$timestamp'";

    $retrievedMessage = getSQLRow($retrieveMessageSQL);

    // now move the pending message into the "messages" database

    $removePendingMessageSQL = "DELETE from pending where to_user = '$to' and from_user = '$from' and message = '$message' and timestamp = '$timestamp'";
    run($removePendingMessageSQL);

    $addToMessagesSQL = "INSERT into messages (to_user, from_user, message, timestamp) VALUES ('$to', '$from', '$message', '$timestamp')";
    run($addToMessagesSQL);

}

function blockMessage($to, $from, $message, $timestamp, $adminname, $adminpassword) {

    if (!verifyAdmin($adminname, $adminpassword))
        return false;

    $removePendingMessageSQL = "DELETE from pending where to_user = '$to' and from_user = '$from' and message = '$message' and timestamp = '$timestamp'";
    run($removePendingMessageSQL);

}

// Recieving end of messages

// returns a mysqli object containing all messages to this user
// 
// this is a LOW LEVEL function that does not check for verification of a user
function retrieveMessagesTo($user, $password) {

    if (!verify($user, $password))
        return false;

    $sql = "SELECT from messages where to_user = '$user'";

    return json_encode(getAllSQL($sql));

}

function retrieveAllPendingMessages($adminname, $adminpassword) {
    if (!verifyAdmin($adminname, $adminpassword))
        return false;

    $sql = "SELECT * from pending";

    return json_encode(getAllSQL($sql));
}

// returns all messages from a certain user
function retrieveMessagesFrom($user, $password) {

    if (!verify($user, $password))
        return false;

    $sql = "SELECT from messages where from_user = '$user'";

    return json_encode(getAllSQL($sql));
}

?>