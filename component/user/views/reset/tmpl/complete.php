<?php defined('_JEXEC') or die; ?>

<div style="padding:20px;">
<div style="font-weight:bold">
	<?php echo JText::_('RESET_YOUR_PASSWORD'); ?>
</div>

<form action="index.php?option=com_user&task=completereset" method="post" class="josForm form-validate">
	<table cellpadding="0" cellspacing="2" border="0" width="100%" class="contentpane">
		<tr>
			<td>&nbsp;</td>
			<td height="40">
				<p><?php echo JText::_('RESET_PASSWORD_COMPLETE_DESCRIPTION'); ?></p>
			</td>
		</tr>
		<tr>
			<td align="right" width>
				Nova lozinka:
			</td>
			<td>
				<input id="password1" name="password1" type="password" class="required validate-password" />
			</td>
		</tr>
		<tr>
			<td  align="right">
				Potvrda nove lozinke :
			</td>
			<td>
				<input id="password2" name="password2" type="password" class="required validate-password" />
			</td>
		</tr>
		<tr>
			<td  align="right">&nbsp;
			</td>
			<td>
<?php
$sess = &Session::getInstance();	
$token = $sess->get('user.reset.token', '');
?>
	<input type="hidden" name="<?php echo $token;?>" value="1" />
	<input type="submit" class="button" value="<?php echo SEND;?>" /> 
			</td>
		</tr>
	</table>

</form>
</div>