<?php defined('CREW') or die('Restricted access');
$doc = &Document::getInstance();
$doc->setTitle('Extrafull forum - '.$this->what );

$user = &User::getInstance();
$myid = $user->id;
require_once(JPATH_BASE.DS.'editor'.DS.'editor.php');	
echo '<div style="padding:4px">';
echo '<div style="text-align:right;">';
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
	echo JHTML::lnk('forum?pid='.$p->id, $p->subject).'</a>';
	echo '</span></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td width="200px" valign="top" style="border-bottom:none;">';
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
	echo '</td>';
	echo '<td valign="top"><div style="width:598px;overflow:auto">';
	echo editor_decode($p->message,true);
	echo '</div>';
	echo '<span class="forumtwo">'.JHTML::lnk('forum?pid='.$p->id, '[pogledaj]').'</span>';
	echo '</td></tr>';
	echo '</table>';
}
}else{
}
echo '</div>';
echo '<div style="text-align:right;">';
echo '&nbsp;';$this->pag->getPagesLinks(2);echo ' &nbsp; ';
echo '</div>';
echo '</div>';
?>