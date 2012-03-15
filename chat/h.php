<html>
<head>
<title>Welcome</title>
<link type="text/css" rel="stylesheet" href="chat.css" />
</head>
<body>
<div id = "on" style="left:20px;top:100px;position:absolute;background:#82a9d9;" >
<table id="users_list" border=0 cellspacing=0 cellpadding=0>
<tbody style="background:#82a9d9;">
<tr>
<td class="listLT"></td><td class="listTM"></td><td class="listRT"></td>
</tr>
<tr>
<td></td><td class="listcaption">
<div id="handleonline">Online Users
</div></td><td></td>
</tr>
<tr>
<td class="listML"></td><td VALIGN="top" class="listM">
<div id="online_people" style="visibility:visible;position:absolute;float: left;"><form name="online_users" TITLE="online users"><select size='25' id="online_people" style="width:140px;height:400px;font-size:18px;color:#82a9d9"  onmouseover="user_info(event.target.value);" onmouseout="hide_user_info(event.target.value);" onDblClick="open_chat(this.options[this.selectedIndex].innerHTML);">
</select></form>
</div></td><td class="listMR"></td>
</tr>
<tr>
<td class="listLB"></td><td class="listBM"></td><td class="listRB"></td>
</tr>
</tbody>
</table>
</div>
<script>
</script>
<?php
require('scripts.php');
?>
</body>
</html>
