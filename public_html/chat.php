<?php

include_once("background_scripts/messages.php");
include_once("background_scripts/users.php");

// Constants

// regular actions
$ACTION_SENDMSG   = "sndmsg";

$ACTION_CREATEUSR = "cusr";
$ACTION_DELETEUSR = "dusr";

$ACTION_REFRESH   = "rfsh";
$ACTION_SEEFROM   = "getf";

// admin actions
$ACTION_ACCEPTMSG  = "acptmsg";
$ACTION_BLOCKMSG   = "blckmsg";
$ACTION_SEEPENDING = "seepndg";

// these are required by all functions
$username = $_GET['user'];
$password = $_GET['pass'];

$adminname = $_GET['adusr'];
$adminpass = $_GET['adpss'];

// optional parameters, might not exist
$to_user   = $_GET['to'];
$from_user = $_GET['from'];

$message   = $_GET['msg'];
$timestamp = $_GET['ts'];

switch ($_GET['f']) {
    case ($ACTION_CREATEUSR):
        echo "Creating user";
        createUser($username, $password);
    case ($ACTION_DELETEUSR):
        delete($username, $password);
    case ($ACTION_SENDMSG):
        sendMessage($to_user, $username, $message, $password);
    case ($ACTION_REFRESH):
        // return to the client the messages to this user
        echo retrieveMessagesTo($username, $password);
    case ($ACTION_SEEFROM):
        // return to the client the messages to this user
        echo retrieveMessagesFrom($username, $password);

    case ($ACTION_SEEPENDING):
        echo retrieveAllPendingMessages($adminname, $adminpass);
    case ($ACTION_ACCEPTMSG):
        acceptMessage($to_user, $from_user, $message, $timestamp, $adminname, $adminpass);
    case ($ACTION_BLOCKMSG):
        blockMessage($to_user, $from_user, $message, $timestamp, $adminname, $adminpass);
}

?>