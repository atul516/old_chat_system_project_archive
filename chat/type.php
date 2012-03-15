<?php
require_once('events.class.php');
$type_events = new events();
if (!(isset($_POST['from_user']) && isset($_POST['typing']) && isset($_POST['pid']))) {
    $type_events->bad_request();
}
$pid = $_POST['pid'];
if (strlen($pid) < 10) {
    $send_events->bad_request();
}
if (!($_POST['typing'] == 1 || $_POST['typing'] == 0)) {
    $type_events->bad_request();
}
$from_user = $type_events->decrypt($_POST['from_user']);
$typing    = $_POST['typing'];
if (file_exists('online_users/' . $from_user)) {
    $to_user = $type_events->is_chat_pair($from_user,$type_events->decrypt($pid));
    if (($to_user != null) && (file_exists('online_users/' . $to_user))) {
        $from_user = $type_events->find_user_name($from_user);
        if ($typing == 1)
            $w = 't' . ' 1 ' . time() . ' ' . $from_user;
        if ($typing == 0)
            $w = 't' . ' 0 ' . time() . ' ' . $from_user;
        $ft = fopen('online_users/' . $to_user, 'a');
        flock($ft, LOCK_EX);
        fwrite($ft, $w);
        fwrite($ft, "\n");
        fclose($ft);
        ob_start();
        $type_events->normal_headers(0);
        echo "1";
        flush();
        ob_flush();
    } else {
        $type_events->user_offline();
    }
} else {
    $type_events->bad_request();
}
?>
