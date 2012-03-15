var nik = getnick();
 var windowfocus = true;
 var texttype = new Array();
 var typing = null;
 var textfocus = new Array();
 var userinfo = new Array();
 var about = new Array();
 var things_to_talk = new Array();
 var connect = new Array();
 var pid = new Array();
var min_users = 0;
 var ajaxobj = createAjaxObject();
 var fetchinfo = null;
 var hideinfo = null;
 var ring1 = null;
 var conn_lost = null;
 var nullstr = null;
 var returnVal = null;
 var z = 1;
var time = 0;
 var notif = 1;
 var online_people = document.getElementById("online_people");
 
 window.onload = function(){
        assign_actions("on");
        document.getElementById("on").style.top = 100;
        document.getElementById("on").style.left = 20;
    setTimeout("flowControl('r'," + null + "," + null + ")", 100);
};
 
 window.onbeforeunload = function(){
    var obj = false;
    if (window.XMLHttpRequest){
        obj = new XMLHttpRequest;
    }
    else if (window.ActiveXObject){
        obj = new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    obj.onreadystatechange = function(){
        if (obj.readyState != 4) return false;
    };
    obj.open("GET", 'http://127.0.0.1/chat/logout.php?nick=' + encodeURIComponent(nik), false);
    obj.send(null);
};
 
 window.onblur = function(){
    windowfocus = false;
};
 
 window.onfocus = function(){
    windowfocus = true;
    document.title = "Welcome";
};
 document.onclick=checkNotif; 
function checkNotif(e){ 
var target = (e && e.target) || (event && event.srcElement); 
var obj = document.getElementById('notifications'); 
var obj2 = document.getElementById('notif'); 
checkParent(target)?obj.style.display='none':null; 
target==obj2?obj.style.display='block':null; 
} 
function checkParent(t){ 
while(t.parentNode){ 
if(t==document.getElementById('notifications')){ 
return false 
} 
t=t.parentNode 
} 
return true 
} 
 function ontextareablur(elem){
    textfocus[elem] = false;
}
  function ontextareafocus(elem){
    textfocus[elem] = true;
    if(ring1) clearTimeout(ring1);
    document.getElementById("left" + elem).style.backgroundColor = "#6067cd";
    document.getElementById("right" + elem).style.backgroundColor = "#6067cd";
}
 
 function createAjaxObject(){
    var obj = false;
    if (window.XMLHttpRequest){
        obj = new XMLHttpRequest;
    }
    else if (window.ActiveXObject){
        obj = new ActiveXObject("Microsoft.XMLHTTP");
    }
    return obj;
}
 
 function flowControl(flag, to_user, str){
    if (flag == 'r') remain();
    if (flag == 'n') networkSlow();
    if (flag == 'l') lostConn();
    if (flag == 's') execute(to_user, str);
    if (flag == 't') started(to_user);
    if (flag == 'd') stopped(to_user);
}
 
 function reconnect(){
    document.getElementById("error").innerHTML = "<br /><br /><br />Reconnecting. Please wait.";
    var pingobj = createAjaxObject();
    var pingurl = "ping.php";
    try {
        pingobj.open("POST", pingurl, true);
        pingobj.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded', true);
        
        pingobj.onreadystatechange = function(){
            if (pingobj.readyState == 4){
                if (pingobj.status == 200){
                    if (pingobj.responseText == "1"){
                        clearTimeout(conn_lost);
                        removeElement("conn_los"); //to be reviewed
                        document.getElementById("error_note_div").innerHTML = "";
document.getElementById("error_note_div").style.display = "none";
                        if ( ! ajaxobj) ajaxobj = createAjaxObject();
                        flowControl("r", null, null);
                    }
                }
                else if (pingobj.status == 400){
                    document.getElementById("error").innerHTML = 
                       '<span style="font-size:13px;font-weight: bold;">&nbsp Oops!You have been Signed Out</span><br /><span style="font-size:13px;">Please Sign in again</span><br /><a href="http://www.chat-desert_hawk.dotcloud.com">HomePage</a>';
                }
            }
        };
        var pingparams = getParams(1, nik);
        pingobj.send(pingparams);
        conn_lost = setTimeout("flowControl('l','" + nullstr + "','" + nullstr + "')", 4000);
    }
    catch (err){document.getElementById("error_note_div").innerHTML="Internet connection is not available.....";
document.getElementById("error_note_div").style.display = "block";}
}
 
 function ping(){
    var pingobj = createAjaxObject();
    var pingurl = "ping.php";
    try {
        pingobj.open("POST", pingurl, true);
        pingobj.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded', true);
        
        pingobj.onreadystatechange = function(){
            if (pingobj.readyState == 4){
                if (pingobj.status == 200){
                    if (pingobj.responseText == "1"){
                        returnVal = "ok";
                    }
                }
                else if (pingobj.status == 400){
                    returnVal = "bad";
                }
            }
        };
        var pingparams = getParams(1, nik);
        pingobj.send(pingparams);
    }
    catch (err){
        returnVal = "fail";
    }
}
 
 function networkSlow(state){
    if (state == null){
        ping();
        document.getElementById("error_note_div").innerHTML="Oops.. trouble connecting to the network.....";
document.getElementById("error_note_div").style.display = "block";
        setTimeout("networkSlow('check')", 4000);
    }
    if (state == "check"){
        if (returnVal == "ok"){
            if (ajaxobj.readyState != 0 && ajaxobj.readyState != 4) ajaxobj.abort();
            if ( ! ajaxobj) ajaxobj = createAjaxObject();
            flowControl("r", null, null);
            document.getElementById("error_note_div").innerHTML = "";
document.getElementById("error_note_div").style.display = "none";
            returnVal = null;
        }
        else {
            flowControl("l", null, null);
            returnVal = null;
        }
    }
}
 
 function lostConn()	//to be reviewed
   {time = 0;
    if ( ! document.getElementById("conn_los")){
        if (ajaxobj.readyState != 0 || ajaxobj.readyState != 4) ajaxobj.abort();
        document.getElementById("error_note_div").innerHTML="Connection Lost !!";
document.getElementById("error_note_div").style.display = "block";
        var responseText = 
           '<div class= "conn_los" id= "conn_los">\n<table' + ' style= "text-align:left;table-layout:fixed;" width="258' + 
           '" height="165" border= "0" cellpadding= "0" cellspacing=' + 
           ' "0"> \n<tbody>\n<tr> \n<td style="background:transparent;b' + 'ackground-image:url(corner1.png);" width="8" height="30"' + 
           '><br /> </td>\n <td style= "background-color:#8091d6;" widt' + 
           'h="242" height="30"><div class="error_caption">Network I' + 
           'nterruption</div><br /></td>\n <td style="background:transp' + 
           'arent;background-image:url(corner2.png);" width="8" heig' + 
           'ht="30"><br /> </td>\n</tr>\n<tr> \n<td width="8" height="131' + 
           '" style= "background-color:#6067cd;"><br /></td> \n<td widt' + 
           'h="242" height="131"><div id="error" class= "error">&nbsp We a' + 
           're having trouble &nbsp&nbsp&nbsp while connecting to th' + 
           'e &nbsp&nbsp&nbsp&nbsp network. Please check yo' + 
           'ur  &nbsp&nbsp&nbsp&nbsp Internet Connection. <br /><button type="butt' + 
           'on" onclick="reconnect();">Reconnect</button></div> </td>\n <t' + 
           'd width="8" height="131" style= "background-color:#6067c' + 'd;"><br /></td>\n</tr>\n<tr> \n<td width="8" height="4" style' + 
           '= "background-color:#8091d6;"><br /></td>\n <td width="242"' + 
           ' height="4" style= "background-color:#8091d6;"></td> \n<t' + 
           'd width="8" height="4" style= "background-color:#8091d6;' + '"><br /></td>\n</tr>\n</tbody>\n</table>\n</div>';
        createElement(responseText);
    }
    else {
        document.getElementById("error").innerHTML = 
           "Connection not available. Please try again later.<br /><button type=\"button\" onclick=\"reconnect();\">Reconnect<\/button>";
    }
}
 
 function initialize(user,id){
    connect[user] = 0;
    userinfo[user] = false;
    about[user] = "*Not specified by user*";
    things_to_talk[user] = "*Anything*";
    if(id){
       textfocus[user] = true;
       pid[user] = id;
       connect[user] = 1;
       if(document.getElementById('0'+user)){
       document.getElementById('0'+user).style.cssText='font-weight:normal; color:#333333;';
       document.getElementById('r'+user).cells[0].innerHTML = '<div class="dialog"></div>';
       }
    }
}
 
 function terminate(user,id){	//to be reviewed
    if(id){
       userinfo[user] = null;
       about[user] = null;
       things_to_talk[user] = null;
       textfocus[user] = null;
       texttype[user] = null;
       connect[user] = null;
       return;
    }
    connect[user] = 0;
    pid[user] = null;
    document.getElementById('0'+user).style.cssText='font-weight:normal; color:#333333;';
}

 function fetchUserInfo(e,Y){	//to be reviewed
    var user = null;
    if(hideinfo) clearTimeout(hideinfo);
    if(fetchinfo) clearTimeout(fetchinfo);
    if (typeof (e) == "string") {
user = e;
if(!document.getElementById("r"+user)) return;
        if(!Y) Y =  document.getElementById("on").offsetTop + document.getElementById("r"+user).offsetTop + 38;
}
    else {
        if ( ! e) e = window.event;
        user = (e.target || e.srcElement).id;
        user = user.substring(1, user.length);
if(!document.getElementById("r"+user)) return;
        if(!Y) Y = getY(e);
    }
    if (user.length != 0){
        document.getElementById("info").style.left = document.getElementById("on").offsetLeft + 145 + "px";
        document.getElementById("info").style.top = Y-7 + "px";;
        document.getElementById("info").style.display = "block";
        document.getElementById("info").style.zIndex = document.getElementById("on").style.zIndex + 4;
        document.getElementById("wait").style.display = "block";
        document.getElementById("request").style.display = "none";
        document.getElementById("infocontent").innerHTML = '<br /><span style="font-size:11px;">&nbsp Retrieving Information....................</span>';
        if (userinfo[user] != true){
            var infoobj = createAjaxObject();
            var infourl = "info.php";
            infoobj.open("POST", infourl, true);
            infoobj.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded', true);            
            infoobj.onreadystatechange = function(){
                if (infoobj.readyState == 4 && infoobj.status == 200){
                    var info = infoobj.responseXML.documentElement;
                    var rabout = info.getElementsByTagName("about");
                    var rthings_to_talk = info.getElementsByTagName("things_to_talk");
                    if (rabout.length > 0) about[user] = rabout.item(0).firstChild.data.toString();
                    if (rthings_to_talk.length > 0) things_to_talk[user] = rthings_to_talk.item(0).firstChild.data.toString();
                    document.getElementById("wait").style.display = "none";
        document.getElementById("request").style.display = "block";
                   document.getElementById("request").innerHTML = connectStatus(user);
                    document.getElementById("infocontent").innerHTML = '<br /><span style="font-size:18px;font-weight:bold;">&nbsp&nbsp' + sanitize(user) + '</span><span style="font-size:14px;font-weight:bold;"><hr />About:</span><span style="font-size:10px;">&nbsp ' + about[user] + '<hr /></span><span style="font-size:14px;font-weight:bold;">Wants to talk about:</span><span style="font-size:10px;">&nbsp ' + things_to_talk[user] + '<hr /></span>';
                }
            };
            var infoparams = getParams(5, user);
            infoobj.send(infoparams);
            userinfo[user] = true;
        }
        else {
            document.getElementById("wait").style.display = "none";
        document.getElementById("request").style.display = "block";
            document.getElementById("request").innerHTML = connectStatus(user);
            document.getElementById("infocontent").innerHTML = '<br /><span style="font-size:18px;font-weight:bold;">&nbsp&nbsp' + sanitize(user) + '</span><span style="font-size:14px;font-weight:bold;"><hr />About:</span><span style="font-size:10px;">&nbsp ' + about[user] + '<hr /></span><span style="font-size:14px;font-weight:bold;">Things I want to talk:</span><span style="font-size:10px;">&nbsp ' + things_to_talk[user] + '<hr /></span>';
        }
    }
}

 function userInfo(e){
    if ( ! e) e = window.event;
    var user = (e.target || e.srcElement).id;
    user = user.substring(1, user.length);
    document.getElementById("r" + user).style.backgroundColor = "#f2f2f2";
    document.getElementById("0" + user).style.color = "#000000";
    if(fetchinfo) clearTimeout(fetchinfo);
    fetchinfo = setTimeout("fetchUserInfo('" + user + "'," + getY(e) + ")",1100);
}
 
 function hideUserInfo(e){
    if(fetchinfo) clearTimeout(fetchinfo);
    if ( ! e) e = window.event;
    var user = (e.target || e.srcElement).id;
    user = user.substring(1, user.length);
    document.getElementById("r" + user).style.backgroundColor = "#F8F8F8";
    document.getElementById("0" + user).style.color = "#333333";
    hideinfo = setTimeout("hideInfo()",800);
}

  function hideInfo(){
    document.getElementById("info").style.display = "none";
}

 function outInfo(){    
    hideinfo = setTimeout("hideInfo()",500);
}

 function overInfo(){
    if(hideinfo) clearTimeout(hideinfo);
    document.getElementById("info").style.display = "block";
}

 function playSound(from_user,messag,a){
ring(from_user,0);
if(a && windowfocus == false){
    if(messag.length <= 12)   document.title = from_user + " says...'" + messag + "'";
    else     document.title = from_user + " says...'" + messag.substring(0,11) + "'";
}
else if(!a && windowfocus == false) {
document.title = messag;
}
}

function onTheTop(id) {
id.style.zIndex = z++;
}

function ring(from_user,a){
if(!document.getElementById(from_user)) return;
if(!a){
    document.getElementById("left" + from_user).style.backgroundColor = "#c4cce1";
    document.getElementById("right" + from_user).style.backgroundColor = "#c4cce1";
}
else{
    document.getElementById("left" + from_user).style.backgroundColor = "#6067cd";
    document.getElementById("right" + from_user).style.backgroundColor = "#6067cd";
}
if(!a && textfocus[from_user] == false) ring1 = setTimeout("ring(\'"+from_user+"\',1)",300);
else if(a && textfocus[from_user] == false) ring1 = setTimeout("ring(\'"+from_user+"\',0)",300);
}

function getY(e){
        if (e.y) return e.y;
        if (document.layers || document.getElementById && ! document.all)   return e.pageY;
        else if (document.all)   return e.clientY;
}
 function createElement(Text){
    if (document.body.insertAdjacentHTML) document.body.insertAdjacentHTML('beforeEnd', Text);
    else {
        var range = document.createRange();
        var docFragmentToInsert = range.createContextualFragment(Text);
        document.getElementById('box').appendChild(docFragmentToInsert);
    }
}
 function removeElement(id)
{
var elem=document.getElementById(id);
    return (elem=elem.parentNode.removeChild(elem));
}
 function minimize(user){
addToToolbar(user);
        document.getElementById(user).style.visibility = 'hidden';
        document.getElementById(user + 'x').style.visibility = 'hidden';
        document.getElementById(user + 'y').style.visibility = 'hidden';
        document.getElementById(user + 'z').style.visibility = 'hidden';
        document.getElementById(user + 'v').style.visibility = 'hidden';
        document.getElementById(user + 'w').style.visibility = 'hidden';
        document.getElementById(user + 'status').style.visibility = 'hidden';
min_users++;
 }
function maximize(e){
user = e;        
document.getElementById(user).style.visibility = 'visible';
        document.getElementById(user + 'x').style.visibility = 'visible';
        document.getElementById(user + 'y').style.visibility = 'visible';
        document.getElementById(user + 'z').style.visibility = 'visible';
        document.getElementById(user + 'v').style.visibility = 'visible';
        document.getElementById(user + 'w').style.visibility = 'visible';
        document.getElementById(user + 'status').style.visibility = 'visible';
removeElement("min_"+user);
min_users--;
}
function addToToolbar(user){
if(document.getElementById("min_"+user)) removeElement("min_"+user);
document.getElementById("min_users").innerHTML += '<div id=\"min_' + user + '\" onclick=\"maximize(\'' + user + '\');\" class=\"min_user\">' + user + '</div>';
}

function sendRequest(user){	//to be reviewed
if(connect[user] == 1 || connect[user] == 2) return;
            document.getElementById("wait").style.display = "block";
                 document.getElementById("request").style.display = "none";
    var params = getParams(5, user);
    var ajaxobj2 = createAjaxObject();
    var connecturl = "connect.php";
    ajaxobj2.open("POST", connecturl, true);
    ajaxobj2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded', true);
    ajaxobj2.onreadystatechange = function(){
        if (ajaxobj2.readyState == 4 && ajaxobj2.status == 200){
                var res = ajaxobj2.responseText;
                if(res == "1"){
                    connect[user] = 3;
       document.getElementById('0'+user).style.cssText='font-weight:bold; color:#333333;';
                 document.getElementById("wait").style.display = "none";
                 document.getElementById("request").style.display = "block";
                    document.getElementById("request").innerHTML = connectStatus(user);
                }
                else
                iferror(user, res, 2);
        }
    };
    ajaxobj2.send(params);
}
 
function respRequest(user,mode,pid,id){	//to be reviewed
if(connect[user] == 1 || connect[user] == 2) return;
document.getElementById("notification"+id).innerHTML = '<div class="wait"><\/div>';
document.getElementById("notification"+id).style.height = "34px";
displayNotification();
      document.getElementById("new").style.display = "none";
    var params = null;
    if(mode == 1) {
       params = getParams(6, user,pid);
}    
else
       params = getParams(7, user,pid);
    var ajaxobj2 = createAjaxObject();
    var connecturl = "response.php";
    ajaxobj2.open("POST", connecturl, true);
    ajaxobj2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded', true);
    ajaxobj2.onreadystatechange = function(){
        if (ajaxobj2.readyState == 4 && ajaxobj2.status == 200){
                var res = ajaxobj2.responseText;
                if(res.length > 16){
                     if(mode == 1) {
                               document.getElementById("notification"+id).innerHTML = 'You are now connected to <span class=\"notif_user\" onclick=\"fetchUserInfo(\''+user+'\')\">' + user + '</span>';
               displayNotification(2,user,res);
                  }
                     else if(mode == 0){     
document.getElementById("notification"+id).innerHTML = '<span class=\"notif_user\" onclick=\"fetchUserInfo(\''+user+'\')\">' + user + '</span>\'s request has been rejected';
displayNotification();
}
document.getElementById("notification"+id).style.backgroundColor = "#6067cd";
unhookEvent(document.getElementById("notification"+id),"mouseover",handleNotif);
hookEvent("notification"+id,"mouseover",handleNotif);
document.getElementById("notification"+id).style.height = "auto";
                }
                else
                iferror(user, res, 3, id);
        }
    };
    ajaxobj2.send(params);
}

 
 function connectStatus(user){
if(connect[user] == 0)
return '<input type=\"button\" onclick=\"sendRequest(\'' + user + '\');\" value=\"Click to Connect\" />';
else if(connect[user] == 3)
return '<span style=\"font-size:12px;font-weight:bold;\">Status: </span>Connection Request Sent';
else if(connect[user] == 1)
return '<span style=\"font-size:12px;font-weight:bold;\">Status: </span>Connected. <input type=\"button\" onclick=\"openChat(\'' + user + '\');\" value=\"Open ChatBox\" />';
else if(connect[user] == 2)
return '<span style=\"font-size:12px;font-weight:bold;\">Status: </span>Request Rejected. ';
}

 function openChat(e){	//to be reviewed
    if (typeof (e) == "string") from_user = e;
    else {
        if ( ! e) e = window.event;
        from_user = (e.target || e.srcElement).id;
        from_user = from_user.substring(1, from_user.length);
    }
    if ( ! document.getElementById(from_user)){
        var responseText="";
responseText += "<div class=\"chatbox\" onclick=\"onTheTop(this);\" id=\"" + from_user + "\">";
responseText += "<div class=\"chatcontents\">";
responseText += "<div class=\"to_user\" id=\"handleTop" + from_user + "\">" + from_user + "<\/div><div onclick=\"minimize('" + from_user + "');\" class=\"minclose\">-&nbsp;<\/div><div onclick=\"document.getElementById('" + from_user + "').style.display = 'none';\" class=\"minclose\"> x<\/div>";
responseText += "<div class=\"display\" id=\"" + from_user + "v\"><div class=\"chathistory\" id=\"" + from_user + "3\"><span style=\"font-size:11px;color:#8091d6;\">You are now connected to the stranger. Start chatting...<\/span><br /><\/div><\/div>";
responseText += "<div class=\"typearea \" id=\"" + from_user + "w\"><form id=\"" + from_user + "4\"><textarea id=\"" + from_user + "2\" class=\"type\" onblur=\"ontextareablur('" + from_user + "');\" onfocus=\"ontextareafocus('" + from_user + "');\" onkeyup=\"handlekey(event,'" + from_user + "');\" onkeydown=\"handlekey(event,'" + from_user + "');\"><\/textarea><\/form><\/div>";
responseText += "<div class=\"status\" id=\"" + from_user + "status\"><\/div>";
responseText += "<\/div>";
responseText += "<table style='left:0px;top:0px;position:static;' width=\"240\" height=\"315\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"> ";
responseText += "<tbody>";
responseText += "<tr> ";
responseText += "<td style=\"background:transparent;background-image:url(corner1.png);\" width=\"8\" height=\"30\"><br /> <\/td>";
responseText += " <td style=\"background-color:#8091d6;\" width=\"224\" height=\"30\">";
responseText += "<br /><\/td>";
responseText += " <td style=\"background:transparent;background-image:url(corner2.png);\" width=\"8\" height=\"30\"><br /> <\/td>";
responseText += "<\/tr>";
responseText += "<tr id=\"" + from_user + "x\" style=\"visibility:visible;\"> ";
responseText += "<td width=\"8\" height=\"210\" style=\"background-color:#6067cd;\" id=\"left" + from_user + "\"><br /> <\/td> ";
responseText += "<td width=\"224\" height=\"210\">";
responseText += " <\/td>";
responseText += " <td width=\"8\" height=\"210\" style=\"background-color:#6067cd;\" id=\"right" + from_user + "\"><br /> <\/td>";
responseText += "<\/tr>";
responseText += "<tr id=\"" + from_user + "y\" style=\"visibility:visible;\"> ";
responseText += "<td width=\"8\" height=\"56\" style=\"background-color:#6067cd;\"><br /> <\/td>";
responseText += "<td width=\"224\" height=\"56\" style=\"background-color:#6067cd;\"><\/td> ";
responseText += "<td width=\"8\" height=\"56\" style=\"background-color:#6067cd;\"><br /> <\/td>";
responseText += "<\/tr><tr id=\"" + from_user + "z\" style=\"visibility:visible;\"> ";
responseText += "<td width=\"8\" height=\"19\" style=\"background-color:#8091d6;\"><br /> <\/td>";
responseText += " <td width=\"224\" height=\"19\" style=\"background-color:#8091d6;\">";
responseText += " <\/td> ";
responseText += "<td width=\"8\" height=\"19\" style=\"background-color:#8091d6;\"><br /><\/td>";
responseText += "<\/tr> ";
responseText += "<\/tbody>";
responseText += "<\/table>";
responseText += "<\/div>";
        createElement(responseText);
        assign_actions(from_user);
        var randnum = Math.ceil(100 * Math.random());
        document.getElementById(from_user).style.top = 100 + randnum;
        document.getElementById(from_user).style.left = 200 + randnum;
        if (windowfocus == false) textfocus[from_user] = false;
        else textfocus[from_user] = true;
        texttype[from_user] = false;
        document.getElementById(from_user + "status").innerHTML = "";
        document.getElementById(from_user).style.display = 'block';
    }
    else if (document.getElementById(from_user).style.display == 'none') document.getElementById(from_user).style.display = 'block';
}
 
 function remain(){
    var params = "";
    try {
        if (ajaxobj.readyState == 4 || ajaxobj.readyState == 0){
            var params2 = getParams(1);
            var execurl = "events.php";
            ajaxobj.open("POST", execurl, true);
            ajaxobj.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded', true);
            ajaxobj.onreadystatechange = handleresponse;
            ajaxobj.send(params2);
            conn_lost = setTimeout("flowControl('n','" + nullstr + "','" + nullstr + "')", 38000);
        }
    }
    catch (e){
        document.getElementById("error_note_div").innerHTML = 
           "Connection Unavailable.....";
document.getElementById("error_note_div").style.display = "block";
        flowControl("l", null, null);
    }
}
 
 function getParams(tasks, to_user, id, messag){
    if (tasks == 7) var params = "from_user=" + encodeURIComponent(nik) + "&to_user=" + encodeURIComponent(to_user) + "&pid=" + encodeURIComponent(id) + "&mode=0";
    if (tasks == 6) var params = "from_user=" + encodeURIComponent(nik) + "&to_user=" + encodeURIComponent(to_user) + "&pid=" + encodeURIComponent(id) + "&mode=1";
    if (tasks == 5) var params = "from_user=" + encodeURIComponent(nik) + "&to_user=" + encodeURIComponent(to_user);
    if (tasks == 4) var params = "from_user=" + encodeURIComponent(nik) + "&pid=" + encodeURIComponent(pid[to_user]) + "&typing=0";
    if (tasks == 3) var params = "from_user=" + encodeURIComponent(nik) + "&pid=" + encodeURIComponent(pid[to_user]) + "&typing=1";
    if (tasks == 2) var params = "from_user=" + encodeURIComponent(nik) + "&pid=" + encodeURIComponent(pid[to_user]) + "&messag=" + encodeURIComponent(messag);
    if (tasks == 1)var params = "connect=" + encodeURIComponent(time) + "&from_user=" + encodeURIComponent(nik);
    return params;
}
 
 function handleresponse(){
    if (ajaxobj.readyState == 4 && ajaxobj.status == 200){
        var messag = "";
        var rreply = null;
        var roption = null;
        var rconnect = null;
        var rowCount = null;
        var row = null;
        var cell = null;
        var response = ajaxobj.responseXML.documentElement;
        roption = response.getElementsByTagName("option");
        rreply = response.getElementsByTagName("reply");
        rconnect = response.getElementsByTagName("connect");
        if(time == 0) removeUsers(1);
        if (roption.length > 0){
            for (var i = 0;i < roption.length;i++){
                var rid = roption.item(i).attributes.getNamedItem("id").value;
                var value = roption.item(i).firstChild.data.toString();
                if (rid == "on"){
                     removeUsers(value);
                    rowCount = online_people.rows.length;
                    row = online_people.insertRow(rowCount);
                    row.setAttribute("id", "r" + value);
                    cell = row.insertCell(0);
                    cell.style.height = "18px";
                    cell.innerHTML = '<div class="online"></div>';
                    cell = row.insertCell(1);
                    cell.className = "online_user_list";
                    cell.innerHTML = '<div id=\"0' + value + '\" class="online_user">' + value + '<\/div>';
                    hookEvent(cell, "mouseover", userInfo);
                    hookEvent(cell, "mouseout", hideUserInfo);
                    hookEvent(cell, "click", fetchUserInfo);
                    initialize(value);
                    resume(value);
                }
                else if (rid == "off"){
                     removeUsers(value);
                }
            }
        }
        if (rreply.length > 0){
            var reply_length = rreply.length;
            var events = new Array();
            for (var i = 0;i < reply_length;i++){
                events[i] = rreply[i];
            }
            events.sort(function(x, y){	//to be reviewed
                var xt = parseInt(x.getAttribute('timestamp'));
                var yt = parseInt(y.getAttribute('timestamp'));
                return xt - yt;
            });
            for (var i = 0;i < reply_length;i++){
                var reply = events[i];
                var from_user = reply.attributes.getNamedItem("from_user").value;
                var rtyping = reply.getElementsByTagName("typing");
                var conn = reply.getElementsByTagName("conn");
                if (rtyping.length > 0){
                    if (document.getElementById(from_user + "status") && rtyping[0].firstChild.data.toString() == '1') document.getElementById(from_user + 
                       "status").innerHTML = from_user + " is typing.......";
                    else if (document.getElementById(from_user + "status") && rtyping[0].firstChild.data.toString() == '0') document.getElementById(from_user + "status").innerHTML = "";
                }
                else if (conn.length > 0){
                    var val = conn[0].firstChild.data.toString();
                    if (conn.item(0).attributes.getNamedItem("status").value == '1'){
                  displayNotification(1,from_user,val);
                    }
                    else if (conn.item(0).attributes.getNamedItem("status").value == '0'){
                  displayNotification(0,from_user,val);
                    }
                    else if (conn.item(0).attributes.getNamedItem("status").value == '2'){
                  displayNotification(3,from_user);
                    connect[from_user] = 2;
                    }
                }
                else {
                    messag = " " + reply.firstChild.data.toString();
                    display(from_user, messag, 0);
                    if (document.getElementById(from_user + "status")) document.getElementById(from_user + "status").innerHTML = "";
                }
            }
        }
        clearTimeout(conn_lost);
        time = rconnect.item(0).firstChild.data.toString();
if(response.getElementsByTagName("check_users").length > 0){
if(online_people.rows.length != response.getElementsByTagName("check_users").item(0).firstChild.data.toString())
time = 0;
}
        remain();
    }
    else if (ajaxobj.readyState == 4 && ajaxobj.status == 400){
       time = 0;
        if (document.getElementById("conn_los")){
            document.getElementById("error").innerHTML = 
               '<span style="font-size:14px;font-weight: bold;">&nbsp oops!You have been Signed Out</span><br /><span style="font-size:14px;">Please Sign in again</span><br /><a href="http://www.chat-desert_hawk.dotcloud.com">HomePage</a>';
        }
        else {
            flowControl("l", null, null);
            document.getElementById("error").innerHTML = 
               '<span style="font-size:14px;font-weight: bold;">&nbsp oops!You have been Signed Out</span><br /><span style="font-size:14px;">Please Sign in again</span><br /><a href="http://www.chat-desert_hawk.dotcloud.com">HomePage</a>';
        }
    }
}
 
 function removeUsers(value){
                    var rowCount = online_people.rows.length;
                    for (var i = 0;i < rowCount;i++){
                        var row = online_people.rows[i];
                 if(row.getAttribute("id") == "r"+value || value == 1){
                            online_people.deleteRow(i);
                            rowCount--;
                            i--;
                            terminate(row.getAttribute("id"),1);
                      }
                    }
}
function displayNotification(type,user,pid){
if(type == 0){
notif++;
                    document.getElementById("notifications").innerHTML += '<div class=\"notification\" id=\"notification'+notif+'\">A new chat request from <span class=\"notif_user\" onclick=\"fetchUserInfo(\''+user+'\')\">' + user + '</span><br /><input type="button" value="Accept" onclick=\"respRequest(\'' + user + '\',1,\'' + pid + '\',\'' + notif + '\');\" />&nbsp&nbsp<input value="Reject" type="button" onclick=\"respRequest(\'' + user + '\',0,\''  + pid + '\',\'' + notif + '\');\" /><\/div>';
document.getElementById("notification"+notif).style.backgroundColor = "#6067cd";
hookEvent("notification"+notif,"mouseover",handleNotif);
                   if (windowfocus == false)      playSound(user,"New Chat Request__|__|__|",0);
                    document.getElementById("new").style.display = "block";
}
else if(type == 1){
notif++;
                    document.getElementById("notifications").innerHTML += '<div class=\"notification\" id=\"notification'+notif+'\"><span class=\"notif_user\" onclick=\"fetchUserInfo(\''+user+'\')\">' + user + '</span> has accepted your chat request. <\/div>';
document.getElementById("notification"+notif).style.backgroundColor = "#6067cd";
hookEvent("notification"+notif,"mouseover",handleNotif);
                    openChat(user);
                    initialize(user,pid);
}
else if(type == 2){
                    openChat(user);
                    initialize(user,pid);
}
else if(type == 3){
notif++;
                    document.getElementById("notifications").innerHTML += '<div class=\"notification\" id=\"notification'+notif+'\"><span class=\"notif_user\" onclick=\"fetchUserInfo(\''+user+'\')\">' + user + '</span> has rejected your chat request.<\/div>';
document.getElementById("notification"+notif).style.backgroundColor = "#6067cd";
hookEvent("notification"+notif,"mouseover",handleNotif);
}
document.getElementById("notifications").style.height = (notif < 9)?"auto":"400px";
                    document.getElementById("notifications").style.display = "block";
}
function handleNotif(e){
var id
    if (typeof (e) == "string"){ 
id = e;
if(document.getElementById(id)) document.getElementById(id).style.backgroundColor = "#8091d6";
unhookEvent(document.getElementById(id),"mouseover",handleNotif);
}
    else {
        if ( ! e) e = window.event;
        id = (e.target || e.srcElement).id;
setTimeout("handleNotif('" + id.toString() + "')", 1000);
    }
}
 function display(from_user, messag, code){
    var str = null;
    var textBox = document.getElementById(from_user + "3");
        if (code == 1){
            str = '<span class="errormsg">' + messag + '</span>';
        }
        else if (code == 0){
            str = '<span class="from">' + from_user +':' + '</span><span class="frommessage">' + sanitize(messag) + '</span>';
        }
        else if (code == 2){
            str = '<span class="from">You: </span><span class="frommessage">' + sanitize(messag) + '</span>';
        }
        if (document.getElementById(from_user).style.display == 'none'){
            document.getElementById(from_user).style.display = 'block';
        }
        textBox.innerHTML = textBox.innerHTML + str + "<br />";
        textBox.scrollTop = textBox.scrollHeight;
        textBox.style.height = '202px';
        if (textfocus[from_user] == false)    playSound(from_user,messag,1);
}
 
 function resume(to_user){
    if (document.getElementById(to_user)){
        if (document.getElementById(to_user + "2").disabled == true){
            display(to_user, "user online!", 1);
            document.getElementById(to_user + "2").disabled = false;
        }
    }
}
 function execute(to_user, messag){
    messag = trim(decodeURIComponent(messag));
    if (messag != ""){
        var params1 = getParams(2, to_user,null, messag);
        var ajaxobj1 = createAjaxObject();
        var sendurl = "send.php";
        ajaxobj1.open("POST", sendurl, true);
        ajaxobj1.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded', true);
        
        ajaxobj1.onreadystatechange = function(){
            if (ajaxobj1.readyState == 4 && ajaxobj1.status == 200){
                var res = ajaxobj1.responseText;
                if(res != "1")
                iferror(to_user, res, 1);
        else
        display(to_user, messag, 2);
            }
            if (ajaxobj1.readyState == 4 && ajaxobj1.status == 400){}
        };
        ajaxobj1.send(params1);
    }
}
 
 function displayError(str){
    alert(str);
}
 
 function handlekey(e, to){
    var to_user = to;
    var textBox1 = document.getElementById(to_user + "2");
    var textBox2 = document.getElementById(to_user + "4");
    e = ( ! e) ? window.event: e;
    var code;
    if (e.keyCode) code = e.keyCode;
    else if (e.which) code = e.which;
    if (e.type == "keyup" && code == 13){
        textBox2.reset();
    }
    if (e.type == "keydown"){
        if (code == 13){
            clearTimeout(typing);
            var str = encodeURIComponent(textBox1.value);
            textBox2.reset();
            texttype[to_user] = false;
            flowControl("s", to_user, str);
        }
        else {
            clearTimeout(typing);
            if (texttype[to_user] == false){
                texttype[to_user] = true;
                flowControl("t", to_user, null);
            }
            typing = setTimeout("flowControl('d','" + to_user + "','" + nullstr + "')", 3000);
        }
    }
}
 
 function stopped(to){
    var to_user = to;
    var params4 = getParams(4, to_user);
    var ajaxobj3 = createAjaxObject();
    var typeurl = "type.php";
    ajaxobj3.open("POST", typeurl, true);
    ajaxobj3.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded', true);    
    ajaxobj3.onreadystatechange = function(){};
    ajaxobj3.send(params4);
    texttype[to_user] = false;
}
 
 function started(to){
    var to_user = to;
    var params3 = getParams(3, to_user);
    var ajaxobj2 = createAjaxObject();
    var typeurl = "type.php";
    ajaxobj2.open("POST", typeurl, true);
    ajaxobj2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded', true);    
    ajaxobj2.onreadystatechange = function(){};
    ajaxobj2.send(params3);
}
 
 function iferror(to_user, resp, type, id){	//to be reviewed
    if (type == 1 && resp == "user offline!"){
        display(to_user, to_user+" is offline now!!!", 1);
        document.getElementById(to_user + "2").disabled = true;
 removeUsers(to_user);
    }
    if (type == 1 && resp == "message too long!"){
        display(to_user, resp, 1);
        document.getElementById(to_user + "2").disabled = false;
    }
    if (type == 2 && resp == "user offline!"){
                document.getElementById("request").innerHTML = '<span class="errormsg">' + to_user +' is offline now!!!</span>';
                    setTimeout("removeUsers('"+to_user+"')",1000);
    }
    if (type == 3 && resp == "user offline!"){
                document.getElementById("notification"+id).innerHTML = '<span class="notif_user">' + to_user +'</span> is offline now!!!';
document.getElementById("notification"+id).style.backgroundColor = "#6067cd";
unhookEvent(document.getElementById("notification"+id),"mouseover",handleNotif);
hookEvent("notification"+id,"mouseover",handleNotif);
                    setTimeout("removeUsers('"+to_user+"')",1000);
    }
}
 
 function trim(str){
    if (typeof str != "string"){
        return str;
    }
    var trimmedstr = str;
    var ch = trimmedstr.substring(0, 1);
    while (ch == " " || ch == "\n"){
        trimmedstr = trimmedstr.substring(1, trimmedstr.length);
        ch = trimmedstr.substring(0, 1);
    }
    ch = trimmedstr.substring(trimmedstr.length - 1, trimmedstr.length);
    while (ch == " " || ch == "\n"){
        trimmedstr = trimmedstr.substring(0, trimmedstr.length - 1);
        ch = trimmedstr.substring(trimmedstr.length - 1, trimmedstr.length);
    }
    while (trimmedstr.indexOf("  ") != - 1){
        trimmedstr = trimmedstr.substring(0, trimmedstr.indexOf("  ")) + trimmedstr.substring(trimmedstr.indexOf("  ") + 
           1, trimmedstr.length);
    }
    return trimmedstr;
}
 
 function sanitize(str){
    var sanitizedstr = '';
    var a = 0;
    for (i = 0;i < str.length;i++){
        if (a == 3){
            sanitizedstr += '\r';
            a = 0;
        }
        sanitizedstr += ( "&#" + str.charCodeAt(i) +";");
        if (str.charCodeAt(i) == 32) a += 1;
    }
    return sanitizedstr;
}
 
