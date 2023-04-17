<?php
/*
Main execution script
Created by : Atul Pratap Singh
Project : Online Chat System
*/
require_once('base.class.php');
require_once('error_handler.php');
class events extends base {
    public $msql;
    private $msql1;
    private $msql2;
    private $get_query;
    private $del2;
    private $modif;
    function __construct() {
        $this->msql = new mysqli(host,user,pw,db,port);
        $this->msql1 = new mysqli(host,user,pw,db,port);
        $this->msql2 = new mysqli(host,user,pw,db,port);
    }
    function __destruct() {
        $this->msql->close();
        $this->msql1->close();
        $this->msql2->close();
    }
    function prepare_getmessage() {
        $this->get_query=$this->msql1->prepare("SELECT id,message,timestamp FROM message_store WHERE chat_pair_id=? ORDER BY id ASC");
        $this->del2=$this->msql2->prepare("DELETE FROM message_store WHERE id = ?");
    }
    function prepare_online_people() {
        $this->modif =$this->msql1->prepare("SELECT uniqid,nick,type FROM modification WHERE timestamp >?");
    }
    function get_typing($status,$timestamp,$typinguser) {
        $typinguser = $this->sanitize($this->sanitize($typinguser));
        $response = '<reply timestamp="'.$timestamp.'" from_user="'.$typinguser.'"><typing>'.$status.'</typing></reply>';
        return $response;
    }
    function get_connect($status,$timestamp,$user,$pid) {
        $user = $this->sanitize($this->sanitize($user));
        $response = '<reply timestamp="'.$timestamp.'" from_user="'.$user.'"><conn status="'.$status.'">' . $pid . '</conn></reply>';
        return $response;
    }
    function getmessage($pid,$from_user) {
        $response=null;
        $this->get_query->bind_param("i", $pid);
        $this->get_query->execute();
        $this->get_query->bind_result($lid,$message,$timestamp);
        $num=1;
        while($row = $this->get_query->fetch()) {
            $from_user = $this->sanitize($this->sanitize($from_user));
            $message = $this->sanitize($this->sanitize($message));
            $response =$response.'<reply timestamp="'.$timestamp.'" from_user="'.$from_user.'">'.$message.'</reply>';
            $num=$num+1;
            $this->del2->bind_param("i",$lid);
            $this->del2->execute();
        }
        return $response;
    }
    function online_people($from_user,$connect) {
        $response=null;
        $option='<option id="';
        if($connect == 0) {
            $query='SELECT uniqid,nick FROM online_users';
            $result=$this->msql->query($query);
            if($result->num_rows) {
                while($row = $result->fetch_row()) {
                    $user = $row[0];
                    $nik = $row[1];
                    if($user != $from_user) {
                        if($this->difference($user)<8) {
                            $response=$response.$option.'on'.'">'.$this->sanitize($nik).'</option>';
                        }
                        else {
                            $this->update($user,$nik,1);
                        }
                    }
                }
            }
            return  '<online_people>'.$response.'</online_people>';
        }
        else if($connect != 0) {
            //date_default_timezone_set('Asia/Calcutta');
            $lag = $this->msql->query('SELECT NOW()-8');
            $row1=$lag->fetch_row();
            $lag1 = 'SELECT uniqid FROM uncertain WHERE finish_time<'.$row1[0];
            $uncert=$this->msql->query($lag1);
            if($uncert->num_rows)
            while($row=$uncert->fetch_row()) {
                $this->update($row[0],$this->find_user_name($row[0]));
            }
            $this->modif->bind_param("s",$connect);
            $this->modif->execute();
            $this->modif->bind_result($user,$nick,$type);
            while($row = $this->modif->fetch()) {
                if($type == 1) $st = 'on';
                else $st = 'off';
                if($user != $from_user) {
                    $response=$response.$option.$st.'">'.$this->sanitize($nick).'</option>';
                }
            }
            if($response)
                return '<online_people>'.$response.'</online_people>';
            else
                return null;
        }
    }
    function close_getmessage() {
        $this->get_query->close();
        $this->del2->close();
    }
    function close_online_people() {
        $this->modif->close();
    }
    function uncertain($user,$type) {
        if($type == 1) {
            $insert=$this->msql->prepare("INSERT INTO uncertain(uniqid,finish_time) VALUES(?,NOW())");
            $insert->bind_param("i",$user);
            $insert->execute();
            $insert->close();
        }
        else {
            $delete=$this->msql->prepare("DELETE FROM uncertain WHERE uniqid=?");
            $delete->bind_param("i",$user);
            $delete->execute();
            $delete->close();
        }
    }
    function update($off,$nik) {
        $upd=$this->msql->prepare("INSERT INTO modification(uniqid,nick,type,mod_time,timestamp) VALUES(?,?,0,NOW(),?)");
        $upd->bind_param("isi",$off,$nik,time());
        $upd->execute();
        $upd->close();
        $fp = fopen('online_users/Global_Online_Modification', 'a');
        fwrite($fp,'1');
        fclose($fp);
        $upd=$this->msql->prepare("DELETE FROM online_users WHERE uniqid=?");
        $upd->bind_param("i",$off);
        $upd->execute();
        $upd->close();
        $upd=$this->msql->prepare("DELETE FROM uncertain WHERE uniqid=?");
        $upd->bind_param("i",$off);
        $upd->execute();
        $upd->close();
        if (file_exists('online_users/' . $off))
            unlink('online_users/'.$off);        
    }
    function deletemessages() {
        $query='TRUNCATE TABLE message_store';
        $result=$this->msql1->query($query);
    }
    function post_request() {
        $pid = $this->rand_str();
        $post_request=$this->msql->prepare("INSERT INTO pending_requests(pid,timestamp) VALUES(?,?)");
        $post_request->bind_param("si",$pid,time());
        $post_request->execute();
        $post_request->close();
        return $pid;
    }
    function remove_pending_request($pid) {
        $post_request=$this->msql->prepare("DELETE  FROM pending_requests WHERE pid=?");
        $post_request->bind_param("s",$pid);
        $post_request->execute();
        $dec = $post_request->affected_rows;
        $post_request->close();
        if($dec <= 0)
            return 0;
        else return 1;
     }
    function accept_request($from_user,$to_user) {
         $post_request=$this->msql->prepare("INSERT INTO current_chat_pairs(initiated_by,accepted_by,timestamp) VALUES(?,?,?)");
         $post_request->bind_param("iii",$from_user,$to_user,time());
         $post_request->execute();
         $post_request->close();
         $post_request=$this->msql->prepare("SELECT id FROM current_chat_pairs WHERE initiated_by=? AND accepted_by=?");
         $post_request->bind_param("ii",$from_user,$to_user);
         $post_request->execute();
         $post_request->bind_result($a);
         $post_request->fetch();
         $post_request->close();
         return $this->encrypt($a);
    }
    function is_chat_pair($from_user,$pid) {
        $request=$this->msql->prepare("SELECT initiated_by,accepted_by FROM current_chat_pairs WHERE id=?");
        $request->bind_param("i",$pid);
        $request->execute();
        $request->bind_result($a,$b);
        $request->fetch();
        $request->close();
        if(isset($a))
           return ($a == $from_user) ? $b : $a;
        else
           return 0;
    }
    function postmessage($pid,$message) {
        $post_message=$this->msql->prepare("INSERT INTO message_store(chat_pair_id,message,sent_on,timestamp) VALUES(?,?,NOW(),?)");
        $post_message->bind_param("isi",$pid,$message,time());
        $post_message->execute();
        $post_message->close();
        $post_message=$this->msql->prepare("INSERT INTO perm_message_store(chat_pair_id,message,sent_on,timestamp) VALUES(?,?,NOW(),?)");
        $post_message->bind_param("isi",$pid,$message,time());
        $post_message->execute();
        $post_message->close();
    }
    function info($nick,$uniqid) {
        $nick = $this->sanitize($this->sanitize($nick));
        $fetch_info =$this->msql->prepare("SELECT about,things_to_talk FROM interests_info WHERE uniqid=?");
        $fetch_info->bind_param("i",$uniqid);
        $fetch_info->execute();
        $fetch_info->bind_result($about,$things_to_talk);
        $inf = null;
        while($row = $fetch_info->fetch()) {
            $about = $this->sanitize($this->sanitize($about));
            $things_to_talk = $this->sanitize($this->sanitize($things_to_talk));
            $inf = '<nick>'.$nick.'</nick>';
            if(strlen($about) > 0)
            $inf = $inf.'<about>'.$about.'</about>';
            if(strlen($things_to_talk) > 0)
            $inf = $inf.'<things_to_talk>'.$things_to_talk.'</things_to_talk>';
        }
        $fetch_info->close();
        return $inf;
    }
    function remain_online($from_user,$req) {
        if($req == 0) {
            $update=$this->msql1->prepare("UPDATE online_users SET first_loop_request=NOW(),last_online=? WHERE uniqid=?");
            $update->bind_param("ii",time(),$from_user);
            $update->execute();
            $update->close();
        }
        else {
            $update=$this->msql1->prepare("UPDATE online_users SET last_online=? WHERE uniqid=?");
            $update->bind_param("ii",time(),$from_user);
            $update->execute();
            $dec = $update->affected_rows;
            $update->close();
            return $dec;
        }
    }    
    function first_req($from_user) {
        $var = $this->msql->prepare("SELECT first_loop_request FROM online_users WHERE uniqid = ?");
        $var->bind_param("i",$from_user);
        $var->execute();
        $var->bind_result($a);
        $var->fetch();
        $var->close();
        if(isset($a))
           return 1;
        else
           return 0;
    }  
    function last_online($from_user) {
        $var = $this->msql->prepare("SELECT last_online FROM online_users WHERE uniqid = ?");
        $var->bind_param("i",$from_user);
        $var->execute();
        $var->bind_result($a);
        $var->fetch();
        $var->close();
        return $a;
    }
    function mark_modif($from_user){
        $insert=$this->msql1->prepare("INSERT INTO modification(uniqid,nick,type,mod_time,timestamp) VALUES(?,?,1,NOW(),?)");
        $insert->bind_param("isi",$from_user,$this->find_user_name($from_user),time());
        $insert->execute();
        $insert->close();
        $fp = fopen('online_users/Global_Online_Modification', 'a');
        fwrite($fp,'1');
        fclose($fp);
    }
    function typing($from_user,$to_user,$typing) {
        $type=$this->msql->prepare("INSERT INTO typing(from_user,to_user,status,timestamp) VALUES(?,?,?,?)");
        $on = 1;
        $off = 0;
        if($typing == 1)
            $type->bind_param("ssii",$from_user,$to_user,$on,time());
        else if ($typing == 0)
            $type->bind_param("ssii",$from_user,$to_user,$off,time());
        $type->execute();
        $type->close();
    }
function check_users($from_user){
$count = 0;
            $query='SELECT uniqid,nick FROM online_users';
            $result=$this->msql->query($query);
            if($result->num_rows) {
                while($row = $result->fetch_row()) {
                    $user = $row[0];
                    $nik = $row[1];
                    if($user != $from_user) {
                        if($this->difference($user)<8) {
$count++;
                        }
                        else {
                            $this->update($user,$nik,1);
                        }
                    }
                }
           }
return $count;
}
}
?>
