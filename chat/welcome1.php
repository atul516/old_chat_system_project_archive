<div class="on">
<table style= "text-align:left;table-layout:fixed;" width="166" height="450" border= "0" cellpadding= "0" cellspacing= "0" > 
<tbody>
<tr> 
<td style="background:transparent;background-image:url(corner1.png);" width="8" height="30"><br> </td>
 <td style= "background-color:#8091d6;" width="150" height="30"><div id= "handleTopfrom_user"><div class="online_user_caption">Online Users</div></div><br></td>
 <td style="background:transparent;background-image:url(corner2.png);" width="8" height="30"><br> </td>
</tr>
<tr> 
<td width="8" height="401" style= "background-color:#6067cd;"><br> </td> 
<td width="150" height="401"><div class= "online_users_display" id= "from_user3"></div></td>
 <td width="8" height="401" style= "background-color:#6067cd;"><br> </td>
</tr>
<tr> 
<td width="8" height="19" style= "background-color:#8091d6;"><br> </td>
 <td width="150" height="19" style= "background-color:#8091d6;"></td> 
<td width="8" height="19" style= "background-color:#8091d6;"><br></td>
</tr>
</tbody>
</table>
</div>

<?php
require_once('sanitize.php');
$response = '<div class="welcome_div"><div class = "logo"><span>Welcome '.sanitize($user).'!</span></div>';
$logout ='<div class="logout"><a href="http://127.0.0.1/chat/logout?nick='.$id.'">Logout</a></div></div>';
echo ($response);
echo $logout;
?>
