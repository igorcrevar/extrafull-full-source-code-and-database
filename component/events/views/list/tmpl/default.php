<?php defined( 'CREW' ) or die( 'Restricted access' );
  $me = &User::getInstance();
  $myid = $me->id;
  $routeBase = Basic::routerBase();
  require_once(JPATH_BASE.DS.'editor'.DS.'editor.php');
?>
<?php
if ($this->format == 'html'){
 echo '<div>';
 echo '<div id="events_block">';
}		
echo '<br>';
foreach ( $this->rows as $row ){		
  	echo '<div class="ev">';
  	echo '<div class="ev-f">';
  	if ( !empty($row->image) ){
  		//$row->image = 'def'; //default;
  		echo '<a href="'.$routeBase.'/desavanja/event/'.$row->id.'">';
  		echo '<img src="'.$routeBase.'/events/'.$row->image.'t.jpg" />';
	  	echo '</a>';
  	}
  	else {
  		echo '&nbsp;';
  	}
		echo '</div>';
		echo '<div class="ev-s">';
			echo '<span class="ev-loc">';
  		echo '<a href="'.$routeBase.'/desavanja/event/'.$row->id.'">';
  		echo $row->name;
  		echo '</a>';
  	echo '</span><br />';
  	echo '<span class="ev-loc">'.$row->location_name.'</span> ';
		echo '<span class="ev-date">';
		echo JHTML::date($row->date,'date2');
  	echo '</span><br />';
  		echo '<a href="'.$routeBase.'/desavanja/event/'.$row->id.'">';
  		echo ' [detaljnije]';
  		echo '</a>';
  	if ( MyRights::isEventsModerator($myid) ){ 	
			echo ' <a onclick="return dlgCnf(1);" href="'.JRoute::_('index.php?option=com_events&view=new&task=del&id='.$row->id).'">'.JText::_('ERASE').'</a>';
		}
  	echo '</div>';  	
  	echo '<div style="clear:left"></div>';
  	echo '</br />';
  	echo '</div>';
}
			  
if ($this->format == 'html'){?>
	</div>
	<div id="calend_block">	
			<?php
			echo '<input type="button" class="button" style="width:130px" onclick="events_call(\'no\');return false;" value="'.JText::_('NEXT_EVENTS').'"/>';			
			echo ' &nbsp; <input type="button" class="button" style="width:130px" onclick="events_call(\'yes\');return false;" value="'.JText::_('PREV_EVENTS').'"/>';
			echo '<br><br>';
			$this->cal->show();
			if (MyRights::canChangeEvents())
			echo '<br><a href="'.JRoute::_('index.php?option=com_events&view=new').'"><b>'.JText::_('NEW_EVENT').'</b></a>';
			?>
			<br><br>
	 </div>	
	 <div style="clear:left;"></div>
</div>	 
<?php }	?>
			
