<?php
defined( 'CREW' ) or die( 'Restricted access' );
require_once(BASE_PATH.DS.'component'.DS.'votes'.DS.'stars.php' );
if ( count($this->rows ) ){
	foreach ($this->rows as $row){ 
		if ( empty($row->avatar1) ) $row->avatar1 = 's_nophoto.jpg';
		if ( empty($row->avatar2) ) $row->avatar2 = 's_nophoto.jpg';
	?>
	<div class="photo_event" style="padding:4px;width:500px;height:120px;overflow:hidden;text-align:center">
		<div style="float:left;width:130px;text-align:center">
			<?php echo JHTML::profileLink( $row->id1, '<img src="'.Basic::routerBase().'/avatars/'.$row->avatar1.'" />' );?>
			<br />
			<?php echo JHTML::profileLink( $row->id1, $row->name1 ); ?>
		</div>
		<div style="float:left;width:130px;text-align:center">
			<?php echo JHTML::profileLink( $row->id2, '<img src="'.Basic::routerBase().'/avatars/'.$row->avatar2.'" />' );?>
			<br />
			<?php echo JHTML::profileLink( $row->id2, $row->name2 );?>
		</div>
		<div style="float:left;">
			<?php
			echo '<br /><div style="font-style:italic">Datum kad su se smuvali:</div> '.JHTML::date( $row->time, 'date2').'<br />';
			$days = (int)( (time() - $row->time) / (60*60*24) );
			$hrs = (int)( (time() - $row->time) / (60*60) );
			echo '<span style="font-style:italic">Zajedno:</span> ';
			echo $days.' dana i '.$hrs.' sati';
			echo '<br />';
			starsDraw($row, $row->id1, $this->user > 30,2, 'padding-left:100px' );
			?>
		</div>
		<br style="clear:both" />	
 	</div>  
 	<?php
  }
  $this->pagination->getHeader();$this->pagination->getPagesLinks(2);
}