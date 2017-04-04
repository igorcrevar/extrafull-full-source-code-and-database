<?php defined( 'CREW' ) or die( 'Restricted access' );
  $user = $this->user;
  $me = &Session::getInstance();
  $myid = $me->userId;
?>
<div id="profile">
	<h3 class="normal p-hd"><?php echo JText::_('MB_HEAD').' '.$user->username;?></h3>
	<div id="profile-left">
		<table width="100%" cellspacing="1" cellpadding="0">
			<tr>
				<td class="bt">&nbsp;</td>	
				<td class="bt" align="center">
					<img style="border:2px solid #223322;" src="<?php echo Basic::routerBase();?>/avatars/<?php echo $user->avatar;?>"/>
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
  	  echo '<tr><td class="bt" align="center">';
  	  echo '<b>'.JText::_('MB_KARMA_M1').' : </b>';
  	  $tmpS = '<span id="vsum">'.$user->karma.'</span> od <span id="vcnt">'.$user->karma_time.'</span> ';
 			if ( $user->username != ''  &&  $myid > 0  &&  $user->id != $myid ){
 				echo $tmpS;
 				echo '<span id="v_cs">';
   			if ($user->myKarma != 1)
 	   			echo '<a href="#" onclick="vote_m('.$user->id.',1);return false;">'.JHTML::image('kplus.gif','title="Pozitivna"').'</a>&nbsp;';
 	   		if ($user->myKarma != -1)
     			echo '<a href="#" onclick="vote_m('.$user->id.',-1);return false;">'.JHTML::image('kminus.gif','title="Negativna"').'</a>&nbsp;';
     		echo '</span>';	
 			} 
 			else echo $tmpS;
  	  echo '</td></tr>';
  	?>
    </table>
    <table width="100%" cellspacing="1" cellpadding="0">
		<?php if ( $user->username != ''  &&  $myid > 0  &&  $user->id != $myid ){ ?>
			<tr>
				<td class="bt">
  				<input type="button" class="button" value="<?php echo JText::_('MB_FRIEND_HD');?>" onclick="showMyDiv('friendSend','msgSend');"/>
  			</td>	   
				<td class="bt" align="center">
  				<input type="button" class="button" value="<?php echo JText::_('MB_P_MSG_HD');?>" onclick="showMyDiv('msgSend','friendSend');"/>
  			</td>       	  
			</tr>          	
			<tr>
				<td colspan="2"><div id="msgSend" style="display:none;background:#ffffeF;border:1px solid #223322;">
  				<div id="msgMSG"></div>
   				<?php echo JText::_('MB_P_MSG_SUB');?><br><input maxlength="60" id="subject" type="text"/><br>
          <?php echo JText::_('MB_P_MSG_TXT');?>(<span id="cl_2">1000</span>)<br> 
					<textarea id="text" wrap="VIRTUAL" onKeyDown="commentChanged(this,<?php echo $myid==68 ? '6000' : '1000';?>,'cl_2')" onKeyUp="commentChanged(this,<?php echo $myid==68 ? '6000' : '1000';?>,'cl_2')" rows="4" cols="38"></textarea><br>
   				<input onclick="prv_msg(<?php echo $user->id;?>);return false;" type="button" class="button" value="<?php echo JText::_('MB_P_MSG_B');?>"/>
				</div></td>
			</tr>  
			<tr>
				<td><div id="friendSend" style="display:none">
					<div id="msgFRIEND"></div>
					<input onclick="add_friend(<?php echo $user->id;?>);return false;" type="submit" class="button" value="<?php echo JText::_('MB_FRIEND_B');?>"/>
				</div></td>
			</tr>  
		<?php } 
			echo '<tr><td class="bt bs">'.JText::_('MB_VISIT').'</td>'; 
			echo '<td class="bt">'.$user->uhits.'</td></tr>';
  		echo '<tr><td class="bt bs">'.JText::_('MB_LAST_VISIT').'</td>'; 
			echo '<td class="bt">'.JHTML::date($user->lastvisitDate).'</td></tr>';
  		echo '<tr><td class="bt bs">'.JText::_('MB_REG').'</td>'; 
			echo '<td class="bt">'.JHTML::date($user->registerDate).'</td></tr>';
		?>
		</table>
		<div id="stats" class="bt sp">
		<a onclick="loadStat(<?php echo $user->id.','.$user->posts;?>);return false;" href="#" ><?php echo JText::_('STATISTICS');?></a>
	  </div> 	

 <?php
 	  if ( $user->music > 0 ){
      echo '<div class="bt sp">';
      echo '<div class="people">'.JText::_('MUSIC').'</div>&nbsp;';
      $musics = explode(',',JText::_('MUSICS'));
      $i = 1;
      $j = 0;
			foreach ($musics as $music){
				if ( ($user->music & $i) > 0){
					++$j;
					if ($j > 1) echo ' , ';
					echo '<a href="'.Basic::routerBase().'/clan/lista?layout=list&music='.$i.'"><b>'.$music.'</b></a>';
				}
				$i = $i << 1;
			}
			echo '</div>';
		}
		?> 	  	
    <?php 
		echo '<div class="bt sp">';
		echo '<div class="people">'.JText::_('MB_PICS_HD').'</div>';
		for ($i=0;$i<count($this->galleries);++$i){
			if ($i>0) echo ' , ';
			$gn = trim( $this->galleries[$i]->name );
			if ( $gn == '' ) $gn = 'Galerija';
			echo JHTML::galleryLink($this->galleries[$i]->id,'<b>'.$gn.'</b>');
		}  
		if ($i>0) echo ' , ';
		echo JHTML::picsLink($user->id,'<b>'.JText::_('MB_PICS_FAV').'</b>');
		echo ' , '.JHTML::picsLink($user->id.'?stat=1','<b>'.JText::_('MB_PICS_CMNTS').'</b>');
		echo ' , '.JHTML::picsLink($user->id.'?stat=2','<b>'.JText::_('MB_PICS_VOTED').'</b>');
    echo '</div>';
 	  
 	  echo '<div class="bt sp">';
    echo '<div class="user_edit">Napisanih blogova: <a href="'.Basic::routerBase().'/blog/blogs/'.$user->id.'">'.$user->blogs.'</a></div>';
    echo '</div>';
    
    echo '<div class="bt sp">';
    echo '<div class="people">'.JText::_('MB_FRIENDS_HD').'</div>';
    if ( count($user->tfriends) ){
			foreach ($user->tfriends as $friend){				
			  $gc = ($friend->gender<2) ? 'maleC' : 'femaleC';
			  echo '<div class="'.$gc.'">';
			  $friend->location = '';//str_replace( array("'", '"'), '', $friend->location );
			  echo '<a href="'.Basic::routerBase().'/profil/'.$friend->id.'" onmouseover="mDetails(this,\''.str_replace( array("'", '"'), '', $friend->username ).'\',\''.$friend->avatar.'\',\''.$friend->location.'\',\''.$friend->birthdate.'\');" onmouseout="hidePopUp(this);">';
			  echo $friend->name.'</a>';
			  $online = $friend->cnt > 0 ? ' [trenutno online]': '';
  			echo $online.'</div>';
			}
		}	
		echo '<br><div class="people"><a href="'.Basic::routerBase().'/clan/lista?layout=list&friends='.$user->id.'">'.JText::_('LOOK_ALL_FR').' ('.$user->friends.')</a></div>';
		if ( $myid != $user->id ){
			echo '<div class="people"><a href="'.Basic::routerBase().'/clan/lista?layout=list&friends='.$user->id.'&mutal=1">Pogledaj zajednicke prijatelje</a></div>';
		}
		echo '<div class="maleC"><a href="'.Basic::routerBase().'/clan/lista?layout=list&gender=1&friends='.$user->id.'">'.JText::_('LOOK_MALE_FR').'</a></div>';
		echo '<div class="femaleC"><a href="'.Basic::routerBase().'/clan/lista?layout=list&gender=2&friends='.$user->id.'">'.JText::_('LOOK_FEMALE_FR').'</a></div>';
		echo '</div>';
		?> 
	</div>	
  <div id="profile-right">  

		<?php
		echo '<div style="padding:2px 8px;text-align:right" id="userblock'.$user->id.'">';
		require_once BASE_PATH.DS.'block.php';
		if ($myid != $user->id) UserBlocks::show($user->id,0,false);
		if ( $myid >= 18  &&  isProfileMod($myid)  &&  $myid != $user->id  ){
			echo JHTML::lnk( 'clan/mojprofil/'.$user->id, '[edituj profil]' );
		}
		echo '</div>';	

		echo '<div class="bt sp">';
		if ($user->gender == 2) echo '<div class="femaleC">';		 
		else echo '<div class="maleC">';
		echo '<b>'.$user->name.'</b> ';
		if ( !empty($user->whatsup) ){
			echo $user->whatsup;
		}			
		echo '</div></div>';
		echo '<div class="bt sp" style="padding-left:10px">';
		echo '<div style="font-style:italic">'.JHTML::image('love.gif').JText::_('MB_LOVE').'</div>';
		$tmpS=explode(',',JText::_('MB_LOVE_VALS'));
		$tmpI = $user->love;
		if ($tmpI<0) $tmpI=0;
		else if ($tmpI>=count($tmpS)) $tmpI=count($tmpS)-1;			
		if ( $user->lover_id > 1 ){
		if ( $tmpI < 1 || $tmpI > 2 ) $tmpI = 1;
			echo ucfirst($tmpS[$tmpI]);
			echo ' sa '.JHTML::profileLink( $user->lover_id, $user->lover_name );
		}
		else{
			echo ucfirst($tmpS[$tmpI]);
		}
		echo '</div>';
																
		if ($user->mood > 1){
			echo '<div class="bt sp"><b>'.JText::_('MOOD').'</b> : ';
			$tmpS = explode(',', JText::_('MOODS') );
			$tmpI = $user->mood - 1;
			if ($tmpI<1) $tmpI=1;
			else if ($tmpI >= count($tmpS)) $tmpI=count($tmpS)-1;			
			echo $tmpS[$tmpI];
			echo '</div>';
		}

		echo '<div class="bt sp">';
		echo ' <b>'.JText::_('MB_BIRTH').'</b> : '.JHTML::date($user->birthdate,'date2');
		echo '</div>';
			
		if ( $user->location != '' ){
			echo '<div class="bt sp"><b>'.JText::_('MB_LOCATION').'</b> : ';
			echo $user->location;
			echo '</div>';
		}	

	 	if ( $user->MSN != ''){
			echo '<div class="bt sp"><b> MSN : </b>'.$user->MSN.'</div>';
   	} 
	 	if ($user->YIM != ''){
			echo '<div class="bt sp"><b> YIM : </b>'.$user->YIM.'</div>';
   	} 
		if ($user->GTALK != ''){
			echo '<div class="bt sp"><b> GTALK : </b>'.$user->GTALK.'</div>';
    } 
		if ($user->SKYPE != ''){
			echo '<div class="bt sp"><b> SKYPE : </b>'.$user->SKYPE.'</div>';
    } 
	?>
	          
    <div class="bt sp"><b><?php echo JText::_('MB_ABOUT');?></b><br>        
    	 <?php echo $user->personalText;?>
    </div>
    <div id="descs" class="bt sp">
    <a href="#" onclick="loadDesc(<?php echo $user->id;?>);return false;"><?php echo JText::_('DESCRIPTIONS');?></a>  
    </div>	     
    <div class="bt sp"><b><?php echo JText::_('MB_SIGN');?></b><br>
    	 <?php echo wordwrap($user->signature,40,'<br>',true);?>
    </div>
  	<?php 
		if ( count($this->locations) ){
			echo '<div class="bt bs sp">';
			echo '<div class="people">'.JText::_('MB_CLUBS').'</div>';
    	$i = 0;
			foreach ($this->locations as $row){
				if ($i>0) echo ' ,';
				echo '&nbsp; <a href="'.Basic::routerBase().'/clan/lista?layout=list&photo_locations='.$row->id.'">';
				echo $row->name.'</a>';    	  	  
				++$i;
			}
			echo '</div>';
		}
		?>
		
    <div class="bt sp" style="overflow:auto">
    	<div class="user_edit"><?php echo JText::_('MB_CMNTS');?>
    		<?php if ( $user->id == $myid ){ ?>
    		&nbsp; <span style="font-size:9px;font-weight:normal"><a onclick="return dlgCnf(2)" href="<?php echo Basic::routerBase();?>/clan?task=clearCmnts">[ukloni sve]</a></span>
    		<?php } ?>
    	</div>
    <?php 
			echo '<div id="p_cs">';
			for ($i = 0;$i < count($this->comments); ++$i ){
				$row = $this->comments[$i];
				echo '<b>'.JHTML::profileLink($row->from_id,$row->username);
				echo ' '.JHTML::_('date',$row->date);
				echo '</b><br>';
				echo $row->comment;
				if ( $myid == $user->id || $row->from_id == $myid){
    	  	echo '&nbsp; <a href="javascript:del_c('.$user->id.','.$row->id.');">'.JText::_('MB_ERASE_CMNT').'</a>';
    	  } 
				echo '<br>';
			}
			$this->pag->getPagesLinks(false);
			echo '</div>';
			if ( $user->username != ''  &&  $myid > 0  &&  $user->id != $myid ){     	  	
    	?>
   	  <div id="cmta"></div><textarea id="_comment" wrap="VIRTUAL" onKeyDown="commentChanged(this,400,'chars_left')" onKeyUp="commentChanged(this,400,'chars_left')" name="comment" rows="4" cols="30"></textarea><br>
      <?php echo JText::_('MB_CMNTS_M');?><br>
   	  <input onclick="comment(<?php echo $user->id;?>);return false;" type="button" class="button" value="<?php echo JText::_('MB_CMNTS_B');?>"/>
   	  
      <?php } ?>
    </div>	
  </div>
  <div style="clear:left"></div>	
</div>