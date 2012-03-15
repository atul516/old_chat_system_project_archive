
 function handleresponse(){
  if (ajaxobj.readyState == 4 && ajaxobj.status == 200){
    var option = "<OPTION VALUE=";
    var messag = "";
    var online_people = document.online_users.online_people;
    var response = ajaxobj.responseXML.documentElement;
    var rtyping = response.getElementsByTagName("typing");
    var rreply = response.getElementsByTagName("reply");
    var roption = response.getElementsByTagName("option");
    var rconnect=response.getElementsByTagName("connect");
    if (roption != null){
      for (var i = 0;i < roption.length;i++){
        var rid = roption.item(i).attributes.getNamedItem("id").value;
        var value = roption.item(i).firstChild.data.toString();
        if (rid == "on" && connect != 0){
          online_people.options[online_people.length] = new Option(value, value, false, false);
          userinfo[value] = false;
          resume(value);
        }
        else if (rid == "off" && connect != 0){
          for (var i = 0;i < online_people.length;i++) if (online_people.options[i].text == value) online_people.options[i] = null;
        }
        else if(connect == 0) {
          online_people.options[online_people.length] = new Option(value, value, false, false);
          userinfo[value] = false;
          resume(value);
        }
      }
    }
    if (rtyping != null){
      for (var i = 0;i < rtyping.length;i++){
        var typinguser = rtyping.item(i).attributes.getNamedItem("from_user").value;
        var status = rtyping.item(i).firstChild.data.toString();
        if (document.getElementById(typinguser + "status") && status == 1) document.getElementById(typinguser + "status").innerHTML = typinguser + " is typing....";
        else if (document.getElementById(typinguser + "status") && status == 0) document.getElementById(typinguser + "status").innerHTML = "";
      }
    }
    if (rreply != null){
      for (var i = 0;i < rreply.length;i++){
        messag = messag + " " + rreply.item(i).firstChild.data.toString();
        var from_user = rreply.item(i).attributes.getNamedItem("from_user").value;
        if (i == rreply.length - 1) if (document.getElementById(from_user + "status")) document.getElementById(from_user + "status").innerHTML = "";
        display(from_user, messag, 0);
	var exists = 0;
	for (var i = 0;i < online_people.length;i++) if (online_people.options[i].text == from_user) exists = 1;
	if(exists == 0) {
	online_people.options[online_people.length] = new Option(from_user, from_user, false, false);
        userinfo[from_user] = false;
	}
      }
    }
    connect = rconnect.item(0).firstChild.data.toString();
    clearTimeout(conn_lost);
    setTimeout("remain()",3000);
  }
  else if (ajaxobj.readyState == 4 && ajaxobj.status == 400){
    connect = 0;
    if (document.getElementById("conn_los")){
      document.getElementById("error").innerHTML = '<span style="font-size:12px;font-weight: bold;">&nbsp oops!You have been Signed Out</span><br><span style="font-size:12px;">Please Sign in again</span><br><a href="http://www.talktox.com">HomePage</a>';
    }
    else {
      flow_control("l", null, null);
      document.getElementById("error").innerHTML = '<span style="font-size:12px;font-weight: bold;">&nbsp oops!You have been Signed Out</span><br><span style="font-size:12px;">Please Sign in again</span><br><a href="http://www.talktox.com">HomePage</a>';
    }
  }
}









