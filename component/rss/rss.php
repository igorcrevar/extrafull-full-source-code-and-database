<?php
/*=============================================================================
|| ##################################################################
||	Igor Crevar Extrafull
|| ##################################################################
||
||	Copyright		: (C) 2007-2009 Igor Crevar
||	Contact			: crewce@hotmail.com
||
||	- Extrafull and all of its source code and files are protected by Copyright Laws.
||
||	- You can not use any of the code without Igor Crevar agreement
||
||	- You may also not remove this copyright screen which shows the copyright information and credits for Extrafull (Igor Crevar).
||
||	- Igor Crevar Extrafull is NOT a FREE software
||
|| ##################################################################
=============================================================================*/
defined('_JEXEC') or die('Restricted access'); 
require_once(BASE_PATH.DS.'library'.DS.'simplepie'.DS.'simplepie.php');
/*class Tmp{
function getit(){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://www.astro-art.net/rssdnevni.xml');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($ch);
curl_close($ch);
return $output;
}
}*/
$simplepie = new SimplePie('http://www.astro-art.net/rssdnevni.xml',BASE_PATH.DS.'cache',60*60+3);
$simplepie->handle_content_type();
if ($simplepie->data) {
	echo '<div style="padding:4px">';
	$rss = $simplepie;
	$d = &Document::getInstance();
	$d->setTitle('Extrafull horoskop');
	echo '<a target="_blank" href="http://www.astro-art.net/rssdnevni.xml"><span class="fontMy1">'.$rss->get_title().'</span></a>';
	$items = $rss->get_items();?>
	<ul style="width:600px;text-align:justify">
	<?php 
	  $znaci = array('01-21','02-20','03-21','04-21','05-22','06-23','07-24','08-24','09-24','10-24',
	  '11-23','12-22');	  
	  $sess = &Session::getInstance();
	  if ($sess->userId>0){
	  	$db = &Database::getInstance();
	  	$query = 'SELECT birthdate FROM #__fb_users WHERE userid='.$sess->userId;
	  	$db->setQuery($query);
	  	$birth = $db->loadResult();
	  	$birth = substr($birth,5);
	  	$znak = 11;
	  	for ($i = 0; $i < 11; ++$i){
	  		if ($birth >= $znaci[$i] && $birth < $znaci[$i+1]){
	  			$znak = $i;
	  		}
	  	}	 
  		if ($znak >= 0 && $znak <= 1){
  			$znak = 11 - $znak;
  		}
  		else{
  			$znak -= 2;
  		}		  	
	  	$lnk = $items[$znak]->get_title();					
	  	echo '<div style="padding:5px;border:1px dotted #000;">';
			echo '<b>'.JString::substr( $lnk,0,JString::strpos($lnk,' -')).'</b>';
			echo '<br/>';
			$text = $items[$znak]->get_description();
			echo str_replace('&apos;', "'", $text);
			echo '</div><br><br>';
	  }
	  $i = -1;
		foreach ( $items as $item ) :  ++$i; if ($i == $znak) continue;?>
			<li>
					<?php
					$lnk = $item->get_title();					
					echo '<b>'.JString::substr( $lnk,0,JString::strpos($lnk,' -')).'</b>';
					?>
				<br />
				<?php $text = $item->get_description();
					echo str_replace('&apos;', "'", $text);
				?>
				<br />
				<br />
			</li>
	<?php endforeach; ?>
</ul>
  </div><?php
}		
?>