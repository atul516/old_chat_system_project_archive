<form name="interests" action="chat" method=post>
<input name="nick" type="hidden" size=15 value="<?php echo $user; ?>">
<input name="aboutyou" type="hidden" size=15 value="<?php echo $aboutyou; ?>">
<input name="interests" type="hidden" size=100 value="<?php echo $interests; ?>">
<input name="thingstotalk" type="hidden" size=100 value="<?php echo $thingstotalk; ?>">
<input type="submit" value="Continue" />
</form>
<script type="text/javascript">document.interests.submit();</script>