<?php
require_once('error_handler.php');
require_once('admin.php');
$msql = new mysqli(host,user,pw,db);
$query1='CREATE TRIGGER modify AFTER UPDATE ON online_users FOR EACH ROW BEGIN IF OLD.status="OFFLINE" AND NEW.status="ONLINE" THEN INSERT INTO modification set by_user=NEW.nick,type=1,mod_time=NOW(); ELSE IF OLD.status="ONLINE" AND NEW.status="OFFLINE" THEN INSERT INTO modification set by_user=NEW.nick,type=0,mod_time=NOW(); END IF; END; ';
$result = $msql->query($query1);
if($result)
echo "hiii";
else
echo $msql->error;
$msql->close();
?>