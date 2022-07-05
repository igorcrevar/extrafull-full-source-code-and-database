<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 
if ( isset($this->oldVals) ){
	$rv = $this->oldVals;
}
if ( count($this->errors) ){
	echo '<div id="userMsgs" style="height:auto">';
	foreach ( $this->errors as $err ){
		echo $err.'<br />';
	}
	echo '</div>';	
}
?>
		 	 			
<!--<h2>Upozorenje : Morate uneti pravu e-mail adresu, jer cete na nju dobiti poruku koja sadrzi aktivacioni link koji morate da potvrdite!!!</h2>-->
<form action="<?php echo JRoute::_( 'index.php?option=com_user' ); ?>" method="post" id="josForm" name="josForm">


<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td height="40"  width="140" align="right">
			<?php echo USERNAME; ?> :&nbsp; 
	</td>
	<td width="200">
		<input type="text" id="username2" name="username" size="40" maxlength="25" value="<?php echo isset($rv)?$rv->username:'';?>" />
	</td>
	<td>&nbsp;
		 <span style="color:red" id="usernameError"></span>
  </td>	
</tr>
<tr>
	<td height="40"  align="right">
			<?php echo PASSWORD; ?>:&nbsp;
	</td>
  <td>
  		<input type="password" id="password1" name="password" size="40" value="" maxlength=20 />
  </td>
 	<td>&nbsp;
		 <span style="color:red" id="passError"></span>
  </td>	
</tr>
<tr>
	<td height="40" align="right">
			Potvrda lozinke:&nbsp;
	</td>
	<td>
		<input type="password" id="password2" name="password2" size="40" value="" maxlength=20 />
	</td>
	<td>&nbsp;
		 <span style="color:red" id="pass2Error"></span>
  </td>	
</tr>
<tr>
	<td height="40" align="right">
			e-mail adresa :&nbsp;
	</td>
	<td>
		<input type="text" id="email" name="email" size="40" value="<?php echo isset($rv)?$rv->email:'';?>" maxlength=50 />
	</td>
	<td>&nbsp;
		 <span style="color:red" id="emailError"></span>
  </td>	
</tr>
<!--
<tr>
	<td height="40" align="right">
			Ime i prezime :&nbsp;
	</td>
	<td>
		<input type="text" id="name" name="name" size="40" value="nema" maxlength=40 />
	</td>
	<td>&nbsp;
		 <span style="color:red" id="nameError"></span>
  </td>	
</tr>-->
<tr>
	<td height="40" align="right">
			Pol :&nbsp;
	</td>
	<td>
		<input <?php if (!isset($rv) || $rv->gender==1) echo 'CHECKED';?> id="gender" type="radio" value="1" name="gender"/>muški
    <input <?php if (isset($rv) && $rv->gender==2) echo 'CHECKED';?> id="gender" type="radio" value="2" name="gender"/>ženski 
	</td>
	<td>&nbsp;
		 <span style="color:red" id="genderError"></span>
  </td>	
</tr>
<tr>
	<td height="40" align="right">
		Datum rođenja :&nbsp; 
	</td>
	<td>
		<select id="birth_day" name="birth_day">
			<option <?php if (!isset($rv)) echo 'SELECTED';?> value="0">--</option>
			<?php
			for ($i = 1;$i <= 31; ++$i){
				echo '<option value="'.$i.'"';
				if (isset($rv) && $rv->day==$i) echo ' SELECTED ';
				echo '>'.$i.'</option>';
			}
			?>      
      </select>
      <select id="birth_month" name="birth_month">
      <option <?php if (!isset($rv)) echo 'SELECTED';?> value="0">-----------------</option>	
			<?php
			$month = array('','Januar','Februar','Mart','April','Maj','Juni','Juli','Avgust',
			'Septembar','Oktobar','Novembar','Decembar');
			for ($i = 1;$i <= 12; ++$i){
				echo '<option value="'.$i.'"';
				if (isset($rv) && $rv->month==$i) echo ' SELECTED ';
				echo '>'.$month[$i].'</option>';
			}
			?>      
		  </option>
      </select>
      <select id="birth_year"  name="birth_year">
      <option <?php if (!isset($rv)) echo 'SELECTED';?> value="0">-------</option>	
			<?php
			for ($i = 2000;$i >= 1950; --$i){
				echo '<option value="'.$i.'"';
				if (isset($rv) && $rv->year==$i) echo ' SELECTED ';
				echo '>'.$i.'</option>';
			}
			?>      
		  </select>
	</td>
	<td>&nbsp;
		 <span style="color:red" id="birthError"></span>
  </td>	
