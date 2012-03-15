<?php
require_once('error_handler.php');
require_once('base.class.php');
$base = new base();
if (isset($_GET['uid'])) {
        $uid = $_GET['uid'];
        $uniqid = $base->decrypt($uid);
    if(file_exists('online_users/' . $uniqid)) {
            $base->normal_headers(0);
            require_once('welcome.php');
        }
else {
    $base->redirect();
}
}
else {
    $base->redirect();
}
?>
