<?php defined( 'CREW' ) or die( 'Restricted access' );
$event = $this->event;
$user =& User::getInstance();
require_once(JPATH_BASE.DS.'editor'.DS.'editor.php');
?>
<form action="<?php echo Basic::uriBase();?>/index.php" method="POST" onsubmit="return isOk(true)" enctype="multipart/form-data" >
	<div id="add_form">
		<b><?php echo JText::_('LOCATION');?> (*)</b><br>
		<select name="l_name">
		<?php for ($i=0;$i<count($this->locs);++$i){	
			$row = $this->locs[$i];
			echo '<option value="'.$row->name.'"';
			if ($event != null && $row->name==$event->location_name) echo ' SELECTED';
			echo '>'.$row->name.'</option>';
  	}?>
		</select>
		<?php echo '<br><a href="'.JRoute::_('index.php?option=com_events&view=new&layout=locations').'">'.JText::_('NEW_LOCATION').'</a>';?>
		<br><br>
		<b><?php echo JText::_('DATE');?> (*)</b><br>
		<?php
	  if ($event != null)
			list($year,$month,$day) = explode('-',$event->date);
		else{
			list($year,$month,$day) = explode('-',date('Y-m-d'));
			$month = intval($month);
			$day = intval($day);
		}
		echo '<select id="dday" name="day">';
		for ($i=1;$i<=31;++$i){
			echo '<option value="'.$i.'"';
		  if ($i == $day) echo ' SELECTED';
		  echo '>'.$i.'</option>';
		}  
	  echo '</select>. ';
	  echo '<select id="mmonth" name="month">';
  	for ($i=1;$i<=12;++$i){
		  echo '<option value="'.$i.'"';
		  if ($i == $month) echo ' SELECTED';
		  echo '>'.$i.'</option>';
		}  
	  echo '</select>. ';
	  echo '<select id="yyear" name="year">';
  	for ($i=$year;$i<$year+2;++$i){
		  echo '<option value="'.$i.'"';
		  if ($i == $year) echo ' SELECTED';
		  echo '>'.$i.'</option>';
		}  
	  echo '</select>.';
	?>
	<br><br>
	<b><?php echo JText::_('NAME');?> (*)</b><br />
  <input id="event_name" type="text" name="name" <?php if ($event != null) echo 'VALUE="'.$event->name.'"';?> size="50"/>
  <br /> <br />
	<b>Flajer razumnih dimenzija(max 1024*768); smanjice se na 500x375 ili 375x500 svakako</b><br />
  <input type="file" name="image" />
	<br><br>
	<b>Opis</b><br>
  <?php editor_show('text',$event ? $event->text : '','',800,54,8);?>
  <br>
	<b><?php echo JText::_('FORUM_LINK_FIELD');?></b><br>
  <input type="text" name="forum_lnk" <?php if ($event) echo 'VALUE="'.$event->forum_link.'"';?> size="50"/>
	<br><br>
	<input type="submit" class="button" value="<?php echo JText::_('ADD_ME');?>"/> 
 <?php 
  if ($event!=null)
    echo '<input type="hidden" name="id" value="'.($event ? $event->id : 0).'"/>';?>
 	<input type="hidden" name="task" value="addNew"/> 
 	<input type="hidden" name="option" value="com_events"/> 
 	<br><br>
 	<h3 style="text-align:center"><?php echo JText::_('MUST_FIELDS');?></h3>
 	<br><br>
</div>
</form>	
