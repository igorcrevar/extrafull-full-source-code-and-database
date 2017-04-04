<?php defined('CREW') or die('Restricted access'); ?>
<script type="text/javascript" language="javascript">
var selectedImage=<?php echo JRequest::getInt('image_id',-1);?>;
var image_file_name='';
var jpath='<?php echo $this->mypath;?>';
var event_id=<?php echo $this->event_id;?>;
</script>

<div style="width:100%;overflow:hidden;">
  <div style="text-align:center;float:left;width:594px">
  		 <?php if ($this->event->published==1){ ?>
	     <span class="fontMy1"><?php echo $this->event->location;?></span>         	 			 
	     &nbsp;- <span class="fontMy2"><?php echo $this->e_date;?></span>
	     <?php } else echo '<span class="fontMy1">Privatna galerija</span>';?>
       <?php if ($this->event->name != '' ) echo ' - '.$this->event->name; ?>&nbsp;
       <b>by 
       <?php echo JHTML::profileLink($this->event->a_id,$this->event->username);?>
       </b>
			 <br>
		   <a href="javascript:ViewFullImage()"> 
		     <img name="defPic" id="mid_image" src="<?php global $mainframe;echo $mainframe->getImageDir();?>default.jpg" />
			 </a>
			 <div id="ajax_msg"></div>
			 <div id="image_info"></div>
	</div>
	<div style="text-align:center;float:left;width:220px">		 
		<br />
		 <?php    
		   echo '<div id="s_images" style="width:100%">';
		   $this->loadTemplate('images');
		   echo '</div>';
			 $text = $this->event->description;
			 if ( $text != '' )
			   echo '<div>'.$text.'</div><br>';
			 ?>						 
	</div>
</div>
