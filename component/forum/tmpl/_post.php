<?php defined('CREW') or die('Restricted access'); 
?>
<table class="forum" width="800px" cellspacing="1">
	<tr>
		<td width="200px" align="left" class="forumtitle">
			<span style="color:#333"><?php echo JHTML::date($p->time);?></span>
		</td>
		<td class="forumtitle2" align="left">
			<span class="forumone">
				<?php if ($linkSubject){
				echo JHTML::lnk('forum?pid='.$p->id, $p->subject).'</a>';
				} else { ?>
				<a style="color:#000;" name="post<?php echo $p->id;?>">
					<?php echo $p->subject;?>
				</a>
			<?php } ?>	
			</span>
		</td>
	</tr>
	<tr>
		<td valign="top" style="border-bottom:none;">
			<div style="width:200px;overflow:auto">
			<?php	
			if ( !isset($noProfile) ){
				if ($p->gender<2){
					echo '<div class="maleC">';
				}
				else{
					 echo '<div class="femaleC">';
				}
				if ( !isset($date)){
					$date = date('m-d');
				}
				if ( !isset($year)){
					$year = intval(date('Y'));
				}
				$y = intval(substr($p->birthdate,0,4));
				$tmp = substr($p->birthdate,5);
				$y = $year - $y;
				if ($date < $tmp){
					--$y;
				}
				echo JHTML::profileLink($p->who_id,$p->username).' <span class="forumtwo">('.$y.')</span>';
				echo '<div>';
			}	
			else{
				echo JHTML::profileLink($p->who_id,$p->username).'<br />';
			}			
			echo $p->name;
			if ( !isset($noProfile) ){
				echo '<div class="forumtwo">Postova: '.$p->posts;
				echo '<br />Lokacija: '.$p->location;
				echo '</div><br />';
			}	
			?>
			</div>	
		</td>	
		<td valign="top">
			<div style="width:598px;overflow:auto">
			<?php echo editor_decode($p->message);?>
			</div>
		</td>
	</tr>
  <tr>
		<td>&nbsp;</td>
		<td valign="bottom" align="left">
			<div style="width:598px;overflow:auto">
				<span style="color:#777" class="forumtwo"><?php echo $p->signature;?></span>
				<div style="padding:2px;text-align:right;">
				<?php
				if ( isset($rights) ){
					if ($rights > 1){
						echo JHTML::lnk('forum?task=newP&pid='.$p->id,'[citiraj]');
					}
					if ($isMod || ($p->who_id==$user->id && $rights > 1)){
						$lnkCh = 'forum?task=newP&change=1&pid='.$p->id;
						if ($this->topic->id == $p->id) $lnkCh .= '&first=1';
						echo '&nbsp;'.JHTML::lnk($lnkCh,'[izmeni]');
					}	
					if ($isMod){
						echo '&nbsp;';
						echo '<a onclick="return dlgCnf(1)" href="'.Basic::routerBase().'/forum?task=del&pid='.$p->id.'">';
						echo JText::_('ERASE');
						echo '</a>';
					}
					echo '&nbsp;';
				}
				else if ( isset($p->cat_name) ){
					echo JHTML::lnk('forum?cid='.$p->cid,$p->cat_name);
					echo ' >> ';
					echo JHTML::lnk('forum?tid='.$p->tid,$p->topic_subject);
					echo ' &nbsp; &nbsp; ';
				}
				?>
				</div>
			</div>
		</td>
	</tr>
</table>