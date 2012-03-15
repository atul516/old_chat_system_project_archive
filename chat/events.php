<?php
    set_time_limit(800);
    ignore_user_abort(false);
    require_once('base.class.php');
    $base = new base();
if (isset($_POST['connect']) && isset($_POST['from_user'])) {
    $uid = $_POST['from_user'];
    $uniqid = $base->decrypt($uid);
    $connect   = $_POST['connect'];
    if (file_exists('online_users/' . $uniqid)) {
        $cycle = 0;
        $rem   = 0;
        $up    = array();
        require_once('events.class.php');
        $event = new events();
        if($connect == 0)
            $event->mark_modif($uniqid);
        $event->remain_online($uniqid, 0);
        $event->uncertain($uniqid, 0);
        $base->normal_headers(1);
        if ($connect == 0) {
            $result   = $event->online_people($uniqid, $connect);
            $connect  = time();
            $event->remain_online($uniqid, 1);
            $response = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
            $response = $response . '<response>';
            $response = $response . $result;
            echo $response . '<connect>' . $connect . '</connect></response>';
            flush();
            ob_flush();
        } else {
            while (1) {
                usleep(rand(800000,1500000));
                ob_start();
                if ($cycle == 25) {
                    $connect = time();
                    $event->uncertain($uniqid, 1);
                    echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . '<response><connect>' . $connect . '</connect><check_users>' . $event->check_users($uniqid) . '</check_users></response>';
                    flush();
                    ob_flush();
                    break;
                }
                $cycle = $cycle + 1;
                clearstatcache();
                if (file_exists('online_users/' . $uniqid))
                     $file_size = filesize('online_users/' . $uniqid);
                else break;
                clearstatcache();
                $global_modified = filemtime('online_users/Global_Online_Modification');
                if ($file_size !=0 || $global_modified >= $connect) {
                    $response = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>';
                    if ($global_modified >= $connect) {
                        $event->prepare_online_people();
                        $result2  = $event->online_people($uniqid, $connect);
                        $connect = time();
                        $response = $response . $result2;
                        $event->close_online_people();
                    }
                    if ($file_size != 0) {
                        $fh = fopen('online_users/' . $uniqid, 'r+');
                        flock($fh, LOCK_EX);
                        $i = 0;
                        while (!feof($fh)) {
                            $up[$i] = fgets($fh);
                            $i++;
                        }
                        ftruncate($fh, 0);
                        fclose($fh);
                        $connect = time();
                        for($j=0; $j < $i; $j++) {
                            if (substr($up[$j], 0, 1) == 't') {
				$u = explode("\n",$up[$j]);
				$u = explode(" ",$u[0]);
                                $result   = $event->get_typing($u[1],$u[2],$u[3]);
                                $response = $response . $result;
                            }
                            if (substr($up[$j], 0, 1) == 'c') {
				$u = explode("\n",$up[$j]);
				$u = explode(" ",$u[0]);
                                $result   = $event->get_connect($u[1],$u[2],$u[3],$u[4]);
                                $response = $response . $result;
                            }
                            if (substr($up[$j], 0, 1) == 'm') {
				$u = explode("\n",$up[$j]);
				$u = explode(" ",$u[0]);
                                $event->prepare_getmessage();
                                $result1  = $event->getmessage($u[1],$u[2]);
                                $response = $response . $result1;
                                $event->close_getmessage();
                            }
                        }
                    }
                    if ($response == '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>') {
                        $connect = $connect + 1;
			continue;
		    }
                    else {
                        echo $response . '<connect>' . $connect . '</connect></response>';
                        flush();
                        ob_flush();
                        break;
                    }
                }
                if ($rem == 3) {
                    $dec = $event->remain_online($uniqid, 1);
                    if ($dec <= 0) {
                        echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . '<no_update>1</no_update>';
                        flush();
                        ob_flush();
                        break;
                    }
                    $rem = 0;
                }
                $rem   = $rem + 1;
            }
        }
    } else {
$base->bad_request();
    }
} else {
$base->bad_request();
}
?>
