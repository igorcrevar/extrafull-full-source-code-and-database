<?php defined( '_JEXEC' ) or die( 'Restricted access' );
if (count($this->rows)){
	foreach ($this->rows as $row){
		echo '<b>';
		echo JHTML::profileLink($row->from_id,$row->username);
		echo '</b> : ';
		echo wordwrap( $row->txt, 38, "<br>", true);
		echo '<br><br>';
	}
}
else{
  echo JText::_('MB_DESCS_NONE');
}
if ($this->canWrite){?>      
  <br><textarea id="mydesc" wrap="VIRTUAL" onKeyDown="commentChanged(this,300,'chars_left2')" onKeyUp="commentChanged(this,300,'chars_left2')" rows="4" cols="30"></textarea><br>
  <?php echo JText::_('MB_CMNTS_DESCS');?><br>
  <input onclick="writeDesc(<?php echo $this->id;?>);return false;" type="button" class="button" value="<?php echo JText::_('MB_CMNTS_B');?>"/>
<?php } ?>
  