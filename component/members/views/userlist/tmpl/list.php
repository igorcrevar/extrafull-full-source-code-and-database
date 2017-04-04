<?php defined( 'CREW' ) or die( 'Restricted access' );
require_once BASE_PATH.DS.'block.php';
?>
<script language="javascript" type="text/javascript">
function requestdone(){	
	if (http_request.readyState == 4){ 
    if (http_request.status == 200 || window.location.href.indexOf("http")==-1){ 
    	 _label.innerHTML=http_request.responseText;	
		}
	}		
}		
</script>
<div style="margin-left:100px;width:600px;overflow:hidden">
<?php foreach ($this->rows as $row) : ?>
	<div class="bottommodules" style="height:101px">
		<div class="flLeft">
<?php if (strlen($row->avatar) > 0) 
				echo JHTML::profileLink($row->id,'<img src="'.Basic::routerBase().'/avatars/'.$row->avatar.'" />');
			else echo '<img src="'.Basic::routerBase().'/avatars/s_nophoto.jpg">';
?>
		</div>	
		<div style="float:left;width:260px;overflow:hidden">
			<div class="<?php if ($row->gender == 2)echo 'fe';?>maleC">
				<?php echo $row->name;?>
				<div><?php echo JHTML::profileLink($row->id,$row->username);?></div>
   			 	<?php 
   			 	echo '<div>Datum rođenja : '.JHTML::date($row->birthdate,'date').'</div>';
    		  echo '<div>Lokacija: '.$row->location.'</div>';
    		  echo 'Poslednja poseta: '.JHTML::date($row->lastvisitDate);
    		  ?>
			</div>
		</div>
	<?php if ( $this->myfriends ){ ?>
		<div style="float:left;text-align:center;width:180px"><?php
				echo '<br /><br />';
 				echo JHTML::lnk('index.php?option=members&id='.$row->id.'&task=request&type=2', '[Zahtev za vezu]' );
 				echo ' &nbsp; ';
 				echo JHTML::lnk('index.php?option=members&id='.$row->id.'&task=unfriend', '[Ukloni ga]' )
 				?>
		</div>		
	<?php } else{
			echo '<div style="float:left;text-align:center;width:180px;padding-top:20px" id="userblock'.$row->id.'">';
			echo UserBlocks::show( $row->id, 0, false );
			echo '</div>';
		}
	?>
		<br style="clear:both;">		
	</div>	
<?php endforeach ?>
<br />
<?php $this->pagination->getHeader();$this->pagination->getPagesLinks();?>
<br />
</div>
