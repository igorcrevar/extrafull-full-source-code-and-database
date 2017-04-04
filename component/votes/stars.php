<?php defined('CREW') or die('Restricted access');
$doc = &Document::getInstance();
if ($doc->type == 'html'){
	$doc->addScript( Basic::uriBase().'/component/votes/stars.js' );
}

function starsDraw( $row, $typeid, $enabled  = false, $type = 2, $style = ''){
	$count = intval($row->vote_count);
	$sum = intval($row->vote_sum);
	if ( isset($row->grade) ){
		$grade = $row->grade;
	}
	else if ( $row->vote_count ){
  	$grade = (double)$row->vote_sum / (double)$row->vote_count;
	}
	else{
  	$grade = (double)0.0;
	}
	$width = (int)( ((double)$grade / 5.0) * 85.0 );
	$percent = (int)( ((double)$grade / 5.0) * 100.0 );	
?>
		<div class="vstars"<?php if (!empty($style)) echo ' style="'.$style.'"';?>>			
			<div style="padding:2px 10px;font-size:11px;">
				Ocena <span id="vote_avg<?php echo $typeid;?>"><?php echo number_format($grade,2);?></span>
				od <span id="vote_count<?php echo $typeid;?>"><?php echo $row->vote_count;?></span>				
			</div>
<?php	
if ($enabled){
?>

 			<ul id="star<?php echo $typeid;?>" class="star" onmousedown="star.update(event,this,<?php echo $type.','.$typeid;?>)" onmousemove="star.mouse(event,this,<?php echo $typeid;?>)" title="Rate This!">
  			<li id="starCur<?php echo $typeid;?>" title="<?php echo $percent;?>" class="curr" style="width:<?php echo $width;?>px;"></li>
 			</ul>
 			<div id="starUser<?php echo $typeid;?>" class="user"></div>
 			<div id="vote_sum<?php echo $typeid;?>" style="clear:both;display:none"><?php echo $row->vote_sum;?></div>

 			<div id="ajax_stars<?php echo $typeid;?>"></div>
		</div>
	
<?php	} else{ ?>
 			<ul id="star<?php echo $typeid;?>" class="star" title="Rate This!">
				<li id="starCur<?php echo $typeid;?>" title="<?php echo $percent;?>" class="curr" style="width:<?php echo $width;?>px;"></li>
 			</ul>
 			<div id="starUser<?php echo $typeid;?>" class="user"></div>
 			<br style="clear: both;">
		</div>				
<?php	}
}
?>   

