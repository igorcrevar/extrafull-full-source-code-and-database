<?php defined( 'CREW' ) or die( 'Restricted access' );
  $me = &User::getInstance();
  $event = $this->event;
  $routeBase = Basic::routerBase();
  require_once(JPATH_BASE.DS.'editor'.DS.'editor.php');
?>
<div style="margin:0 auto;padding:0px 40px;width:650px;">	
<div style="text-align:center" class="fontMy1"><?php echo $event->name;?></div>
<div style="text-align:center" class="ev-loc"><?php echo $event->date;?> <span style="color:#006B6B"><?php echo $event->location_name;?></span></div>
<div style="text-align:center">
 by 
<?php 
echo JHTML::profileLink($event->user_id,$event->username).' ';
			  	
if ( !empty($event->forum_link) ){
	echo '<span class="ev2">';
  echo '<a href="'.$event->forum_link.'">'.JText::_('FORUM_LINK').'</a>';
	echo '</span> ';
}
if ( $this->right ){
	echo '<span class="ev2">';
	echo ' <a href="'.JRoute::_('index.php?option=com_events&view=new&id='.$event->id).'">'.JText::_('CHANGE').'</a>';	  	
	echo ' <a onclick="return dlgCnf(1);" href="'.JRoute::_('index.php?option=com_events&view=new&task=del&id='.$event->id).'">'.JText::_('ERASE').'</a>';
	if ( $this->moder ){		
		$txt = $event->feautured ? '[unfeauture]' : '[feauture]';
		echo ' <a href="'.$routeBase.'/desavanja?task=feauture&id='.$event->id.'">'.$txt.'</a>';
	}
	echo '</span>';
}	 	
echo '</div>';
if (!empty($event->image) ){
	echo '<br /><center><img src="'.$routeBase.'/events/'.$event->image.'.jpg" /></center><br />';
}
?>
<div style="border-top:1px dotted #888;border-bottom:1px dotted #888;margin:3px 0px;padding:10px 0px;">
<?php
echo editor_decode($event->text);
?>
</div>

<a name="comments"></a>
<div id="who_attend">
<?php echo $this->loadTemplate('attend');?>
</div> 
<?php			
		echo '<div id="comment_start">';
		echo '<div class="headc">&nbsp;<b>Komentari ('.$event->cnt.') :</b></div>';
		$this->pag->getPagesLinks(0);
		if ( $this->right && $event->cnt){
		  echo '<form method="post" action="'.$routeBase.'/desavanja?task=delCmnt&id='.$event->id.'">';
		}
		foreach ($event->comms as $c){
    	echo '<div class="justc">';
    	if ($this->right){
    		echo '<input type="checkbox" name="cid['.$c->id.']" value="del"> ';
    	}
    	echo '<b>'.JHTML::profileLink( $c->who_id, $c->username );
    	echo '&nbsp; '.JHTML::date( $c->date ).'</b>';
    	echo '</div>';
    	echo "<blockquote>$c->comment</blockquote>";
    }
    if ( $this->right && $event->cnt ){
    	echo '<input type="submit" class="button" value="'.ERASE.'">';
	    echo '</form>';
    }	
		echo '</div>';
    if ( $me->gid >= 18 ){
			 echo '<br/><form method="post" action="'.$routeBase.'/desavanja?task=comment&id='.$event->id.'">';
 			 echo '<textarea name="comment" cols="50" rows="4" wrap="VIRTUAL" id="ucnt" onKeyUp="commentChanged(this,220,\'chars_left\')"></textarea><br/>';
 			 echo 'Znakova: <span id="chars_left">220</span><br/>';
	     echo '<input type="submit" class="button" value="'.SEND.'">';
			 echo '</form>';
		}
?>
</div>
<br />