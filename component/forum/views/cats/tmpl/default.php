<?php defined('CREW') or die('Restricted access'); 
echo '<div style="padding:4px;">';

$user = &User::getInstance();
if ( $user->gid >= 18 ){
	echo '<br /><div class="forumtwo" style="text-align:center">';
	echo JHTML::lnk('forum?show=1', '[moje teme]');
	echo ' &nbsp; ';
	echo JHTML::lnk('forum?show=2', '[moje zapocete teme]');
	echo ' &nbsp; ';
	echo '</div><br />';
}

$this->loadParticle( 'forum', 'search' );

echo '<table class="forum" cellspacing="1">';
echo '<tr><td class="forumtitle" colspan="2">Forum</td>';
echo '<td class="forumtitle2" align="center" width="300px">Poslednja poruka</td>';
echo '<td class="forumtitle" colspan="2">Tema/Poruka</td></tr>';
foreach ($this->cats as $cat){
	echo '<tr>';
	echo '<td align="center" valign="center" width="40px">';
	$icon = ($cat->icon != '') ? $cat->icon : 'icon.gif';
	echo JHTML::image('forum/'.$icon);
	echo '</td>';
	echo '<td width="400px">';
	echo '<span class="forumone">';
	echo JHTML::lnk('forum?cid='.$cat->id,$cat->name);
	echo '</span><br/>';
	echo '<span class="forumtwo">'.$cat->description.'</span><br/>';
	$moders = explode(',,,,,',$cat->moderators);
	$i = 0;
	foreach ($moders as $mod){
		list($id,$name) = explode('-',$mod);
		if ($i > 0) echo ', ';
		echo JHTML::profileLink($id,$name);
		++$i;
	}
	echo '</td>';
	if ( isset($cat->time) ){
		echo '<td align="center"><span class="forumtwo">';
		echo JHTML::lnk('forum?pid='.$cat->last_id,$cat->subject).'</span><br/>';
		echo JHTML::date($cat->time).' od '.$cat->last_username;
		//echo JHTML::profileLink($cat->last_userid,$cat->last_username);
	}
	else{
		echo '<td align="center">';
		echo '--';
	}
	echo '</td>';
	echo '<td align="center">';
	echo $cat->numTopics.'/'.$cat->numPosts;
	echo '</td>';
	echo '</tr>';
}
echo '</table>';
echo '</div>';
?>