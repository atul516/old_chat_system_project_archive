<?php
require_once('admin.php');
class Chat
{
private $msql;

function __construct()
{
$this->msql = new mysqli(host,user,pw,db);
}
public function register_user($user)
{
$user=$this->msql->real_escape_string($user);
$query='INSERT INTO online_users SET nick='.$user;
$result = $this->msql->query($query);
}
public function __destruct()
{
$this->msql->close();
}
public function deletetable()
{
$query = 'TRUNCATE TABLE chat';
$result = $this->msql->query($query);
}
public function postmessage($from_user,$to_user,$message,$mid,$time)
{
$query = 'INSERT INTO chat(from,to,message,mid,time)'.
'VALUES ("' . $from_user. '" , "' .$to_user . '" , "' . $message .
'","' . $mid . '" , "' .$time. '")';
$result = $this->msql->query($query);
}
public function getmessage($id=0,$user)
{
$query =
'SELECT from_user,message,mid ' .
' FROM chat WHERE mid > ' . $id .
' ORDER BY mid';
$result = $this->msql->query($query);
}
?>