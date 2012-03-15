<?php
    require_once('chat/error_handler.php');
    require_once('new/admin2.php');
    $msql = new mysqli(host,user,pw,db1);
    $insert_text=$msql->prepare("INSERT INTO feedback_table(email,sent_on,text) VALUES(?,NOW(),?)");
    $insert_text->bind_param("ss",$email,$text);
    $insert_text->execute();
    $insert_text->close();
    echo "Feedback successfully sent. Thank you for taking the time to provide us your feedback. We will use your comments for providing better services in future.";
?>
