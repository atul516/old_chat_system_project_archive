<?php
require_once('events.class.php');
$send_events = new events();
if (!(isset($_POST['from_user']) && isset($_POST['pid']) && isset($_POST['messag']))) {
    $send_events->bad_request();
}
$pid = $_POST['pid'];
if (strlen($pid) < 10) {
    $send_events->bad_request();
}
$message = $_POST['messag'];
if (strlen($message) > 249) {
    ob_start();
    $send_events->normal_headers(0);
    echo "message too long!";
    flush();
    ob_flush();
    exit;
}
$from_user = $send_events->decrypt($_POST['from_user']);
if (file_exists('online_users/' . $from_user)) {
    $to_user = $send_events->is_chat_pair($from_user,$send_events->decrypt($pid));
    if (($to_user != null) && (file_exists('online_users/' . $to_user))) {
        $result = $send_events->postmessage($pid, $message);
        $from_user = $send_events->find_user_name($from_user);
        $fh     = fopen('online_users/' . $to_user, 'a');
        flock($fh, LOCK_EX);
        $w = 'm' . ' ' . $pid . ' ' . $from_user;
        fwrite($fh, $w);
        fwrite($fh, "\n");
        fclose($fh);
        ob_start();
        $send_events->normal_headers(0);
        echo "1";
        flush();
        ob_flush();
    } else {
        $send_events->user_offline();
    }
} else {
    $send_events->bad_request();
}
?>
