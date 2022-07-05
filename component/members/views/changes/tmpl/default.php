<?php defined('CREW') or die('Restricted access'); 
$sopt = array( 2=> '[Slike na profilu]', 3=> '[Slike u galerijama]', 0 => '[Sve]' );
$what = explode(',',JText::_('CHANGES'));
$date = '';
echo '<div class="myWindow" >';
$tmp = $this->friends ? ' prijatelji' : '';
echo '<h3 class="normal">'.JText::_('CHANGES_HD').$tmp.'</h3>';

echo '<div style="padding-left:5px;width:540px;float:left;overflow:auto;">';

echo '<div style="padding-left:50px">';
foreach ($sopt as $k => $v){
	$area = $k > 0 ? '&area='.$k : '';
	echo JHTML::lnk('index.php?option=members&view=changes'.$area,$v).' &nbsp; ';
}
echo '</div><br />';

$this->rows = isset($this->rows) ? $this->rows : array();
foreach($this->rows as $row){
	$dt = date('Y-m-d',$row->time);
	if ($date != $dt){
		showdate($row->time);
		$date = $dt;
	}	
	$t = sprintf($what[$row->what-1],JHTML::profileLink($row->who_id,$row->username));	
	echo JHTML::image('arrow.gif').' '.showtime($row->time).' ';
	echo $t;
	if ( $this->area == 2 ){
		echo '<div style="padding-left:150px"><img src="'.Basic::routerBase().'/avatars/'.$row->avatar.'" /></div><br />';
	}
	if ( $this->area == 3 ){
		echo '<div style="padding-left:150px">';
		echo JHTML::picLink($row->tag,'<img src="'.Basic::routerBase().'/photos/'.$row->event_id.'e/'.$row->file_name.'" />');
		echo '</div><br />';
	}
	else if ($row->what==3){
	 	$tg = intval($row->tag);
	 	if ( $tg > 6000 ){
	 		echo ' '.JHTML::picLink($row->tag,'[pogledaj sliku]');
	 	}
	 	else{
	 	 	echo ' '.JHTML::galleryLink($row->tag,'[pogledaj]');
	 	}
	}
	else if ($row->what==4){
		echo ' <span style="color:#777;font-size:9px;">'.$row->tag.'</span>';
	}
	echo '<br/>';
}
echo '</div>';
echo '<div style="float:left">';
$area =  $this->area != 0 ? '&area='.$this->area : '';
echo JHTML::lnk('index.php?option=members&view=changes'.$area,JText::_('CHANGES_OP1'));
echo ' &nbsp; '.JHTML::lnk('index.php?option=members&view=changes&friends=1'.$area,JText::_('CHANGES_OP2'));
echo '<div style="padding:46px;text-align:center;width:180px">';
$db = &Database::getInstance();
$user = &User::getInstance();
if ($this->friends){
	$query = 'SELECT a.comment,a.user_id,a.datetime,b.id,b.file_name,b.event_id,c.username FROM #__photo_comments AS a JOIN #__photo_images AS b ON a.image_id=b.id JOIN #__users AS c ON a.user_id=c.id JOIN #__members_friends AS d ON d.id1='.$user->id.' AND d.id2=a.user_id ORDER by a.id DESC LIMIT 8';
}
else{
	$query = 'SELECT a.comment,a.user_id,a.datetime,b.id,b.file_name,b.event_id,c.username FROM #__photo_comments AS a JOIN #__photo_images AS b ON a.image_id=b.id JOIN #__users AS c ON a.user_id=c.id ORDER by a.id DESC LIMIT 8';
}
$db->setQuery($query);
$list = $db->loadObjectList();
require_once(BASE_PATH.DS.'modules'.DS.'images2.php'); 
echo '</div>';
echo '</div>';
echo '<div style="clear:left;"></div>';
$this->pag->getPagesLinks(false);
echo '</div>';
function showdate($time){
	$dy = date( 'N', $time );
	$weekdays = explode(',',JText::_('DAYS'));
	$tmp = $weekdays[$dy-1];
	$dt = date('Y-m-d',$time);
	list($y,$m,$d) = explode('-',$dt);
	echo '<b>'.$tmp.', '.$d.'.'.$m.'.'.$y.'.</b><br/>';
}
function showtime($time){
	return date('H:i',$time);
}
?>