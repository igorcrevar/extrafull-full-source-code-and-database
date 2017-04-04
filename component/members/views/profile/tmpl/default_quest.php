<?php defined( 'CREW' ) or die( 'Restricted access' );
  $user = $this->user;
?>
<div id="profile">
	<h3 class="normal p-hd"><?php echo JText::_('MB_HEAD').' '.$user->username;?></h3>
	<div id="profile-left">
		<table width="100%" cellspacing="1" cellpadding="0">
			<tr>
				<td class="bt">&nbsp;</td>	
				<td class="bt" align="center">
					<img style="border:2px solid #223322;" src="<?php echo Basic::routerBase();?>/avatars/<?php echo str_replace('l_','',$user->avatar);?>"/>
				</td>	
				<td class="bt">&nbsp;</td>	          
      </tr>	
  	</table>
  	<table width="100%" cellspacing="1" cellpadding="0">
  	<?php
  	  echo '<tr><td class="bt" align="center">';
  	  $online = ($user->cnt > 0 ? 'online.gif': 'offline.gif');
  	  echo JHTML::image($online);
  	  echo '</td></tr>';
  	  echo '<tr><td class="bt"><div style="text-align:center" id="v_cs">';
  	  echo '<b>'.JText::_('MB_KARMA_M1').' : </b>';
 			echo $user->karma.', <b>'.JText::_('MB_KARMA_M2').' : </b>'.$user->karma_time;
  	  echo '</div></td></tr>';
  	?>
    </table>
    <table width="100%" cellspacing="1" cellpadding="0">
		<?php
			echo '<tr><td class="bt bs">'.JText::_('MB_VISIT').'</td>'; 
			echo '<td class="bt">'.$user->uhits.'</td></tr>';
  		echo '<tr><td class="bt bs">'.JText::_('MB_LAST_VISIT').'</td>'; 
			echo '<td class="bt">'.JHTML::date($user->lastvisitDate).'</td></tr>';
  		echo '<tr><td class="bt bs">'.JText::_('MB_REG').'</td>'; 
			echo '<td class="bt">'.JHTML::date($user->registerDate).'</td></tr>';
		?>
		</table>
 	  <div class="bt sp">
    <div class="user_edit">Napisanih blogova: <a href="<?php echo Basic::routerBase();?>/blog/blogs/<?php echo $user->id;?>"><?php echo $user->blogs;?></a></div>
    </div>

   
	</div>	
  <div id="profile-right">  


		<table width="100%" cellspacing="1" cellpadding="0">
		<?php 
echo '<tr><td class="bt" colspan="2"><b>'.$user->name.'</b>';
if ( !empty($user->AIM) != ''){
	echo ' '.$user->AIM;
}			
echo '</td></tr>';
								
			echo '<tr><td class="bt bs">'.JText::_('MB_SEX').'</td>'; 
			echo '<td class="bt">';			
			if ($user->gender == 2) echo JText::_('MB_FEMALE'); else echo JText::_('MB_MALE');
			echo '</td></tr>';			
			
			echo '<tr><td class="bt bs">'.JText::_('MB_BIRTH').'</td>'; 
			echo '<td class="bt">'.JHTML::_('date',$user->birthdate,'date').'</td></tr>';
									
			echo '<tr><td class="bt bs">'.JText::_('MB_LOVE').'</td>';
			$tmpS=explode(',',JText::_('MB_LOVE_VALS'));
			$tmpI=$user->love;
			if ($tmpI<0) $tmpI=0;
			else if ($tmpI>=count($tmpS)) $tmpI=count($tmpS)-1;			
			echo '<td class="bt">'.$tmpS[$tmpI].'</td></tr>';
			
			if ($user->mood > 1){
				echo '<tr><td class="bt bs">'.JText::_('MOOD').'</td>';
				$tmpS=explode(',',JText::_('MOODS'));
				$tmpI=$user->mood - 1;
				if ($tmpI<1) $tmpI=1;
				else if ($tmpI >= count($tmpS)) $tmpI=count($tmpS)-1;			
				echo '<td class="bt">'.$tmpS[$tmpI].'</td></tr>';
			}
			
			if ( $user->location != '' ){
				echo '<tr><td class="bt bs">'.JText::_('MB_LOCATION').'</td>';
				echo '<td class="bt">'.$user->location.'</td></tr>';
			}	
		?>
    </table>
	          
    <div class="bt sp"><b><?php echo JText::_('MB_ABOUT');?></b><br>        
    	 <?php echo $user->personalText;?>
    </div>     
    <div class="bt sp"><b><?php echo JText::_('MB_SIGN');?></b><br>
    	 <?php echo wordwrap($user->signature,40,'<br>',true);?>
    </div>		
  </div>
  <div style="clear:left"></div>	
</div>
