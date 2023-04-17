<?php
require_once('events.class.php');
$connect = new events();
if (!(isset($_POST['from_user']) && isset($_POST['to_user']))) {
    $connect->bad_request();
}
$from_user = $connect->decrypt($_POST['from_user']);
if (file_exists('online_users/' . $from_user)) {
    $to_user = $connect->find_uniqid($_POST['to_user']);
    if (($to_user != null) && (file_exists('online_users/' . $to_user))) {
        $fh     = fopen('online_users/' . $to_user, 'a');
        $from_user = $connect->find_user_name($from_user);
        $pid = $connect->post_request();
        $w = 'c' . ' 0 ' . time() . ' ' . $from_user . ' ' . $pid;
        $ft = fopen('online_users/' . $to_user, 'a');
        flock($ft, LOCK_EX);
        fwrite($ft, $w);
        fwrite($ft, "\n");
        fclose($ft);
        ob_start();
        $connect->normal_headers(0);
        echo "1";
        flush();
        ob_flush();
    } else {
        $connect->user_offline();
    }
} else {
    $connect->bad_request();
}
?>
