<?php
defined( 'CREW' ) or die( 'Restricted access' );?>
<?php if ($this->first){?>
<script type="text/javascript" language="javascript">
var event_id=0;
</script>
<div id="s_images">
<?php }?>

<center>
	<?php $tmp = explode(',',JText::_('CHOOSE_PRIVATE'));
	echo '<b>'.$tmp[0].'</b>';?>
	<SELECT id="gender">
		<OPTION value="4" SELECTED ><?php echo $tmp[3];?></OPTION>
		<OPTION value="24"><?php echo $tmp[2];?></OPTION>
		<OPTION value="14"><?php echo $tmp[1];?></OPTION>
  </SELECT>	
	<input type="button" class="button" onclick="ajaxPag(0,document.getElementById('gender').value);" value="<?php echo $tmp[4];?>"/>
</center>
<?php if ($this->first)
 echo '</div>';
 	?>