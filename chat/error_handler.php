<?php
set_error_handler('error_handler',E_ALL);
require_once('admin.php');
function error_handler($errNo,$errStr,$errFile,$errLine)
{
if(ob_get_length()) ob_clean();
$error_message = 'ERRNO:'.$errNo.chr(10).'TEXT:'.$errStr.chr(10).'LOCATION:'.$errFile.',line'.$errLine;
$query = 'INSERT INTO error_log(error,error_time) values("'.$error_message.'",NOW())';
$error = new mysqli(host,user,pw,db,port);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$result = $error->query($query);
$error->close();
exit;
}
?>
