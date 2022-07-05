<?php defined('CREW') or die('Restricted access');
$doc = &Document::getInstance();
$doc->setTitle('Extrafull forum - '.$this->topic->subject);
$doc->setDescription('forum - '.$this->topic->subject.'.'.$doc->description);
//$doc->addOnLoad( "fixImgs('postSt',580)" );

$user = &User::getInstance();
$myid = $user->id;
require_once(JPATH_BASE.DS.'editor'.DS.'editor.php');	
echo '<div style="padding:4px">';
echo '<span class="pathway">';
$dummyImg = '<img src="data:," alt>';
echo JHTML::lnk('forum','Forum').$dummyImg.JHTML::lnk('forum?cid='.$this->topic->cid,$this->topic->name).$dummyImg.$this->topic->subject;
echo '</span><br/>';
echo '<div style="text-align:right;">';
if ($this->rights > 1){
	echo JHTML::lnk('forum?task=newP&tid='.$this->topic->id,JHTML::image('forum/reply.gif'));
	echo '&nbsp; <br/>';
}
if ($this->isMod){
	if ($this->topic->sticky) $tmp = '[unsticky]'; else $tmp = '[sticky]';
	echo '&nbsp;'.JHTML::lnk('forum?task=change&sticky=1&tid='.$this->topic->id,$tmp);
	if ($this->topic->locked) $tmp = '[unlock]'; else $tmp = '[lock]';
	echo '&nbsp;'.JHTML::lnk('forum?task=change&tid='.$this->topic->id,$tmp).' &nbsp ';
}
echo '&nbsp;';$this->pag->getPagesLinks(2);echo ' &nbsp; ';
echo '</div>';
echo '<div id="postSt">';
if (count($this->posts)){
$date = date('m-d');
$year = intval(date('Y'));
foreach ($this->posts as $p){
	echo '<table class="forum" width="800px" cellspacing="1">';
	echo '<tr>';
	echo '<td width="200px" align="left" class="forumtitle">';
	echo '<span style="color:#333">'.JHTML::date($p->time).'</span>';
	echo '</td>';
	echo '<td class="forumtitle2" align="left"><span class="forumone">';
	echo '<a style="color:#000;" name="post'.$p->id.'">'.$p->subject.'</a>';
	echo '</span></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td valign="top" style="border-bottom:none;"><div style="width:200px;overflow:auto">';
	if ($p->gender<2) echo '<div class="maleC">';
	else echo '<div class="femaleC">';
	$y = intval(substr($p->birthdate,0,4));
	$tmp = substr($p->birthdate,5);
	$y = $year - $y;
	if ($date < $tmp){
		--$y;
	}
	echo JHTML::profileLink($p->who_id,$p->username).' <span class="forumtwo">('.$y.')</span>';
	echo '</div>';
	echo $p->name;
	echo '<div class="forumtwo">Postova: '.$p->posts;
	echo '<br />Lokacija: '.$p->location;
	echo '</div><br />';
	//echo JHTML::lnk('index.php?option=jim&task=new&to='.urlencode($p->username),'[privatna poruka]');
	echo '</div></td>';
	echo '<td valign="top"><div style="width:598px;overflow:auto">';
	echo editor_decode($p->message);
	echo '</div></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>&nbsp;</td>';
	echo '<td valign="bottom" align="left"><div style="width:598px;overflow:auto">';
	echo '<span style="color:#777" class="forumtwo">'.$p->signature.'</span>';
	echo '<div style="padding:2px;text-align:right;">';
	if ($this->rights > 1){
		echo JHTML::lnk('forum?task=newP&pid='.$p->id,'[citiraj]');
	}
	if ($this->isMod || ($p->who_id==$user->id && $this->rights > 1)){
		$lnkCh = 'forum?task=newP&change=1&pid='.$p->id;
		if ($this->topic->id == $p->id) $lnkCh .= '&first=1';
		echo '&nbsp;'.JHTML::lnk($lnkCh,'[izmeni]');
	}	
	if ($this->isMod|| ($p->who_id==$user->id && $this->rights > 1 && $this->topic->id != $p->id)){// || $p->who_id == $myid){
		echo '&nbsp;'; //.JHTML::lnk('forum?task=del&pid='.$p->id,JText::_('ERASE'));
		echo '<a onclick="return dlgCnf(1)" href="'.Basic::routerBase().'/forum?task=del&pid='.$p->id.'">';
		echo JText::_('ERASE');
		echo '</a>';
	}
	echo '&nbsp;</div></div></td>';
	echo '</tr>';
	echo '</table>';
}
}else{
}
echo '</div>';
echo '<div style="text-align:right;">';
echo '&nbsp;';$this->pag->getPagesLinks(2);echo ' &nbsp; ';
if ($this->rights > 1){
	 echo '<br/>'.JHTML::lnk('forum?task=newP&tid='.$this->topic->id,JHTML::image('forum/reply.gif')).'&nbsp; <br/><br/>';
}
echo '</div>';
echo '</div>';
?>