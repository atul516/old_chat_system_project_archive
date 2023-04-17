<?php
require_once('now.php');
function online_people() {
$msql = new mysqli(host,user,pw,db);
$query='SELECT nick FROM online_users';
$user=$msql->real_escape_string($_POST['nick']);
$result=$msql->query($query);
$num=1;
$option='<option value=';
$response=null;
if($result->num_rows)
{
while($row = $result->fetch_array(MYSQLI_ASSOC))
{
$user = $row['nick'];
$difference = difference($user);
if($difference < 3)
{
$response=$response.$option.$num.'>'.$user;
$num=$num+1;
}
}
}
$msql->close();
return $response;
}
?>
