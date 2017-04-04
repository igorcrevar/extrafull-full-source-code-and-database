<?php defined('CREW') or die('Restricted access'); 
?>
<table width="100%">
 <tr>
	 <td valign="top">
	 	<?php 
			if ($this->rows != null)
			{
				foreach($this->rows as $row)
				{ 
					?>	
					<div style="margin:3px;width:510px;border-bottom:1px dotted #000;padding:3px">
						<div class="bottommodules" style="margin:1px;padding:1px;">
							<div style="float:left;width:300px">
								<a href="<?php echo Basic::routerBase();?>/blog/blog/<?php echo $row->id;?>">	
								<?php echo JHTML::image('arrow.gif');?><span style="font-size:12px;font-weight:bold"><?php echo $row->subject;?></span></a><br/>
								<?php echo JHTML::profileLink($row->who_id,$row->username).' - '.JHTML::date($row->date);?>
							</div>
						<?php $user = &User::getInstance();
						echo '<div style="float:right;text-align:right;width:200px">';
						if ($row->comments){
							echo '<span style="font-size:9px;">Komentara: <b>'.$row->comments.'</b></span><br/>';
						}
						if ($row->vote_count){
							echo '<span style="font-size:9px;">Ocena: <b>'.$row->vote_sum.' od '.$row->vote_count.'</b></span><br/>';
						}
						if ($user->gid >= 18 && ($user->id == $row->who_id || isMod($user->id)) ){
							echo '<a href="'.Basic::routerBase().'/blog/post/'.$row->id.'">'.JText::_('CHANGE').'</a>';
							echo ' &nbsp; <a href="'.Basic::routerBase().'/blog/'.$row->id.'?task=del">'.JText::_('ERASE').'</a>';
						}
						echo '</div>';
						?>													
							<div style="clear:both;"></div>	
						</div>
						<?php echo $row->text.'... <a href="'.Basic::routerBase().'/blog/blog/'.$row->id.'">'.JText::_('DETAILED').'</a>';
						?>
				  </div><br/>	
			<?php 
			  }
			  $this->pagination->getPagesLinks();
			} ?>
  </td>
	<td width="280px" valign="top" align="justify">				 
     <?php $user = &User::getInstance();
     if ( $user->gid >= 18 )
		 echo '<b><a href="'.Basic::routerBase().'/blog/post">'.JText::_('NEW_ONE').'</a></b><br/><br/>';
       $this->calendar->show();
		 ?>	
		 <div class="photo_hd"><?php echo JText::_('CATS');?></div>		 
<?php	 
			$types = explode(',',JText::_('TYPES'));
      array_unshift( $types, 'Sve');
      echo '<div style="width:260px;">';
      for ($i = 0; $i < count($types); ++$i){
      	echo '<div style="float:left;width:130px;height:20px;font-size:11px">';
      	echo JHTML::image('arrow.gif');
      	if ($this->type == $i) echo $types[$i];
      	else echo '<a href="'.$this->lnk.'type='.$i.'">'.$types[$i].'</a>';
      	echo '</div>';
      	if (($i+1) % 2 == 0) echo '<div style="clear:both;"></div>';
      }
      echo '</div>';
      ?>
   </td>
 </tr>
</table>  
