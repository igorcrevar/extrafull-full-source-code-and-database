<?php
defined( 'CREW' ) or die( 'Restricted access' );?>
<?php if ($this->first){?>
<div id="s_images">
<?php }?>
<center>
<?php 
if (($this->stat>=0 && $this->stat<=3) || $this->stat >= 7) 
   echo '<span class="fontMy1">'.JText::_('PH_IMGS_HD'.$this->stat).'</span>';
?>
<br><br>
<?php 
if ($this->stat==0){
  $user = & User::getInstance();
  $user_id = $user->id;
}
if ($this->imgs && count($this->imgs)>0){
echo '<table>';
  $i = 0;
  foreach($this->imgs as $row){
		$link = JRoute::_('index.php?option=com_photo&view=image&id='.$row->id);
		$src = Basic::routerBase().'/photos/'.$row->event_id.'e/'.$row->file_name;
		if (($i+5)%5 == 0) echo '<tr>';
		echo '<td align="center" width="120px">';  
		echo '<a href="'.$link.'">';
  	echo '<img src="'.$src.'" class="my_thumb"/></a><br>';
  	if ($this->stat==3) {
    	echo '<a href="'.$link.'">'.JString::substr($row->username,0,20).'</a>';
  	}  
  	else{ 
    	echo '[pregleda:'.$row->number_of_views.']<br>';
    	if ($this->stat==2 || $this->stat == 8) echo '[ocena:'.$row->grade.']';
	  	else echo '[komentara:'.$row->comments.']';
	  	if ($this->stat==0 && $user->id == $row->user_id)
	  			echo '<br><a href="'.JRoute::_('index.php?option=com_photo&task=delFav&i_id='.$row->id).'">'.JText::_('OBRISI_SLIKU').'</a>';
	 	} 
		echo '</td>';
  	++$i;
  	if ($i%5==0) echo '</tr>'; 
  } 
  if ($i%5!=0) echo '<td width="120px">&nbsp;</td></tr>';
echo '</table><br>';
$this->pagination->getPagesLinks();echo '<br/>';
}
else echo '<b>Ne postoji ni jedna slika</b>';
?>	
</center>
<?php if ($this->first)
 echo '</div>';
 	?>