</tr>	

<tr align="right">
	<td align="right">
		Kod sa slike : &nbsp;
	</td>	
	<td align="left">
		<input type="text" name="scode" maxlength="4" />
	</td>		
	<td align="left">
		<img src="<?php echo Basic::uriBase();?>/img.php?sid=<?php echo md5(uniqid(time())); ?>" id="captchaimage" align="absmiddle" />
		<a href="#" onclick="document.getElementById('captchaimage').src = '<?php echo Basic::uriBase();?>/img.php?sid=' + Math.random(); return false">[druga slika]</a>
	</td>	
</tr>	

<tr>
    <td align="right">
    <input class="button" type="submit" onclick="return provera();" value="Pošalji!"/>&nbsp;
    </td>
	<td colspan="2" height="40">
		<div style="font-size:13px;font-weight:bold;color:red">Obavezno unesite pravu e-mail adresu, jer u suprotnom necete moci da se registrujete</div>
		<span style="color:red;">Sva polja su obavezna.</span>
	</td>
</tr>
</table>
<input type="hidden" name="task" value="register_save" />
<input type="hidden" name="id" value="0" />
<input type="hidden" name="gid" value="0" />
</form><br>
<script type="text/javascript" language="javascript">
	function provera()
	{
		 p1 = document.getElementById("password1").value;
		 p2 = document.getElementById("password2").value;
		 k = document.getElementById("username2").value;
		 dd = document.getElementById("birth_day").value;
		 mm = document.getElementById("birth_month").value;
		 yy = document.getElementById("birth_year").value;
		 email = document.getElementById("email").value;
		 //myname = document.getElementById("name").value;
		 ok = true;
	 	/* a = document.getElementById('nameError');
		 if ( myname.length==0  )
		 {
		 	 a.innerHTML = 'Niste uneli ime i prezime!';
		 	 ok = false;
		 } 
		 else
		 	 a.innerHTML = '';*/
		 a = document.getElementById('emailError');
		 if ( email.length==0  )
		 {
		 	 a.innerHTML = 'Nije dobro uneta e-mail adresa!';
		 	 ok = false;
		 } 
		 else
		 	 a.innerHTML = '';
	 	 a = document.getElementById('usernameError');
		 if ( k.length < 4 )
		 {
		 	 a.innerHTML = 'Korisničko ime mora biti dugačko barem 4 karaktera!';
		 	 ok = false;
		 } 
		 else
		 	 a.innerHTML = '';
		 a = document.getElementById('passError');		 	 
		 if ( p1.length < 3 )
		 {		   
		 	 a.innerHTML = 'Lozinka mora biti dugačka barem 3 karaktera!';
		 	 ok = false;
		 }
		 else
		 	 a.innerHTML = '';
		 a = document.getElementById('pass2Error');	
		 if ( p1 != p2 )
		 {		   
		 	 a.innerHTML = 'Lozinka mora biti dobro potvrđena!';
		 	 ok = false;
		 }	 
		 else
		 	 a.innerHTML = '';
 	   a = document.getElementById('birthError');
		 if ( dd == 0 || mm == 0 || yy == 0 )
		 {
		 	  a.innerHTML = 'Morate specificirati datum rodjenja!';
		 	  ok = false;
		 }  
		 else 
		 	 a.innerHTML = '';
		 return ok;
	} 
</script>	