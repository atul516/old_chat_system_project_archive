<?php
require_once('admin.php');
require_once('error_handler.php');
            $msql = new mysqli(host,user,pw,db);
            $lag = $msql->query('SELECT NOW()-3');
            $row1=$lag->fetch_row();
            $lag1 = 'SELECT by_user FROM uncertain WHERE finish_time<'.$row1[0];
            $uncert=$msql->query($lag1);
while($row=$uncert->fetch_row()) {
  $off = $row[0];
        $upd=$msql->prepare("INSERT INTO modification(by_user,type,mod_time) values(?,0,NOW())");
        $upd->bind_param("s",$off);
        $upd->execute();
        $upd->close();
        $upd=$msql->prepare("DELETE FROM online_users WHERE nick=?");
        $upd->bind_param("s",$off);
        $upd->execute();
        $upd->close();
        $upd=$msql->prepare("DELETE FROM uncertain WHERE by_user=?");
        $upd->bind_param("s",$off);
        $upd->execute();
        $upd->close();
            }
?>
