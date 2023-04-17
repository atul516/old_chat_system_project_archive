<?php
require_once('error_handler.php');
require_once('admin.php');
function validate($str) {
    return preg_match('/^[A-Za-z0-9_]+$/',$str);
}
if (isset($_POST['nick']) && isset($_POST['aboutyou']) && isset($_POST['interests']) && isset($_POST['thingstotalk'])) {
    if(strlen($_POST['nick'])>15) {
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        echo 'Nickname should not be longer than 15 characters.&nbsp&nbsp&nbsp&nbsp Please use a different nickname. &nbsp <a href="http://127.0.0.1">back</a>';
        flush();
        ob_flush();
    }
    else if(!validate($_POST['nick'])) {
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        echo 'Nickname should only contain alphanumeric characters and the underscore.&nbsp&nbsp&nbsp&nbsp Please use a different nickname. &nbsp <a href="http://127.0.0.1">back</a>';
        flush();
        ob_flush();
    }
    else {
        $msql = new mysqli(host,user,pw,db);
        $user=$_POST['nick'];
        $check_user=$msql->prepare("SELECT * FROM online_users WHERE nick=?");
        $check_user->bind_param("s",$user);
        $check_user->execute();
        if(!$check_user->fetch()) {
            $check_user->close();
            $online = "ONLINE";
            $uniq = rand ();
            $insert_user=$msql->prepare("INSERT INTO online_users(uniqid,nick,first_logged_in,last_online,status) VALUES(?,?,NOW(),NOW(),?)");
            $insert_user->bind_param("sss",$uniq,$user,$online);
            $insert_user->execute();
            $insert_user->close();
            $id = $uniq;
            $aboutyou=$_POST['aboutyou'];
            $interests=$_POST['interests'];
            $thingstotalk=$_POST['thingstotalk'];
            $check=$msql->prepare("SELECT * FROM interests_info WHERE nick=?");
            $check->bind_param("s",$id);
            $check->execute();
            $check->bind_result($a,$b,$c,$d,$e);
            if($row = $check->fetch()) {
                $check->close();
                $update=$msql->prepare("UPDATE interests_info SET about=?,interests=?,things_to_talk=? WHERE nick=?");
                $update->bind_param("ssss",$aboutyou,$interests,$thingstotalk,$user);
                $update->execute();
                $update->close();
            }
            else {
                $insert=$msql->prepare("INSERT INTO interests_info(nick,about,interests,things_to_talk)VALUES(?,?,?,?)");
                $insert->bind_param("ssss",$id,$aboutyou,$interests,$thingstotalk);
                $insert->execute();
                $insert->close();
            }
            $msql->close();
            header("Cache-Control: no-cache, must-revalidate");
            header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
            require_once('welcome.php');
        }
        else {
            $error = '<h2>The nick is not available!&nbsp&nbsp&nbsp&nbsp Please use a different nickname &nbsp <br> <a href="http://127.0.0.1">back</a></h2> ';
            echo $error;
        }
    }
}
else {
    header("HTTP/1.1 400 Not Found");
    header("Status: 400 Not Found");
    echo "Bad Request. Try the proper way!";
    flush();
    ob_flush();
}
?>