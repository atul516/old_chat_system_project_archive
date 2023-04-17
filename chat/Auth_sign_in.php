<?php
    require_once('error_handler.php');
    require_once('recaptchalib.php');
    require_once('base.class.php');
    $var        = new base();
function validate($str) {
    $privatekey = '6LdGS8oSAAAAAI79__n6NbJcwvl5rM4IKvFvQEWT';
    $resp       = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
    if (!$resp->is_valid) {
        echo "The reCAPTCHA solution was incorrect. Please try again.";
        return 0;
    } 
    if (!preg_match('/^[A-Za-z0-9_]+$/', $str)) {
        echo 'Nickname should only contain alphanumeric characters and the underscore';
        return 0;
    }
    if (strlen($str) > 15 || strlen($str) < 4) {
        echo "The nick's length should be more than 3 and less than 15";
        return 0;
    }
    return 1;
}
if (isset($_POST['nick']) && isset($_POST['aboutyou']) && isset($_POST['thingstotalk']) && isset($_POST["recaptcha_challenge_field"]) && isset($_POST["recaptcha_response_field"])) {
    if (!validate($_POST['nick'])) {
        flush();
        ob_flush();
        exit;
    }
    $user       = $_POST['nick'];
    $check_user = $var->msql->prepare("SELECT * FROM online_users WHERE nick=?");
    $check_user->bind_param("s", $user);
    $check_user->execute();
    if ($check_user->fetch()) {
        $error = 'The nick is not available.Try some other nick.';
        echo $error;
        flush();
        ob_flush();
        exit;
    }
    $check_user->close();
    $insert_user = $var->msql->prepare("INSERT INTO online_users(nick,first_logged_in,last_online) VALUES(?,NOW(),NOW())");
    $insert_user->bind_param("s", $user);
    $insert_user->execute();
    $aboutyou     = $_POST['aboutyou'];
    $thingstotalk = $_POST['thingstotalk'];
    $uniq = $var->find_uniqid($user);
    $insert_info  = $var->msql->prepare("INSERT INTO interests_info(uniqid,about,things_to_talk)VALUES(?,?,?)");
    $insert_info->bind_param("iss", $uniq,$aboutyou, $thingstotalk);
    $insert_info->execute();
    if ($insert_info->affected_rows <= 0 || $insert_user->affected_rows <= 0) {
        echo "Error occurred";
        flush();
        ob_flush();
        exit;
    }
    $insert_user->close();
    $insert_info->close();
    $fh   = fopen('online_users/' . $uniq, 'w');
    fclose($fh);
    $var->normal_headers(0);
    echo "/chat/chat.php?uid=" . urlencode($var->encrypt($uniq));
    flush();
    ob_flush();
} else {
    $var->bad_request();    
}
?>
