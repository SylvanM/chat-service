<?php

include_once("background_scripts/messages.php");
include_once("background_scripts/users.php");

// Constants



do_chat_API();

function do_chat_API() {
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

// any actions
$ACTION_CHECKLOGIN = "v";
$ACTION_CHECKADMIN = "a";

// these are required by all functions
$username = get("user");
$password = get("pass");

$adminname = get('adusr');
$adminpass = get('adpss');

// optional parameters, might not exist
$to_user   = get('to');
$from_user = get('from');

$message_id = get('mid');

$message   = get('msg');
$timestamp = get('ts');
    switch (get('f')) {
        case ($ACTION_CREATEUSR):
            createUser($username, $password);
            return;
        case ($ACTION_DELETEUSR):
            delete($username, $password);
            return;
        case ($ACTION_SENDMSG):
            echo "Sending message...\n";
            sendMessage($to_user, $username, $message, $password);
            return;
        case ($ACTION_REFRESH):
            // return to the client the messages to this user
            echo retrieveMessagesTo($username, $password);
            return;
        case ($ACTION_SEEFROM):
            // return to the client the messages to this user
            echo retrieveMessagesFrom($username, $password);
            return;
    
        case ($ACTION_SEEPENDING):
            echo retrieveAllPendingMessages($adminname, $adminpass);
            return;
        case ($ACTION_ACCEPTMSG):
            acceptMessage($message_id, $adminname, $adminpass);
            return;
        case ($ACTION_BLOCKMSG):
            blockMessage($message_id, $adminname, $adminpass);
            return;
        case ($ACTION_CHECKLOGIN):
            echo json_encode(verify($username, $password));
            return;
        case ($ACTION_CHECKADMIN):
            echo json_encode(verifyAdmin($adminname, $adminpass));
            return;
    }
}



function get($index) {
    return array_key_exists($index, $_GET) ? $_GET[$index] : "";
}

?>