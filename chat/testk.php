<?php
if (isset($_POST['connect']) && isset($_POST['from_user'])) {
        set_time_limit(40);
        ignore_user_abort(false);
        $from_user = $_POST['from_user'];
        $connect = $_POST['connect'];
        if (file_exists('online_users/'. $from_user)) {
            $cycle = 0;
            $rem   = 0;
            $up    = array();
            require('admin.php');
            require('error_handler.php');
            require('execute3.class.php');
            $event = new events();
            $event->remain_online($from_user, $connect, 0);
            $event->uncertain($from_user, 0);
            header('Content-Type: text/xml');
            header("Expires: Wed, 16 May 1991 09:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            if ($connect == 0) {
                $result   = $event->online_people($from_user, $connect);
                $response = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
                $response = $response . '<response>';
                $response = $response . $result;
		$connect = time();
                echo $response.'<connect>'.$connect.'</connect></response>';
                flush();
                ob_flush();
            } else {
                while (1) {
                    //flush();
                    //if (!connection_aborted()) {
                        ob_start();
                        if ($cycle == 30) {
			    $connect = time();
                            $event->uncertain($from_user, 1);
                            echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.'<no_update>1</no_update><connect>'.$connect.'</connect>';
                            flush();
                            ob_flush();
                            break;
                        }
			$last_modified = filemtime('online_users/' . $from_user);
                        if ($last_modified >= $connect) {
                            $response = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>';
                            $fh = fopen('online_users/' . $from_user, 'r+');
                            flock($fh, LOCK_EX);
                            $i = 0;
                            while (!feof($fh)) {
                                $up[$i] = fgets($fh);
                                $i++;
                            }
                            ftruncate($fh,0);
                            $connect = time();
                            fclose($fh);
                            while ($i > 0) {
                                switch ($up[$i - 1]) {
                                    case "t":
                                        $event->prepare_typing();
                                        $result   = $event->get_typing($from_user);
                                        $response = $response . $result;
                                        $event->close_typing();
                                        break;
                                    case "m":
                                        $event->prepare_getmessage();
                                        $result1  = $event->getmessage($from_user);
                                        $response = $response . $result1;
                                        $event->close_getmessage();
                                        break;
                                    case "o":
                                        $event->prepare_online_people();
                                        $result2  = $event->online_people($from_user, $connect);
                                        $response = $response . $result2;
                                        $event->close_online_people();
                                        break;
                                }
                                $i--;
                            }
                            echo $response . '<connect>'.$connect.'</connect></response>';
                            flush();
                            ob_flush();
                            break;
                        }
                        if ($rem == 2) {
                            $dec = $event->remain_online($from_user, $connect, 1);
                            if ($dec <= 0) {
                                echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.'<no_update>1</no_update>';
                                flush();
                                ob_flush();
                                break;
                            }
                            $rem = 0;
                        }
                        $rem   = $rem + 1;
                        $cycle = $cycle + 1;
                    //} else {
                      //  flush();
                        //ob_flush();
                        //break;
                    //}
                sleep(1);
                }
            }
        } else {
            header("HTTP/1.1 400 Not Found");
            header("Status: 400 Not Found");
            echo "Bad Request. Try the proper way!";
            flush();
            ob_flush();
        }
} else {
    header("HTTP/1.1 400 Not Found");
    header("Status: 400 Not Found");
    echo "Bad Request. Try the proper way!";
    flush();
    ob_flush();
}
?>
