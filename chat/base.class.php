<?php
/*
Base script
Created by : Atul Pratap Singh
Project : Online Chat System
*/
require_once('admin.php');
require_once('error_handler.php');
require_once('encryption.class.php');
class base
{
    public $msql;
    function __construct()
    {
        $this->msql = new mysqli(host, user, pw, db,port);
    if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
    }
    function __destruct()
    {
        $this->msql->close();
    }
    function difference($nick)
    {
        $last_online = $this->msql->prepare("SELECT last_online FROM online_users WHERE uniqid=?");
        $last_online->bind_param("i", $nick);
        $last_online->execute();
        $last_online->bind_result($no);
        $last_online->fetch();
        $last_online->close();
        $now = time();
        return $now - $no;
    }
    function sanitize($string)
    {
        $entity[0]   = '/\&/';
        $entity[1]   = '/</';
        $entity[2]   = "/>/";
        $entity[3]   = '/\n/';
        $entity[4]   = '/"/';
        $entity[5]   = "/'/";
        $entity[6]   = "/%/";
        $entity[7]   = '/\(/';
        $entity[8]   = '/\)/';
        $entity[9]   = '/\+/';
        $entity[10]  = '/-/';
        $entity[11]  = '/\//';
        $escaped[0]  = '&amp;';
        $escaped[1]  = '&lt;';
        $escaped[2]  = '&gt;';
        $escaped[3]  = '<br>';
        $escaped[4]  = '&quot;';
        $escaped[5]  = '&#x27;';
        $escaped[6]  = '&#37;';
        $escaped[7]  = '&#40;';
        $escaped[8]  = '&#41;';
        $escaped[9]  = '&#43;';
        $escaped[10] = '&#45;';
        $escaped[11] = '&#x2F;';
        return preg_replace($entity, $escaped, $string);
    }
    function find_user_name($id)
    {
        $find = $this->msql->prepare("SELECT nick FROM online_users WHERE uniqid=?");
        $find->bind_param("i", $id);
        $find->execute();
        $find->bind_result($a);
        $find->fetch();
        $find->close();
        return $a;
    }
    function find_uniqid($nick)
    {
        $find = $this->msql->prepare("SELECT uniqid FROM online_users WHERE nick=?");
        $find->bind_param("s", $nick);
        $find->execute();
        $find->bind_result($a);
        $find->fetch();
        $find->close();
        if ($a != null)
            return $a;
        else
            return null;
    }
    function encrypt($str)
    {
        $k         = 'Qgh%*fd#}|g_!~nkn,';
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($k), $str, MCRYPT_MODE_CBC, md5(md5($k))));
        return $encrypted;
    }
    function decrypt($encrypted)
    {
        $k         = 'Qgh%*fd#}|g_!~nkn,';
        $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($k), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($k))), "\0");
        return $decrypted;
    }
    function normal_headers($xml)
    {
      if($xml == 1)
        header('Content-Type: text/xml');
      else
        header('Content-Type: text/html');
        header("Expires: Wed, 16 May 1991 09:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }
    function bad_request()
    {
        ob_start();
        header("HTTP/1.1 400 Not Found");
        header("Status: 400 Not Found");
        echo "Bad Request. Try the proper way !";
        flush();
        ob_flush();
        exit;
    }
    function user_offline()
    {
        ob_start();
        $this->normal_headers(0);
        echo "user offline!";
        flush();
        ob_flush();
        exit;
    }
    function redirect()
    {
        ob_start();
        header('Location: http://chat-desert_hawk.dotcloud.com/');
        flush();
        ob_flush();
        exit;
    }
    function getkey()
    {
         $pass = 'Hello Stranger';
         $salt = 'Strangers Become Friends';
         $cryptastic = new cryptastic();
         $key = $cryptastic->pbkdf2($pass, $salt, 1000, 32);
    }
    function rand_str()
    {
        $length       = 6;
        $chars        = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        $chars_length = (strlen($chars) - 1);
        $string       = $chars{rand(0, $chars_length)};
        for ($i = 1; $i < $length; $i = strlen($string)) {
            $r = $chars{rand(0, $chars_length)};
            if ($r != $string{$i - 1})
                $string .= $r;
        }
        return $string;
    }
}
?>
