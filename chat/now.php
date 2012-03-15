<?php
require_once('error_handler.php');
require_once('admin.php');
function difference($nick)
{
$msql = new mysqli(host,user,pw,db);
$user=$nick;
$last_online='SELECT last_online FROM online_users WHERE nick="'.$user.'"';
$result=$msql->query($last_online);
date_default_timezone_set('Asia/Calcutta');
if($result->num_rows)
{
$row = $result->fetch_array(MYSQLI_ASSOC);
}
$msql->close();
$user_last_online = $row['last_online'];
$now=time();
$no=strtotime($user_last_online);
$n = $now - $no;
return $n;
}
?>