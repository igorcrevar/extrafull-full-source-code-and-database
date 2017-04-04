<?php defined('CREW') or die('Restricted access'); ?>
<div style="text-align:center;margin:20px 10px">
	<form action="<?php echo Basic::routerBase();?>/index.php?option=members&task=ip" method="post">
		IP: <input type="text" name="ip" />
		&nbsp;
		<input type="submit" value="Dodaj" class="button" />
	</form>	
	<div class="bottommodules">
		Banovan IP traje 4 dana
	</div>	
	<table>
		<tr>
			<td class="bottommodules" width="103px">IP</td>	
			<td class="bottommodules" width="200px">Vreme</td>	
			<td class="bottommodules" width="450px">Dodao</td>	
		</tr>	
		<?php foreach ($this->ips as $ip){
			echo '<tr>';
			echo '<td>'.$ip->ip.'</td>';
			echo '<td>'.JHTML::date($ip->time).'</td>';
			echo '<td>'.JHTML::profileLink($ip->mod_id,$ip->username.'/'.$ip->name).'</td>';
			echo '</tr>';
		}
		?>
	</table>
	<br /><br />	
</div>	

