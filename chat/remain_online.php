<?php
require_once('error_handler.php');
require_once('admin.php');
$msql = new mysqli(host,user,pw,db);
$user=$msql->real_escape_string($_POST['from_user']);
$query='UPDATE online_users SET last_online=NOW(),status="ONLINE" WHERE nick="'.$user.'"';
$result = $msql->query($query);
$msql->close();
?>