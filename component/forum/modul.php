<?php
defined('CREW') or die();
echo '<div class="myWindow">';
echo '<h3 class="normal">'.STAT_FORUM.'</h3>'; 
$db = &Database::getInstance();
$query = 'SELECT id,subject,time,last_username AS username FROM #__forum_topics ORDER BY time DESC LIMIT 12';
//$query = 'SELECT last_id,subject,time,last_username AS username FROM #__forum_topics ORDER BY time DESC LIMIT 12';
$db->setQuery($query);
$rows = $db->loadObjectList();
$time = time();
foreach ($rows as $row){
	// echo JHTML::lnk('forum?pid='.$row->last_id,$row->subject);
	 echo JHTML::lnk('forum?task=last&tid='.$row->id,$row->subject);	
	 echo '<br/>';
	 echo '<span style="font-style:italic"> pre '; 
	 JHTML::_time($row->time);
	 echo '</span> od ';	 
	 echo $row->username.'<br>';
}  
echo '</div>';
?>