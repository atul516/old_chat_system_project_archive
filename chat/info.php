<?php
require_once('events.class.php');
$execute = new events();
if (!(isset($_POST['from_user']) && isset($_POST['to_user']))) {
$execute->bad_request();
}
$from_user = $execute->decrypt($_POST['from_user']);
if (file_exists('online_users/' . $from_user)) {
    $to_user = $_POST['to_user'];
    $uniqid = $execute->find_uniqid($to_user);
    if (($to_user != null) && (file_exists('online_users/' . $uniqid))) {
        $result = $execute->info($to_user,$uniqid);
        $response ='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $response = $response.'<response>'.$result.'</response>';
        ob_start();
        $execute->normal_headers(1);
        echo $response;
       flush();
       ob_flush();
    } else {
        $execute->normal_headers(1);
        echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response><user_offline></user_offline></response>';
    }
} else {
    $execute->bad_request();
}
?>
