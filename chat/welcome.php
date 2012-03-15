<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="16 May 1991 09:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<script type="text/javascript">
function getnick() {
var name="<?php
echo $uid;
?>";
return name;
}
</script>
<title>Welcome</title>
<link type="text/css" rel="stylesheet" href="chat.css" />
</head>
<body>
<div id="error_note_div"></div>
<?php
require_once('select.php');
require_once('scripts.php');
?>
<div id="box">
</div>
<div id="toolbar">
<div id="toolbar_div">
<div id="toolbar_contents">
<div id="toolbar_logo"></div>
<div class="notif"><div style="position:relative;left:5px;width:auto;margin-right:8px;">
<?php
require_once('sanitize.php');
$response = 'Welcome '.sanitize($base->find_user_name($uniqid)).'!';
echo ($response);
?>
</div></div>
<div class="notif" id="notif" onClick = "displayNotification();">Notifications<div id="new">New</div></div>
<div id="notifications">
<div class="notification">Here are some Notifications</div>
</div>
<div id="min_users">
</div>
<?php
$logout ='<a class="notif" id="logout" href="http://chat-desert_hawk.dotcloud.com/chat/logout?nick='.urlencode($uid).'">&nbspSign Out</a>';
echo $logout;
?>
</div>
</div>
</div>
</body>
</html>
