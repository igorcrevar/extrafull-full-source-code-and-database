<?php
defined( 'CREW' ) or die( 'Direct Access to this location is not allowed.' );

class JimOS_html {
  public static function notconnected() {
         echo _JIM_NOAUTH;
  }

  public static function showInbox ($rows, $pageNav) {
		global $JimConfig;
	?>
	
	<script>	
	function CheckAll(cb) {
		var fmobj = document.jimform;
		for (var i=0;i<fmobj.elements.length;i++) {
			var e = fmobj.elements[i];
			if ((e.name != 'allbox') && (e.type=='checkbox') && (!e.disabled)) {
				e.checked = fmobj.allbox.checked;
			}
		}
	}
	</script>
<?php
	include_once(MYBASEPATH.DS.'jim_showinbox.html.php');
// end of ShowInbox function
}

public static function showOutbox ($rows, $pageNav) {
		global $JimConfig;
	?>
	
	<script>	
	function CheckAll(cb) {
		var fmobj = document.jimform;
		for (var i=0;i<fmobj.elements.length;i++) {
			var e = fmobj.elements[i];
			if ((e.name != 'allbox') && (e.type=='checkbox') && (!e.disabled)) {
				e.checked = fmobj.allbox.checked;
			}
		}
	}
	</script>
<?php
	include_once(MYBASEPATH.DS.'jim_showoutbox.html.php');
	// end of ShowInbox function
}



public static function showHeader($page) {
	global $JimConfig;
	if ($JimConfig["Jim_css"]==_CMN_YES)
	{
		include_once(MYBASEPATH.DS.'header_buttons.html.php');
	} else {
		include_once(MYBASEPATH.DS.'header_tabs.html.php');
	}
?>
	<div style="clear: left;"></div>
	<div id="jim-body">
<?php
}

public static function showFooter() {
?>
	</div>
<?php
}


public static function viewMessage ($row) {
	require_once(JPATH_BASE.DS.'editor'.DS.'editor.php');
	global $JimConfig;
?>
<table cellspacing="0" cellpadding="5" border="0" width="100%">
	<tr>
		<th colspan="2" class="Jimtitle">
		</th>
	</tr>
	<tr class="sectiontableheader">
		<th colspan="2">
<?php 		echo _JIM_VIEWMESSAGE;?>
		</th>
	</tr>

	<tr class="sectiontableentry1">
		<th align="right" width="70"><?php echo _JIM_SUBJECT?>:</th>
		<td><?php echo stripslashes($row->subject)?></td>
	</tr>
	<tr class="sectiontableentry2">
		<th align="right"><?php echo _JIM_FROM?>:</th>
		<td>
		<?php	
		 echo $row->whofrom.'('.$row->uname.')';?>
		</td>
	</tr>
	<tr class="sectiontableentry1">
		<th align="right"><?php echo _JIM_SENTDATE?>:</th>
		<td><?php echo JHTML::_('date',$row->date);?></td>
	</tr>

	<tr class="sectiontableheader">
		<th colspan="2">
<?php		echo _JIM_MESSAGE;?>
		</th>
	</tr>

	<tr>
		<td colspan="2">
			<table cellspacing="0" cellpadding="5" border="0" width=100%>
				<tr>
					<td>
<?php					
echo editor_decode($row->message);
//echo nl2br( stripslashes($row->message))
?>
					</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr class="sectiontableentry2">
		<td align="right" colspan="2" >
			<a href="<?php echo JURI::base();?>/index.php?option=com_jim&task=new&rid=<?php echo $row->id?>" />
<?php echo JHTML::image('reply.gif','align="absmiddle"');echo _JIM_REPLY;?>				
			</a>
|
			<a href="<?php echo JURI::base();?>/index.php?option=com_jim&task=deletemsg&id=<?php echo $row->id?>" />
<?php echo JHTML::image('delete.gif','align="absmiddle"');echo _JIM_DELETE;?>	
			</a>
		</td>
	</tr>
</table>
<?php //end of viewMessage function
}


public static function viewSent ($row) {
	global $JimConfig;
	require_once(JPATH_BASE.DS.'editor'.DS.'editor.php');
?>
<table cellspacing="0" cellpadding="5" border="0" width="100%">
	<tr>
		<th colspan="2" class="Jimtitle">
		</th>
	</tr>
	<tr class="sectiontableheader">
		<th colspan="2">
<?php 		echo _JIM_VIEWMESSAGE;?>
		</th>
	</tr>

	<tr class="sectiontableentry1">
		<th align="right" width="70"><?php echo _JIM_SUBJECT?>:</th>
		<td><?php echo stripslashes($row->subject)?></td>
	</tr>
	<tr class="sectiontableentry2">
		<th align="right"><?php echo _JIM_TO?>:</th>
		<td>
		<?php	echo $row->username.'('.$row->uname.')';?>
		</td>
	</tr>
	<tr class="sectiontableentry1">
		<th align="right"><?php echo _JIM_SENTDATE?>:</th>
		<td><?php echo JHTML::_('date',$row->date);?></td>
	</tr>

	<tr class="sectiontableheader">
		<th colspan="2">
<?php		echo _JIM_MESSAGE;?>
		</th>
	</tr>

	<tr>
		<td colspan="2">
			<table cellspacing="0" cellpadding="5" border="0" width=100%>
				<tr>
					<td>
<?php					
echo editor_decode($row->message);
//echo nl2br( stripslashes($row->message))
?>
					</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr class="sectiontableentry2">
		<td align="right" colspan="2" >
			<a href="<?php echo JURI::base();?>/index.php?option=com_jim&task=leavemsg&id=<?php echo $row->id?>" />
				<?php echo JHTML::image('delete.gif','align="absmiddle"');echo _JIM_DELETE;?>
			</a>
		</td>
	</tr>
</table>
<?php //end of viewSent function
}


public static function newMessage ($row, $title, $to,$userList, $rtitle,$rmsg, $rid) {
		global $JimConfig;
?>
<script language="Javascript">
function validate(){
	forma=document.send;
	s=forma.new_title.value;
	t=document.getElementById('editor-text').value;
	if (s.length<2 || t.length<3){
	  alert('Duzina naslova ne sme biti kraca od 2 znaka, a duzina poruke kraca od 3 znaka!');
	  return false;
	} 
	else if (forma.new_to.value==""){
		alert("<?php echo _JIM_NO_REC?>");
		return false;
	}
	return true;
}
function addFriend(){
	forma=document.send;
	v=forma.friends.value;
	forma.new_to.value=forma.friends.value;
}
</script>
<form method="post" action="<?php echo JURI::base();?>/index.php" name="send" onsubmit="return validate();">
<center>	
<table>
  <tr>
     <td align="right"><b><?php echo _JIM_TO?>:</b></td>
     <td align="left">
      <?php if ($to){
        echo $to;
		echo '<input type="hidden" name="new_to" value="'.$to.'"/>';
       }else{
       echo '<input type="text"  name="new_to" size="30"/>';
       echo $userList;
       }
      ?>
      
      <?php ?>
     </td>
 </tr>
 <tr>
   <td align="right"><b><?php echo _JIM_SUBJECT?>:</b></td>
   <td align="left">
    <input type="text" class="inputbox" name="new_title" size="30" value="<?php if ($title) echo str_replace('"','&quot;',$title); else if ($rtitle) echo str_replace('"','&quot;',$rtitle);?>"/>
   </td>
 </tr>
 <tr>
   <td valign="top" align="right"><b><?php echo _JIM_MESSAGE?>:</b></td>
   <td>
   	<?php 
		require_once(JPATH_BASE.DS.'editor'.DS.'editor.php');
		editor_show('new_message',$rmsg,'',4000);
		?>   	 
   </td>
 </tr>
 <tr><td>&nbsp;</td>
   <td align="left">
    <input type="submit" class="button" value="<?php echo _JIM_SEND?>"/>
    <input type="hidden" name="task" value="sendpm">
    <input type="hidden" name="rid" value="<?php echo $rid?>">
    <input type="hidden" name="option" value="com_jim">
   </td>
  </tr>
</table>
</center>
</form>
<?php 
} 
}?>
