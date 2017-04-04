<?php 
$this->pagination->getPagesLinks();
echo '<br />';
foreach($this->imgs as $row){ ?>
	<div class="flLeft" style="height:114px;padding:0px 0px">
  	<a href="javascript:thumbChoose('<?php echo $row->id.'-'.$row->file_name ?>');">
  	<img src="<?php echo $this->mypath.$row->file_name ?>" class="my_thumb" />
  	</a> 
	  <?php echo '[pregleda:'.$row->number_of_views.']<br>';
  	echo '[komentara:'.$row->comments.']';?>
	</div>			
<?php } 
echo '<br />';
echo '<div style="clear:both"></div>';
$this->pagination->getPagesLinks();
?>