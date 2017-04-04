<?php defined('_JEXEC') or die('Restricted access'); ?>
<div style="margin:20px 20px">
	<b>Uploadujem u galeriju sa id-om <?php echo $this->event_id;?></b>
<form id="image_form" action="index.php" method="post" enctype="multipart/form-data" >
	<table>
		  <tr>
		  	<td>Odaberi sliku :</td>
		  	<td><input type="file" name="upload_file" id="upload_file" onchange="chooseFile();" value="Izaberi..." /></td>
			</tr>						
		  <tr>
		  	<td>Naziv slike :</td>
		  	<td><input type="text" name="file_name" maxlength=40 /></td>
			</tr>						 					 					
		  <tr>
		  	 <td>&nbsp;</td>
		  	 <td><input class="button" type="submit" value="Posalji!" id="submiter"  disabled /></td>
		 </tr>																 
  </table>
  <input type="hidden" name="event_id" value="<?php echo $this->event_id;?>" />
  <input type="hidden" name="private" value="<?php echo $this->private;?>" />
  <input type="hidden" name="task" value="imagesUpload" />
	<input type="hidden" name="option" value="com_photo" />
</form>
 <span style="color:#FF0000;font-size:12px;"><b>NAPOMENA: Nije dozvoljeno uploadovati slike skinute sa drugih sajtova (koje sadrze watermark).<br><br>Slike moraju biti manje od 550 KiloBajta. Moraju biti u JPG,PNG ili GIF formatu.</b></span>
 <br><br>
</div>		  
<script type="text/javascript" language="javascript">
function chooseFile(){
  el = document.getElementById("upload_file");
  val = el.value.toLowerCase();
  arr = [".jpg",".jpeg",".gif",".png"];
  isZip = false;
  for (i=0;i<arr.length && !isZip;++i)
    isZip = val.indexOf(arr[i]) != -1;
  el = document.getElementById('submiter');
  el.disabled = !isZip;
  return;
}
</script>    