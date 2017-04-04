<?php defined( 'CREW' ) or die( 'Direct Access to this location is not allowed.' );?>
<form method="post" action="<?php echo JURI::base();?>/index.php" name='jimform'>
	<table cellspacing="1" cellpadding="3" border="0" width="100%">
		<tr>
			<th colspan="5" class="Jimtitle">
			</th>
		</tr>
		<tr>
			<th class="sectiontableheader"><input TYPE="CHECKBOX"  name="allbox"   onClick="CheckAll()"></th>
			<th class="sectiontableheader"><?php echo _JIM_STATUS?></th>

			<th class="sectiontableheader" width=100% align="left"><?php echo _JIM_SUBJECT?></th>
			<th class="sectiontableheader"><?php echo _JIM_FROM?></th>
			<th class="sectiontableheader" ><?php echo _JIM_SENTDATE?></th>
		</tr>	
<?php
	if ( count($rows) < 1 ) { ?>
		<tr class="sectiontableentry1">
			<td colspan="5" align="center">
				<?php echo _JIM_NO_MSG; ?>
			</td>
		</tr>
<?php
	} else {
	$i = 0;
  $k = 0;
		foreach ($rows as $row) {
		      $subject = "\n\n". str_replace( "\'" , "'", $row->subject);
?>
		<tr class="sectiontableentry<?php echo $k+1; ?>">
			<td>
				<input type="checkbox" name="delete[<?php echo $row->id?>]" value="del">
			</td>
			<td align=center>
				<img src="<?php echo JURI::base().'component/jim/images/'.$row->readstate.".png"?>" border="0">
			</td>
			<td>
				<a href="<?php echo JURI::base();?>/index.php?option=com_jim&task=view&id=<?php echo $row->id?>" /><?php echo $subject?></a>
			</td>
			<td>
				<?php echo JHTML::profileLink($row->from_id,$row->whofrom);?>
			</td>
			<td  align=center nowrap>
<?php		echo JHTML::_('date',$row->date); ?>
			</td>				
		</tr>

<?php
			$k=1-$k;
			$i++;
		}
	}
?>  
		<tr class="sectiontableheader" >
			<td colspan="5">
				<input type="button" name="delme" class='button' value="<?php echo _JIM_DELETE_SEL?>" onclick="document.jimform.task.value='deletemsgs';document.jimform.submit();">
		   </td>
		</tr>

		<tr>
			<td colspan="5" align="center">
<?php
	echo $pageNav->getPagesLinks();
?>
			</td>
		</tr>

	</table>
	<input type="hidden" name="option" value="com_jim">
	<input type="hidden" name="task" value="inbox">
</form>

