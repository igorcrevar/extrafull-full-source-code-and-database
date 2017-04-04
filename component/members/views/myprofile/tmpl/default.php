<?php defined( 'CREW' ) or die( 'Restricted access' );
  $user = $this->list;
	$year = $month = $day = '';
	if ($user->birthdate) {
		$tmp = explode(' ',$user->birthdate);
		$date = $tmp[0];
		list($year,$month,$day) = explode( '-', $date );
	}
	require_once(JPATH_BASE.DS.'editor'.DS.'editor.php');	
?>
<table width="100%">
	<tr>
	  <td valign="top"  width="60%">
	  	<div class="myWindow">
	  	<h3 class="normal">Osnovni podaci</h3>	  		
	  	<form action="<?php echo Basic::routerBase();?>/index.php?option=members&view=myprofile" method="post">
	  		<table>
	  		<tr>
		      <td align="right">	
    	      Ime i prezime :&nbsp;
          </td>
          <td>	
            <input type="text" value="<?php echo $user->name;?>" name="name"  maxlength="35"/>
          </td>  
        </tr>  
        <tr>
		      <td align="right">	
    	     Pol :&nbsp;
          </td>
          <td>	
            <input <?php if ($user->gender != 2 ) echo 'CHECKED';?> type="radio" value="1" name="gender"/>muški
            <input <?php if ($user->gender == 2 ) echo 'CHECKED';?> type="radio" value="2" name="gender"/>ženski 
          </td>  
        </tr>
        <tr>
		      <td align="right">	
    	     Ljubavni status :&nbsp;
          </td>
          <td>	          	
          	<select name="love">
          		 <?php $tmp = explode(',',JText::_('MB_LOVE_VALS'));
          		 for ($i=0;$i< count($tmp);++$i)
          		 {
          		 	 if ( $i == $user->love )
  		         	   $tmpSel = 'SELECTED';
  		         	 else
  		         	   $tmpSel = '';  
  		         	 echo "<option $tmpSel value=\"$i\">$tmp[$i]</option>";
          		 }
          		 ?>          		 
            </select>	
          </td>  
        </tr>
        <tr>
  	      <td align="right">
  		       Datum rođenja :&nbsp; 
  	      </td>
  	      <td>		       
  		       <?php
  		         echo '<select name="birth_day">';
  		         for ($i=1;$i<32;++$i)
  		         {
  		         	 if ( $i == $day )
  		         	   $tmp = 'SELECTED';
  		         	 else
  		         	   $tmp = '';  
  		         	 echo "<option $tmp value=\"$i\">$i</option>";
  		         }
  		         echo '</select>';
  		         echo '<select name="birth_month">';
  		         for ($i=1;$i<13;++$i)
  		         {
  		         	 if ( $i == $month )
  		         	   $tmp = 'SELECTED';
  		         	 else
  		         	   $tmp = '';  
  		         	 echo "<option $tmp value=\"$i\">$i</option>";
  		         }
  		         echo '</select>';
  		         echo '<select name="birth_year">';
  		         for ($i=2000;$i>=1946;--$i)
  		         {
  		         	 if ( $i == $year )
  		         	   $tmp = 'SELECTED';
  		         	 else
  		         	   $tmp = '';  
  		         	 echo "<option $tmp value=\"$i\">$i</option>";
  		         }
  		         echo '</select>';		         
  		       ?>
	        </td>
	      </tr> 
	      <tr>
		      <td align="right">	
    	      Lokacija :&nbsp;
          </td>
          <td>	
            <input type="text" value="<?php echo $user->location;?>" name="location" maxlength="35"/>
          </td>  
        </tr>
				<tr>
		      <td align="center" colspan="2">	
    	      O meni :<br/>
    	      <?php editor_show('personalText',$user->personalText,'',1200,53,8);?>
          </td>
        </tr>  
        <tr>
		      <td align="right">	
    	      Potpis :&nbsp;
          </td>
          <td>	
          	<textarea name="signature" cols="40" rows="3" maxlength="200"><?php echo $user->signature;?></textarea>
          </td>            
        </tr>      
        <tr>
		      <td align="right">	
    	      MSN :&nbsp;
          </td>
          <td>	
            <input type="text" value="<?php echo $user->MSN;?>" name="MSN" maxlength="70"/>
          </td>  
        </tr>          	
        <tr>
	        <td align="right">	
    	      YIM :&nbsp;
          </td>
          <td>	
            <input type="text" value="<?php echo $user->YIM;?>" name="YIM" maxlength="70"/>
          </td>  
        </tr> 
        <tr>         	
	        <td align="right">	
    	      SKYPE :&nbsp;
          </td>
          <td>	
            <input type="text" value="<?php echo $user->SKYPE;?>" name="SKYPE" maxlength="70"/>
          </td>  
        </tr>          	
        <tr>
	        <td align="right">	
    	      GTALK :&nbsp;
          </td>
          <td>	
            <input type="text" value="<?php echo $user->GTALK;?>" name="GTALK" maxlength="70"/>
          </td>  
        </tr>  
        <tr>
	        <td align="right">	
    	      <?php echo JText::_('MUSIC');?>
          </td>
          <td>	
          	<?php
          	 $musics = explode(',',JText::_('MUSICS'));
          	 $i = 1;
          	 $j = 1;
          	 echo '<table>';
      			 foreach ($musics as $music){
      			 	  $checked = ($user->music & $i) > 0 ? 'CHECKED' : '';
      			 	  if ($j == 1) echo '<tr>';
								echo '<td width="150px"><input type="checkbox" name="music[]" '.$checked.' value="'.$i.'" />'.$music.'</td>';
								if ($j < 2){
									++$j;
								}
								else{									
									echo '</tr>';
									$j = 1;
								}
								$i = $i << 1;								
      			 }
      			 echo '</table>';
          	?>
          </td>  
        </tr> 
				<tr>
          <td align="right">	
    	      <?php echo JText::_('MOOD');?>
          </td>
          <td>	
          	<select name="mood">
          	<?php
          	 $moods = explode(',',JText::_('MOODS'));
          	 $i = 1;
          	 foreach ($moods as $mood){
          	 	 echo '<option value="'.$i.'"';
          	 	 if ($i == $user->mood) echo ' SELECTED ';
          	 	 echo '>'.$mood.'</option>';
          	 	 ++$i;
          	 }
          	?>           
           </select>
          </td>  
        </tr>                   
        <tr>
          <td align="right">	
    	      &nbsp;
          </td>
          <td>	
            <input type="submit" value="Promeni podatke!" class="button" />
          </td>  
        </tr>            	
      </table>
				<input type="hidden" name="task" value="upload"/>
        <input type="hidden" name="segment" value="profile"/>
        <?php if (isset($this->id)) echo '<input type="hidden" name="id" value="'.$this->id.'"/>';?>
	    </form>	
	    </div>
	  </td>	
	  <td valign="top">
	    <div class="myWindow"><center>
	    <h3 class="normal">Slika</h3>
			<?php if (isset($user->avatar)  &&  $user->avatar != '' ){
			  echo '<img style="border:2px solid #223322;" src="'.Basic::routerBase().'/avatars/'.$user->avatar.'"/> ';
			  if (!isset($this->id))
			  	echo '<br><a href="'.Basic::routerBase().'/index.php?option=com_members&view=myprofile&task=upload&segment=picture&delete=1">[ukloni sliku]</a>';
			  else	
			  	echo '<br><a href="'.Basic::routerBase().'/index.php?option=com_members&view=myprofile&id='.$this->id.'&task=upload&segment=picture&delete=1">[ukloni sliku]</a>';
			}
			else
			  echo '<span style="color:red;font-weight:bold;">&nbsp;&nbsp;nemate sliku</span>';?> 
			<form enctype="multipart/form-data" action="<?php echo Basic::routerBase();?>/index.php?option=members&view=myprofile" method="post">
				<input type="file" name="upload_picture" />			
				<input class="button" type="submit" value="Promeni sliku!" />
				<input type="hidden" name="task" value="upload"/>
				<input type="hidden" name="segment" value="picture"/>
				<?php if (isset($this->id)) echo '<input type="hidden" name="id" value="'.$this->id.'"/>';?>				
		  </form>	
		  <div style="color:red;padding-top:4px;">
		  Pre uploada morate pripremiti sliku koju želite da postavite u nekom od programa za rad sa slikama (može i Paint). Slika treba da bude veličine ne veće od 220KB (Kilobajta). Najbolje je da unapred smanjite sliku na širinu  i visinu ne veće od 256 piksela. Na slici se NE SME nalaziti watermark sa nekog drugog sajta!!.
		  </div></center>
		  </div>
		  
		  <div class="myWindow">
	  	<h3 class="normal"><?php echo $user->name.' '.JText::_('IS');?></h3>
	  	<form action="<?php echo Basic::routerBase();?>/index.php?option=members&view=myprofile&task=upload&segment=is" method="POST">
	  	<input type="text" name="is" value="<?php echo $user->whatsup;?>" size="44" maxlength="120" /><br/>
	  	<input class="button" type="submit" value="Promeni!" />
	  	<?php if (isset($this->id)) echo '<input type="hidden" name="id" value="'.$this->id.'"/>';?>
	  	</form>	
	    </div>
	 <?php
		if ( count($this->beholders) ){
			echo '<div class="bt bs sp">';
			echo '<div class="people">'.JText::_('BEHOLDERS').'</div>';
    	$i = 0;
			foreach ($this->beholders as $row){
				if ($i>0) echo ' , ';
				echo JHTML::profileLink($row->id,$row->username);
				++$i;
			}
			echo '</div><br>';
		}				    
	  ?>			 
    	<div class="myWindow">
	  	<h3 class="normal"><?php echo JText::_('MB_CLUBS');?></h3>
	  	<div id="locs"><a href="#" onclick="loadLocs(<?php echo isset($this->id) ? $this->id : 0;?>);return false;"><b>[Klikni ovde za spisak lokacija]</b></a></div>
	  	</div>
	  <?php if (isset($this->id)) :?>
		    <table width="100%" cellspacing="0" cellspacing="1">
		    	<tr id="bottommodules">
		    		<td id="bottommodules"><b>IP</b>
		    	  </td>	
		    	  <td id="bottommodules">
		    	  	 <?php echo $user->IP;?>
		    	  </td>	
		      </tr>
		    </table><br />
		<?php endif ?>			    
  
		  <div class="myWindow">
		  <h3 class="normal">Detalji naloga</h3>
		  <form action="<?php echo Basic::routerBase();?>/index.php?option=com_members&view=myprofile" method="post">
		  <table>
		  	<tr>
          <td align="right">	
    	      E-mail :
          </td>
          <td>	
            <input type="text" name="email" value="<?php echo $user->email;?>" />
          </td>  
        </tr>    
		  	<tr>
          <td align="right">	
    	      Lozinka :
          </td>
          <td>	
            <input type="password" name="password" maxlength=22/>
          </td>  
        </tr>    
		  	<tr>
          <td align="right">	
    	     Potvrda lozinke :
          </td>
          <td>	
            <input type="password" name="password2" maxlength=22/>
          </td>  
        </tr>    
		    <tr>
          <td align="right">	
    	      &nbsp;
          </td>
          <td>	
            <input type="submit" value="Promeni podatke!" class="button" />
          </td>  
        </tr>            	
      </table>
				<input type="hidden" name="task" value="upload"/>
        <input type="hidden" name="segment" value="account"/>
        <?php if (isset($this->id)) echo '<input type="hidden" name="id" value="'.$this->id.'"/>';?>
	    </form><br>	
		  <?php if (isset($this->id)) { ?>
		  	<b>&nbsp;trenutno korisničko ime : </b><?php echo wordwrap($user->username,40,'<br/>&nbsp;',true);?>
		  	<form action="<?php echo Basic::routerBase();?>/index.php?option=com_members&view=myprofile" method="post">
					&nbsp;<input type="text" value="" name="username" maxlength="35"/>&nbsp; 
					<input type="submit" value="Promeni!" class="button" />					
					<input type="hidden" name="task" value="upload"/>
  	      <input type="hidden" name="segment" value="username"/>
  	      <?php if (isset($this->id)) echo '<input type="hidden" name="id" value="'.$this->id.'"/>';?>							  
		  	</form>&nbsp;
		  	<?php if ( $user->block == 0){
		  		 echo '<a href="'.Basic::routerBase().'/index.php?option=com_members&view=myprofile&id='.$this->id.'&task=upload&segment=ban">[Banuj korisnika]</a>';
		  	 }
		  	 else{
		  	 		echo '<a href="'.Basic::routerBase().'/index.php?option=com_members&view=myprofile&id='.$this->id.'&task=upload&segment=ban">[Odbanuj korisnika]</a>';
		  	 }
		   } ?>	
		  </div>
	  </td>	
	</tr>
</table>	  
 	