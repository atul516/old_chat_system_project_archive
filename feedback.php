<?php
function validate($email) {
if(!filter_var($email, FILTER_VALIDATE_EMAIL))
  return false;
else
  return true;;
}
if (isset($_POST['email']) && isset($_POST['feedback_text'])) {
    $email = $_POST['email'];
    $text = $_POST['feedback_text'];
    if(strlen($_POST['email']) != 0 && strcmp(($_POST['email'] ),"Your Email Address") != 0){
    if(strlen($_POST['email'])>60 || (strlen($_POST['email'])<5)) {
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        echo "The E-mail length should be more than 4 and less than 60";
        flush();
        ob_flush();
    }
    else if(!validate($_POST['email'])) {
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        echo 'Illegal E-mail Address';
        flush();
        ob_flush();
    }
    else
    require_once ('post_feed.php');
    }
    else if(strlen($_POST['feedback_text'])>200 || strlen($_POST['feedback_text']) == 0) {
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        echo "The feedback text length should be more than 0 and less than 200";
        flush();
        ob_flush();
    }
    else
    require_once ('post_feed.php');
}
else {
    header("HTTP/1.1 400 Not Found");
    header("Status: 400 Not Found");
    echo "Bad Request. Try the proper way!";
    flush();
    ob_flush();
}
?>
