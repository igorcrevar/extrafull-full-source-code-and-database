<?php defined('CREW') or die('Restricted access');
$doc = &Document::getInstance();
$doc->setTitle('Extrafull forum - '.$this->cat->name);
echo '<div style="padding:4px">';
echo '<span class="pathway">';
echo JHTML::lnk('forum','Forum').'<img src="ddd"/>'.$this->cat->name;
echo '</span><br/>';

$this->loadParticle( 'forum', 'search', array( 'cid' => $this->cat->id) );
if ($this->rights > 1){
	echo '<div style="text-align:right;">';
	echo JHTML::lnk('forum?task=newP&cid='.$this->cat->id,JHTML::image('forum/new_thread.gif')).' &nbsp; </div><br/>';
}
echo '<table class="forum" cellspacing="1">';
echo '<tr><td class="forumtitle" width="400px" colspan="2">Tema</td>';
echo '<td class="forumtitle2" width="40px">Odgovora</td>';
echo '<td class="forumtitle" align="center" width="300px">Poslednja poruka</td></tr>';
if (count($this->topics)){
foreach ($this->topics as $t){
	echo '<tr';
	if ($t->sticky) echo ' class="sticky"';
	echo '>';
	echo '<td>';
	if ($t->locked){
		echo JHTML::image('forum/thread_lock.gif');
	}else echo JHTML::image('forum/icon.gif');
	echo '</td>';
	echo '<td width="400px">';
	echo '<span class="forumone">';
	echo JHTML::lnk('forum?tid='.$t->id,$t->subject);
	echo '</span>&nbsp;<span class="forumtwo">';
	$pag = &JHTML::getPagination( $t->replies+1, 0, 10, Basic::uriBase().'/forum?tid='.$t->id, 'html' );
	$pag->current = 0;
	$pag->getPagesLinks(2);
	echo '</span></td>';
	echo '<td align="center">'.$t->replies.'</td>';
	echo '<td align="center"><span class="forumtwo">';
	echo JHTML::lnk('forum?task=last&tid='.$t->id,JHTML::image('forum/lastpost.gif')).' ';
	echo JHTML::date($t->time).'<br/> od '.$t->last_username;
	//echo JHTML::profileLink($t->last_userid,$t->last_username);
	echo '</span></td>';
	echo '</tr>';
}
}else{
}
echo '</table>';
echo '&nbsp;';$this->pag->getPagesLinks(false);
if ($this->rights > 1){
	echo '<div style="text-align:right;">';
	echo JHTML::lnk('forum?task=newP&cid='.$this->cat->id,JHTML::image('forum/new_thread.gif')).' &nbsp; </div>';
}
echo '</div>';
?>