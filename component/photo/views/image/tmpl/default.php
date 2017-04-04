<?php defined('CREW') or die('Restricted access');
	global $mainframe;
echo '<div style="text-align:center">';
  if ( isset($this->image_name) && $this->image_name != '') 
    echo '<b>'.$this->image_name.'</b><br />';
  if ( $this->change )
  {
  	  $path = Basic::routerBase().'/index.php?option=photo&view=image&task=change&id='.$this->image_id;
  	  echo '<form action='.$path.' method="POST">';
  	  echo '<input type="text" name="file_name" maxlength=40 value="'.$this->image_name.'"/>';
  	  echo ' <input class="button" type="submit" value="Promeni ime!" />';
  	  echo '&nbsp; <a href="'.$path.'">'.JText::_('OBRISI_SLIKU').'</a>';
  	  echo '</form>';
  }           
  if (!$this->write  &&  $this->read) 
     echo '<div class="bottommodules">'.JText::_('PH_CANT_WRITE').'</div>';
?> 
<br />
<?php 
if ($this->read){
	$this->vote_stat->avg = number_format($this->vote_stat->avg,2);
	$width = (int)( ((double)$this->vote_stat->avg / 5) * 85 );
	$percent = (int)( ((double)$this->vote_stat->avg / 5) * 100 );
	if ( $this->write ){
	?>
	<div>
		<div id="stars" style="float:left;width:290px">
			
			<div style="padding:2px 20px;font-size:11px;">
				<span id="vote_avg"><?php echo number_format($this->vote_stat->avg,2);?></span>
				od <span id="vote_count"><?php echo $this->vote_stat->count;?></span>				
			</div>
 			<ul id="star" class="star" onmousedown="star.update(event,this)" onmousemove="star.mouse(event,this)" title="Rate This!">
  			<li id="starCur" title="<?php echo $percent;?>" class="curr" style="width:<?php echo $width;?>px;"></li>
 			</ul>
 			<div id="starUser" class="user"></div>
 			
 			<div id="vote_sum" style="clear: both;display:none"><?php echo $this->vote_stat->sum;?></div>
		</div>
		<div style="float:left;">
			<div style="font-style:italic">Uploadovana pre: 
			<?php echo JHTML::_time($this->image_time);?>
			</div>
			<img src="<?php echo $mainframe->getImageDir();?>rating_star.png"/>
			<span id="favourite">
			<?php
			if (!$this->favourite){
    		echo '<a href="javascript:ajaxReq(6)">';
  	  	echo JText::_('PH_ADD_TO_FAVOURITE').'</a>';
    	} else { echo '<img src="'.$mainframe->getImageDir().'rating_star.png"/>'; }
			?>
			</span>	
			<img src="<?php echo $mainframe->getImageDir();?>rating_star.png"/>
	  </div>	
		<br style="clear: both;">
	</div>	

	<div class="bottommodules"><?php echo JText::_('PH_COMMENT_WARN');?></div>
  <div style="float:left;padding:10px">
	<textarea cols="40" rows="4" wrap="VIRTUAL" id="user_comment" onKeyUp="commentChanged(this,220,'chars_left')" onKeyDown="commentChanged(this,220,'chars_left')"></textarea>
  </div>	
  <div style="float:left">  	
  <br/>Ostalo <span id="chars_left">220</span> znakova<br/>
	<input type="button" class="button" value="<?php echo JText::_('POSALJI');?>" onclick="ajaxReq(2)" />
  </div>
  <br style="clear:left">
	<?php	
	}
	else{ ?>
		<div id="stars">
 			<ul id="star" class="star" title="Rate This!">
				<li id="starCur" title="<?php echo $percent;?>" class="curr" style="width:<?php echo $width;?>px;"></li>
 			</ul>
 			<div id="starUser" class="user"></div>
 			<br style="clear: both;">
		</div>				
	<?php
	}
?>   
</div>
<div class="headc">&nbsp;<b>Komentari (<span id="comm_cnt"><?php echo count($this->comments);?></span>) :</b></div>
<?php echo $this->loadTemplate('comments'); } ?>