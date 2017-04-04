<?php defined('CREW') or die('Restricted access'); 
global $mainframe;?><br>
<div>
<div style="float:left;width:52%">	
<form id="image_form" action="index.php" method="post" enctype="multipart/form-data" >
	<table>
		<?php 
		if ($this->private==0){?>
		 <tr>
		 	 <td align="right">Kategorija</td>
		   <td><?php echo $this->list['c_id'];?></td>
     </tr>
		 <tr>
		 	 <td align="right">Lokacija</td>
		   <td><?php echo $this->list['l_id'];?></td>
     </tr>
 		 <tr>
		 	 <td align="right">Datum</td>
		   <td>
		   	 <input class="inputbox" type="text" id="date" name="date" value="<?php if ($this->event!=null) echo JHTML::_('date',$this->event->date,'date');?>"/>
		   </td>
     </tr>
    <?php } ?>
		 <tr>
		 	 <td align="right">Naziv</td>
		   <td><input type="text" name="name" value="<?php if ($this->event!=null) echo $this->event->name;?>"/></td>
     </tr>
     <tr>
			  <td align="right">Opis</td>
			  <td><textarea name="description" cols="40" rows="10"><?php if ($this->event!=null) echo $this->event->description;?></textarea></td>
		  </tr>	
    <tr>
     	<td align="right">Opcije</td>
			<?php 
			 $tname = explode(',',JText::_('PH_GALLERY_OPTION'));
       $opt = array(new stdClass(), new stdClass());
			 $opt[0]->options=2;$opt[0]->val=$tname[0];
			 $opt[1]->options=0;$opt[1]->val=$tname[1];
			 if ($this->private==1) {
          $opt[] = new stdClass();          
          $opt[2]->options=1;
          $opt[2]->val=$tname[2];
       }
			 $val = ($this->event != null) ? $this->event->options : 2;
       echo '<td>'.JHTML::_('select.genericlist', $opt, 'options', '','options', 'val', $val ).'</td>';
			?> 		   
     </tr>
		  <tr>
	  	 <td>&nbsp;</td>
	  	 <td>		           	
  	 	  <input type="submit" class="button" value="<?php if ($this->event!=null) echo 'Izmeni!';else echo 'Napravi!';?>" /> 
			 </td>
		 </tr>			
		</table> 
	<?php if ($this->event!=null) echo '<input type="hidden" name="event_id" value="'.$this->event->id.'" />';?>   
	<input type="hidden" name="private" value="<?php echo $this->private;?>" />	
  <input type="hidden" name="task" value="createEvent" />
	<input type="hidden" name="option" value="com_photo" />
</form>   
</div>
<div style="float:left; width:47%">
<?php
if (count($this->events)>0){
  echo '<table>';
  echo '<tr id="bottommodules"><td>Naziv galerije</td><td>Pogledaj</td><td>Uploaduj</td><td>Izmeni</td><td>Ukloni</td></tr>';
  for ($i=0;$i<count($this->events);$i++){
  	echo '<tr id="bottommodules">';
  	echo '<td width="60%"><img src="'.$mainframe->getImageDir().'arrow.gif"/>';
  	echo '<a href="'.JRoute::_('index.php?option=com_photo&view=imageupload&id='.$this->events[$i]->id.'&private='.$this->private).'">';
  	echo JString::substr($this->events[$i]->name,0,30).'</a></td>';
  	echo '<td align="center">';
  	echo '<a href="'.JRoute::_('index.php?option=com_photo&view=event&id='.$this->events[$i]->id).'">';
  	echo '<img width="20" height="20" src="'.$mainframe->getImageDir().'my_prev.png"/>';
  	'</a></td>';  	
  	echo '<td align="center">';
  	echo '<a href="'.JRoute::_('index.php?option=com_photo&view=imageupload&id='.$this->events[$i]->id.'&private='.$this->private).'">';
  	echo '<img width="20" height="20" src="'.$mainframe->getImageDir().'my_upload.png"/>';
  	'</a></td>';
  	echo '<td align="center">';
  	echo '<a href="'.JRoute::_('index.php?option=com_photo&view=imageupload&id='.$this->events[$i]->id.'&change=yes&private='.$this->private).'">';
  	echo '<img width="20" height="20" src="'.$mainframe->getImageDir().'my_edit.png"/>';
  	echo '</a></td>';
  	echo '<td align="center">';
  	echo '<a href="'.JRoute::_('index.php?option=com_photo&view=imageupload&task=del&id='.$this->events[$i]->id.'&private='.$this->private).'">';
  	echo '<img width="20" height="20" src="'.$mainframe->getImageDir().'my_del.jpg"/>';
  	echo '</a></td>';  	  	
  	echo '<tr>';
  }
  echo '</table>';
}else{
	echo '<b>Nemate ni jednu galeriju :(</b>';
}  
?>
<br><span style="font-size:14px;font-weight:bold">Uputstvo za upload:</span>
<?php if ($this->private==0x7120){ /* do not work any more */ ?>
<p>Pravljenje galerija: Izaberite iz gornjih padajucih lista kategoriju i lokal za koje pravite galeriju. Klikom na dugme sa tri tacke, koje stoji do polja za unos datuma, dobicete kalendar gde trebate izabrati datum kada se odigrao dogadjaj za koji pravite galeriju. Mozete da unesete i naziv galerije(ukoliko zelite nesto da naglasite kao: 'svirka Neverne Bebe' ili 'Maskenbal party'). U polje opis mozete da unesete recenziju dogadjaja, ali u vecini slucajeva ovo cete ostaviti prazno. Na kraju, izborom dugmeta 'Napravi!' kreirate galeriju.
<p>Upload: Pre svega morate koristiti Firefox za upload slika i potrebno ga je podesiti za to. To se radi na sledeci nacin. Ukucate: about:config u adress bar browsera. U 'spisku' koji dobijete pronadite sledecu stavku: signed.applets.codebase_principal_support i umesto na false, podesite je na true.<br>Na desnoj strani imate prikaz 8 poslednjih galerija koje ste napravili. Klikom na naziv galerije ili na ikonicu za upload dobicete stranicu za upload slika u izabranu galeriju.<br>
Kliknete na 'Choose file...' i izaberite prvu sliku iz foldera iz kog zelite da uploadujete. Nakon toga ce vam, u slucaju da je ovo prvi put da uploadujete slike, iskociti prozor gde treba da cekirate polje gde vas browser pita da li zelite da vam zapamti odgovor i kliknete na 'Allow'. Sve slike iz tog foldera ce biti prepoznate i prikazane. Kliknite na dugme 'Posalji...' i to je to.
</p>
<?php }else{ ?>
<p> Prvo sto je potrebno uraditi je napraviti galeriju. Unesite naziv i opis galerije i kliknite na dugme Napravi.<br><br>Na desnoj strani imate prikaz svih galerija koje ste napravili. Klikom na naziv galerije ili na ikonicu za upload dobicete stranicu za upload slika u izabranu galeriju. Takodje klikom na ikonicu Izmeni menjate podatke galerije. Klikom na ikonicu Pogledaj dobicete prikaz vase galerije.<br>Na stranici za upload izaberite sliku i opciono joj dajte ime. Slike se smestaju u galeriju koju ste predhodno izabrali.</p>
<?php } ?>
</div>
<div style="clear:left;"></div>
</div>
<br><br>
