<?php defined( '_JEXEC' ) or die( 'Restricted access' );
  $me = &User::getInstance();
  $myid = $me->id;
  $myusername = $me->username;
?>
<?php if (!empty($this->msg)){ ?>
<div id="usrMsgs">
	<?php echo $this->msg;?>
</div>	
<?php } ?>
<div class="myWindow">
<table width="100%">
<tr id="bottommodules">	<td id="bottommodules" align="center"><b>Tebi poslati zahtevi</b></td>
<td id="bottommodules" align="center"><b>Zahtevi koje si poslao/la</b></td></tr>
<tr><td align="center" valign="top">
	<?php 
	  if (count($this->req)==0)
	    echo '<b>nema zahteva</b>';
	  else
	  {
	  	foreach($this->req as $row)
	  	{
	  		 if ( $row->type == 1 ){
	  		 		echo '&nbsp;'.JHTML::profileLink($row->id1,$row->username).' - zeli biti tvoj prijatelj';
	  		 }
	  		 else if ( $row->type == 2 ){
	  		 		echo '&nbsp;'.JHTML::profileLink($row->id1,$row->username).' - zeli biti tvoja ljubav';
	  		 }
	  		 echo '&nbsp;&nbsp;'.JHTML::lnk('index.php?option=members&id='.$row->id.'&task=pending', '[Prihvati]' );
	  		 echo '&nbsp;&nbsp;'.JHTML::lnk('index.php?option=members&id='.$row->id.'&task=pending&do=refuse', '[Odbij]' ).'<br />';
	  	}
	  }  
	?>
</td><td align="center" valign="top">
<?php 
	  if (count($this->pen)==0)
	    echo '<b>nema zahteva</b>';
	  else
	  {
	  	foreach($this->pen as $row){
	  		 if ( $row->type == 1 ){
	  		 		echo '&nbsp;'.JHTML::profileLink($row->id2,$row->username).' - potencijalni prijatelj';
	  		 }
	  		 else if ( $row->type == 2 ){
	  		 		echo '&nbsp;'.JHTML::profileLink($row->id2,$row->username).' - moguca ljubav';
	  		 }	  		
	  		 echo '&nbsp;&nbsp;'.JHTML::lnk('index.php?option=members&id='.$row->id.'&task=pending&do=cancel', '[Opozovi]' ).'<br />';
	  	}
	  }  
	?>
</td></tr>			
</table>	            	
	 <br><center><div style="width:700px;margin:0px;padding:4px;" id="bottommodules">

	  <?php
 	  if ( $this->lover ){ 	  	
			echo '<span id="lovermy">';
			echo '<span style="font-style:italic">'.JHTML::image('love.gif').JText::_('MB_LOVE').' </span>';
			echo ' sa '.JHTML::profileLink( $this->lover->id, $this->lover->name );
			echo ' &nbsp; <a href="javascript:_option=-9;sendAJAX(\'task=unlover\',\'lovermy\');">[raskini vezu]</a>';
			echo '</span>';				
 	  }
 	  
	  if ( count($this->friends) ){
	  	echo '<center><form action="'.Basic::uriBase().'/index.php">';
	  	echo '<input type="submit" class="button" value="'.JText::_('TOP_FRIENDS').'"/>';
	  	echo '<input type="hidden" name="option" value="photo"/>';
	  	echo '<input type="hidden" name="view" value="images"/>';
	  	echo '<input type="hidden" name="stat" value="6"/>';
	  	echo '</form></center><br>';
	    echo JHTML::image('friends.png').' <b> Lista prijatelja...</b>';
	    echo '<table cellspacing="2" cellpadding="2" width="100%">';
	    $i = 0;
	    foreach ($this->friends as $row)
	    {
	    	$i = ($i + 1) % 2;
	    	if ( $i == 0 ) echo '<tr bgcolor="#EaEaFF">';
	    	else echo '<tr>';	      
	      echo '<td>'.$row->name.' - <b>'.JHTML::profileLink($row->id2,$row->username).'</b>';
	  	  //if ($row->cnt>0) echo '&nbsp;&nbsp; [trenutno on-line]';
	  	  echo '</td><td>';
	  	  echo '&nbsp;&nbsp;'.JHTML::lnk('index.php?option=members&id='.$row->id2.'&task=request&type=2', '[Zahtev za vezu]' );
	  	  echo '&nbsp;&nbsp;<a href="/index.php?option=members&id='.$row->id2.'&task=unfriend">[Ukloni ga]</a>';
	  	  echo '</td>';
	  	  echo '</tr>';
	    }	    
	    echo '</table>';	    
     } ?>
     </div></center>
</div>	
