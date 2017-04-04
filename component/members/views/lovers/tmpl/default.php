<?php
defined( 'CREW' ) or die( 'Restricted access' );
?>
<script type="text/javascript" language="javascript">
function ajaxPag(ls){	
	sendAJAX("view=lovers&cnt=<?php echo $this->cnt.'&sort='.$this->sort;?>&limitstart="+ls,"ajaxmsg");
}
</script>
<div style="padding-left:100px">
<h1 class="fontMy1">Extrafull ljubavni parovi</h1>
<br />
<select onchange="window.location='<?php echo Basic::routerBase().'/index.php?option=members&view=lovers&sort=';?>'+this.value">
	<option value="0">Najnoviji</option>
	<option value="2" <?php if ($this->sort == 2) echo 'selected="yes"';?>>Po Oceni</option>
	<option value="1" <?php if ($this->sort == 1) echo 'selected="yes"';?>>Po Trajanju</option>
</select>	
</div>
<br />
<div id="ajaxmsg" style="margin-left:100px;">
<?php
	$this->loadTemplate('list');  
?>  
</div>
<br /><br />