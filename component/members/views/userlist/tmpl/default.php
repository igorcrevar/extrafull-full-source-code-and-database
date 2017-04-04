<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
  if (isset($this->notfound ) && $this->notfound)
  {
  	echo '<span style="color:red;font-weight:bold;">Ne postoji ni jedan član sa zadatim kriterijumom!</span>';
  }
?>
<form action="<?php echo Basic::routerBase();?>/clan/lista" method="get"> 	
<table>
<?php
if ( isProfileMod($this->userId) ){
	echo '<tr><td>&nbsp;</td><td><a href="'.Basic::routerBase().'/clan/lista?baned=1&layout=list">[Banovani]</a></td></tr>';
}
?>	
	<tr>
		<td align="right">	
    	Pol :
    </td>
    <td>	
      <input type="radio" value="1" name="gender"/>muški
      <input id="gender" type="radio" value="2" name="gender"/>ženski 
      <input CHECKED type="radio" value="0" name="gender"/>muški i ženski
    </td>  
  </tr>
  <tr>  
   	<td align="right">	
    	Slika :
    </td>
    <td>	
       <input type="radio" value="1" name="picture"/>samo članovi sa slikom
       <input CHECKED type="radio" value="0" name="picture"/>mogu i članovi bez slike
    </td>  
  </tr>
  <tr>
  	<td align="right">
      Datum rođenja :
    </td> 
    <td>  
      <select name="birth_option">
        <option selected value="0">Tačno</option>
        <option value="1">Stariji od</option>
        <option value="2">Mlađi od</option>
      </select>	
      <select name="birth_year">
          <option selected value="0">----</option>
          <option value="2001">2001</option>
          <option value="2000">2000</option>
          <option value="1999">1999</option>
          <option value="1998">1998</option>
          <option value="1997">1997</option>
          <option value="1996">1996</option>
          <option value="1995">1995</option>
          <option value="1994">1994</option>
          <option value="1993">1993</option>
          <option value="1992">1992</option>
          <option value="1991">1991</option>
          <option value="1990">1990</option>
          <option value="1989">1989</option>
          <option value="1988">1988</option>
          <option value="1987">1987</option>
          <option value="1986">1986</option>
          <option value="1985">1985</option>
          <option value="1984">1984</option>
          <option value="1983">1983</option>
          <option value="1982">1982</option>
          <option value="1981">1981</option>
          <option value="1980">1980</option>
          <option value="1979">1979</option>
          <option value="1978">1978</option>
          <option value="1977">1977</option>
          <option value="1976">1976</option>
          <option value="1975">1975</option>
          <option value="1974">1974</option>
          <option value="1973">1973</option>
          <option value="1972">1972</option>
          <option value="1971">1971</option>
          <option value="1970">1970</option>
          <option value="1969">1969</option>
          <option value="1968">1968</option>
          <option value="1967">1967</option>
          <option value="1966">1966</option>
          <option value="1965">1965</option>
          <option value="1964">1964</option>
          <option value="1963">1963</option>
          <option value="1962">1962</option>
          <option value="1961">1961</option>
          <option value="1960">1960</option>
          <option value="1959">1959</option>
          <option value="1958">1958</option>
          <option value="1957">1957</option>
          <option value="1956">1956</option>
          <option value="1955">1955</option>
          <option value="1954">1954</option>
          <option value="1953">1953</option>
          <option value="1952">1952</option>
          <option value="1951">1951</option>
          <option value="1950">1950</option>
          <option value="1949">1949</option>
          <option value="1948">1948</option>
          <option value="1947">1947</option>
          <option value="1946">1946</option>
          <option value="1945">1945</option>
      </select> 
    </td>  
  </tr>  
  <tr>
  	<td align="right">
      Mesto stanovanja :
    </td>
    <td>
    	<input type="text" name="location" maxlength="50"/>
    </td>	  
  </tr>
	<tr>
  	<td align="right">
      Ime i/ili prezime :
    </td>
    <td>
    	<input type="text" name="namesur" maxlength="30"/>
    </td>	  
  </tr>  
  <tr>
  	<td align="right">
      Korisničko ime :
    </td>
    <td>
    	<input type="text" name="username" maxlength="25"/>
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
			 	  if ($j == 1) echo '<tr>';
					echo '<td width="150px"><input type="checkbox" name="music[]" value="'.$i.'" />'.$music.'</td>';
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
      <?php echo JText::_('MB_LOVE');?> :
    </td>
    <td>
    	<?php 
    	$vals = explode(',', JText::_('MB_LOVE_VALS') );
    	echo '<SELECT name="love">';
    	echo '<OPTION value="-1">...</OPTION>';
    	foreach ($vals as $vid => $val){
    		echo '<OPTION value="'.$vid.'">'.$val.'</OPTION>';
    	}
    	echo '</SELECT>';
    	?>      
    </td>	  
  </tr>   
	<?php if (isset($this->photo_locations)):?>
  <tr>
  	<td align="right">
      Gde izlaze :
    </td>
    <td>
      <?php echo $this->photo_locations;?>
    </td>	  
  </tr>             
	<?php endif;?>
  <tr>
  	<td align="right">
      Sortiraj rezultate po :
    </td>
    <td>
    	<select name="sort">
    		  <option selected value="0">datumu registracije</option>
          <option value="3">korisničkom imenu</option>
          <option value="1">datumu rođenja</option>
          <option value="2">datumu poslednje posete</option>
      </select>
    </td>	  
  </tr>
  <tr>
  	<td>&nbsp;<!--<span style="color:red">Tekstualna polja nisu obavezna</span>--></td>
  	<td><input type="submit" class="button" value="Pretraži!"/></td>
  </tr>	  
</table>    
  <input type="hidden" name="layout" value="list"/>
</form>  
