<html>
<head>
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="01 Jan 1970 00:00:00 GMT">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>Welcome</title>
<link type="text/css" rel="stylesheet" href="chat.css" />
<script type="text/javascript">
function getnick() {
var name="<?php
echo "atul";
?>";
return name;
}
</script>
</head>
<body>
<div id="contents" class="contents">
</div>
<div class="welcome">
<div style="background:transparent;background-image:url(corner1.png);width:8px;position:relative;height:30px;float:left;"></div>
<div class="welcome_logo">
<?php
require_once('sanitize.php');
$response = '<div class="welcome_div">Welcome '.sanitize("atul").'!</div>';
$logout ='<div class="logout"><a href="http://127.0.0.1/chat/logout?nick='.'atul'.'">Logout</a></div>';
echo ($response);
echo $logout;
?>
</div>
 <div style="background:transparent;background-image:url(corner2.png);width:8px;position:relative; height:30px;float:left;"></div>
</div>
<?php
require_once('select.php');
require_once('scripts.php');;
?>
<div id="sound">
</div>
</body>
</html>
