<?php defined( 'CREW' ) or die( 'Restricted access' );
  $me = &User::getInstance();
  $attend = $this->attend;
  $my_status = false;
  $gender = 4;
  echo '<h3 class="normal">'.JText::_('ATTENDERS').'</h3>';
	if ( count($attend) ){
    foreach ( $attend as $att ){
			if ( $att->gender != $gender ){
				if ( $gender != 4 ) echo '<br>';
				if ($att->gender == 2) echo '<span class="femaleC">'.JText::_('WOMAN').'</span>';
				else echo '<span class="maleC">'.JText::_('MAN').'</span>';
				$gender = $att->gender;
			}
			else {
				echo ' ,'; 
			}
			if ( $att->id == $me->id ){
				 $my_status = true;
			}
			echo '&nbsp; '.JHTML::profileLink( $att->id, $att->username );
		} 
	}	
	else{
		 echo '<center>Niko :(</center>'; 
	}
	$ok = $me->gid >= 18  &&  isset($this->event) &&  date('Y-m-d') <= $this->event->oldDate; 
	if ( $ok ){
		echo '<br><center id="attendSend">';
		$txt = !$my_status ? JText::_('MY_ATTEND_ADD') : JText::_('MY_ATTEND_REMOVE');
		echo '<input onclick="attend('.$this->event->id.');return false;" type="button" class="button" value="'.$txt.'"/>';
		echo '</center>';
  }
?> 