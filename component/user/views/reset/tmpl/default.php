<?php defined('CREW') or die; ?>
<div style="padding:20px">
<div style="font-weight:bold">
	<?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?>
</div>
<form action="index.php?option=user&task=requestreset" method="post" class="josForm form-validate">
<table>
		<tr>
			<td height="40">
				<label for="email" class="hasTip">E-mail adresa: </label>
			</td>
			<td>
				<input id="email" name="email" type="text" />
			</td>
			<td>
	<input type="submit" class="button" value="<?php echo SEND;?>"/>
			</td>
		</tr>
	</table>
	
</form>
</div>