<?php defined('CREW') or die('Restricted access');
$doc = &Document::getInstance();
$doc->setTitle('Extrafull forum - pretraga');
$user = &User::getInstance();
$myid = $user->id;

echo '<div style="padding:4px">';
echo '<span class="pathway">';
echo JHTML::lnk('forum','Forum');
echo JHTML::image( 'arrow.gif', 'alt="arrow" style="padding-right:5px"' );
echo 'Pretraga';
echo '</span><br/>';
if ( !empty($this->error) ){
	echo '<div class="error" style="text-align:center;font-weight:bold;">'.$this->error.'</div>';
}
else if (count($this->posts)){
	echo '<div>';
	echo '&nbsp;';$this->pag->getPagesLinks(2);echo ' &nbsp; ';
	echo '</div>';
	$date = date('m-d');
	$year = intval(date('Y'));
	require_once(JPATH_BASE.DS.'editor'.DS.'editor.php');	
	echo '<div id="postSt">';
	foreach ($this->posts as $p){
  	$this->loadParticle( 'forum', 'post', array( 'p' => $p, 'date' => $date, 'year' => $year, 'linkSubject' => 'true', 'noProfile' => true ) );
	}
	echo '</div>';
	echo '<div style="text-align:right;">';
	echo '&nbsp;';$this->pag->getPagesLinks(2);echo ' &nbsp; ';
	echo '</div>';
}
else{
	echo '<div class="notice" style="text-align:center;font-weight:bold;">Nema rezultata</div>';
}
echo '</div>';
?>