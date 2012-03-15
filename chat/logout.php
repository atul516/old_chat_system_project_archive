<?php
require_once('events.class.php');
$logout = new events();
if(!isset($_GET['nick'])) {
$logout->redirect();
}
$nick=$logout->decrypt($_GET['nick']);
if(file_exists('online_users/' . $nick)) {
$user = $logout->find_user_name($nick);
$modify=$logout->msql->prepare("INSERT INTO modification set uniqid=?,nick=?,type=0,mod_time=NOW(),timestamp=?");
$modify->bind_param("ssi",$nick,$user,time());
$modify->execute();
$modify->close();
$fp = fopen('online_users/Global_Online_Modification', 'a');
fwrite($fp,'1');
fclose($fp);
if (file_exists('online_users/' . $nick))
unlink('online_users/'.$nick);
$del_user=$logout->msql->prepare("DELETE FROM online_users WHERE uniqid=?");
$del_user->bind_param("i",$nick);
$del_user->execute();
$del_user->close();
$msql->close();
$logout->uncertain($nick,0);
$logout->normal_headers(0);
echo "<h2>You have logged out successfully ....<a href=\"http://chat-desert_hawk.dotcloud.com\">Home Page</a></h2>";
flush();
ob_flush();
exit;
}
else {
$logout->redirect();
}
?>
