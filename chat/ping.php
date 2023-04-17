<?php
require_once('base.class.php');
$ping = new base();
if (!isset($_POST['from_user'])) {
    $ping->bad_request();
}
$from_user = $ping->decrypt($_POST['from_user']);
if (file_exists('online_users/' . $from_user)) {
        $ping->normal_headers(0);
        echo "1";
        flush();
        ob_flush();
} else {
    $ping->bad_request();
}
?>
