<?php  
 defined('CREW') or die('Restricted access'); 

 echo '<h3 class="normal">'.LAST_COMMENTS.'</h3>';
if (!isset($list)) { $list = getImages(10); echo 'dd';}
$time_to_change = 4000;
if (count($list) > 0){ ?>
<div style="position:relative;width:162px;overflow:hidden;height:116px;margin:2px 0px;">
<div id="scrollCrew">
<?php
  $i = 0;  
  $list[] = $list[0];
  foreach ($list as $row)
  {  	
  	$link = Basic::routerBase().'/slika/';
  	$comm = $row->comment;
  	if (JString::strlen($comm)>40)  
  	  $comm = JString::substr($comm,0,40).'...';
  	?> 
		<div class="ssCrew">
		<a href="<?php echo $link.$row->id;?>">
		<img class="my_thumb" src="<?php echo Basic::routerBase().'/photos/'.$row->event_id.'e/'.$row->file_name;?>" onmouseover="setMe(0);" onmouseout="setMe(54);"/>
		</a><br>		
		<?php echo JHTML::_('date',$row->datetime);
		echo '<br>';
		echo JHTML::profileLink($row->user_id,JString::substr($row->username,0,16) );
		echo ' - '.$comm;
		echo '</div>';
    ++$i;
  }	
?>       
  <div style="clear:left;"></div>
</div>
</div>
<script type="text/javascript" language="javascript">	
var current_scroll=0;
var addMe=54;
var current_pic=1;
setTimeout('scrollMe()',<?php echo $time_to_change;?>);
function setMe(a){addMe=a;}
function scrollMe(){
current_scroll=current_scroll+addMe;		
pic=current_scroll/162;
if (pic==<?php echo count($list)-1;?>) current_scroll=0;
document.getElementById("scrollCrew").style.left=-current_scroll+"px";
offs=current_scroll%162;
if (offs!=0) setTimeout('scrollMe()',150);
else setTimeout('scrollMe()',<?php echo $time_to_change ?>);
}  
</script>     
<?php
}

function getImages($items_used) 
{ 	  	  
	  $db =& Database::getInstance();
		$query = 'SELECT a.comment,a.user_id,a.datetime,b.id,b.file_name,b.event_id,c.username FROM #__photo_comments AS a JOIN #__photo_images AS b ON a.image_id=b.id JOIN #__users AS c ON a.user_id=c.id ORDER by a.id DESC';
		$db->setQuery( $query,0,$items_used );
		$rows = $db->loadObjectList();	
		return $rows;
}
	
?> 
