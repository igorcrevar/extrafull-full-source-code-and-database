<?php defined( 'CREW' ) or die( 'Restricted access' );?>
<form action="<?php echo Basic::uriBase();?>/index.php" method="POST" onsubmit="return isOk(false)">	
 <div id="add_form">
	  <b><?php echo JText::_('NAME');?> (*)</b><br>
	  <input id="loc_name" type="text" name="l_name" value="<?php echo $this->loc ? $this->loc->name : '';?>" /> 
		<br><br>
		<b><?php echo JText::_('ADDRESS');?></b><br>
		<input type="text" name="l_street" value="<?php echo $this->loc ? $this->loc->address : '';?>"/>
		<br><br> 
		<input type="submit" class="button" value="<?php echo JText::_('ADD_ME');?>"/>
		<br><br>
		<h3 style="text-align:center"><?php echo JText::_('MUST_FIELDS');?></h3> 
 </div>
 <input type="hidden" name="task" value="addLocation"/> 
 <input type="hidden" name="option" value="com_events"/>
</form>	
