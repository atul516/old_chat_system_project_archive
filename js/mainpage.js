/*Script Name: mainpage.js
  Created By: http://www.TalkToX.com/
  Email: admin@talktox.com
*/
function select_all(a){if(a.value=="Type your nick"||a.value=="Your Email Address")a.value="";}function focused(area){area.style.backgroundColor="#FFFFFF";}function blurred(a,flag){if(a.value==""&&flag==1)a.value="Type your nick";else if(a.value==""&&flag==2)a.value="Your Email Address";}function submit_login_form(){ document.getElementById("form").style.display = "none"; document.getElementById("wait").style.display = "block";/*'<span style="color:#31f617;font-size: 12px;">Please Wait...............</span>';*/document.getElementById("reply5").innerHTML='';var params=get_params(1);var obj=false;if(window.XMLHttpRequest){obj=new XMLHttpRequest;}else if(window.ActiveXObject){obj=new ActiveXObject("Microsoft.XMLHTTP");}var execurl="chat/Auth_sign_in.php";obj.open("POST",execurl);obj.setRequestHeader('Content-Type','application/x-www-form-urlencoded',true);obj.onreadystatechange=function(){if(obj.readyState==4&&obj.status==200){var info=obj.responseText;if(info.indexOf("uid=")>-1)window.location=info;else if(info.indexOf("CAPTCHA")>-1){document.getElementById("wait").style.display = "none"; document.getElementById("form").style.display = "block"; Recaptcha.reload(); document.getElementById("reply5").innerHTML='<span style="color:red;font-size: 12px;">'+info+'</span>';document.getElementById("reply").innerHTML='';}else{ document.getElementById("wait").style.display = "none"; document.getElementById("form").style.display = "block"; Recaptcha.reload(); document.getElementById("reply").innerHTML='';document.getElementById("reply").innerHTML='<span style="color:red;font-size: 12px;">'+info+'</span>';}}};obj.send(params);}function submit_feedback_form(a,b){ document.getElementById("feedback").innerHTML='<span style="color:#0776ae;font-size: 20px;"><br>Operation not permitted yet.<br></span>'; return false;/*document.getElementById("feedback_captcha").style.display="block";var params=get_params(2);var obj=false;if(window.XMLHttpRequest){obj=new XMLHttpRequest;}else if(window.ActiveXObject){obj=new ActiveXObject("Microsoft.XMLHTTP");}var execurl="feedback.php";obj.open("POST",execurl);obj.setRequestHeader('Content-Type','application/x-www-form-urlencoded',true);obj.onreadystatechange=function(){if(obj.readyState==4&&obj.status==200){var info=obj.responseText;if(info.indexOf("sent")>-1)document.getElementById("feedback").innerHTML='<span style="color:#0776ae;font-size: 20px;"><br>Confirmation:<br>'+info+'</span>';else document.getElementById("reply4").innerHTML='<span style="color:red;font-size: 12px;">'+info+'</span>';}};obj.send(params); */}function get_params(flag){if(flag==1)var params="nick="+encodeURIComponent(document.login.nick.value)+"&aboutyou="+encodeURIComponent(document.login.aboutyou.value)+"&thingstotalk="+encodeURIComponent(document.login.thingstotalk.value)+"&recaptcha_challenge_field="+encodeURIComponent(document.login.recaptcha_challenge_field.value)+"&recaptcha_response_field="+encodeURIComponent(document.login.recaptcha_response_field.value);if(flag==2){var a=document.feedbackform.email;if(a.value=="Your Email Address")a.value="";var params="email="+encodeURIComponent(document.feedbackform.email.value)+"&feedback_text="+encodeURIComponent(document.feedbackform.feedback_text.value);}return params;}
