<?php
require_once('events.class.php');
$connect = new events();
if (!(isset($_POST['from_user']) && isset($_POST['pid']) && isset($_POST['mode']) && isset($_POST['to_user']))) {
    $connect->bad_request();
}
if (!($_POST['mode'] == 1 || $_POST['mode'] == 0 || (strlen($pid) < 7))) {
    $connect->bad_request();
}
$mode = $_POST['mode'];
$pid = $_POST['pid'];
$from_user = $connect->decrypt($_POST['from_user']);
if (file_exists('online_users/' . $from_user)) {
    $to_user = $connect->find_uniqid($_POST['to_user']);
    if (($to_user != null) && (file_exists('online_users/' . $to_user))) {
if(!$connect->remove_pending_request($pid)) {
    $connect->bad_request();
}
        $fh     = fopen('online_users/' . $to_user, 'a');
        $user = $connect->find_user_name($from_user);
        if ($mode == 0){
            $w = 'c' . ' 2 ' . time() . ' ' . $user . ' ' . $pid;
        }
        else if ($mode == 1){
        $pid = $connect->accept_request($from_user,$to_user);
            $w = 'c' . ' 1 ' . time() . ' ' . $user . ' ' . $pid;
        }
        $ft = fopen('online_users/' . $to_user, 'a');
        flock($ft, LOCK_EX);
        fwrite($ft, $w);
        fwrite($ft, "\n");
        fclose($ft);
        ob_start();
        $connect->normal_headers(0);
        if ($mode == 0){
        echo "1";
        }
        else if ($mode == 1){
        echo $pid;
        }
        flush();
        ob_flush();
    } else {
        $connect->user_offline();
    }
} else {
    $connect->bad_request();
}
?>
