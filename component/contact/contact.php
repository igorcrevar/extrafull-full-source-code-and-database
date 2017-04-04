<?php 
defined('CREW') or die();
$what = JRequest::getString('what','0');
if ($what == 1)
{
	$doc = &Document::getInstance();
	$doc->setTitle('Pravila extrafull.com');
	echo file_get_contents(JPATH_COMPONENT.DS.'pravila.txt');
}else{
?>
<br>
<h3> Pošaljite nam vaše predloge, kritike, sugestije, potražite odgovor na neki problem u korišcenju sajta...NOT!</h3>
<table width="100%">
  <tbody><tr class="sectiontableentry1">
  	<td>
  		Igor</a></center>
    </td>	 		
  	<td>
  		Covek koji je sa 17 bio bog za c++ i asm, a sada je spao na to da programira odvratne php+mysql/html+css+js soc network sajtove i ekstenzije za popularno smece kao sto je phpfox, joomla, alstrasoft, etc... Iako je administrator i programer, te i vlasnik sajta, molim vas da mu se ne obracate ni u kom slucaju, ako imate bilo koji tehnicki problem. Ozbiljno.
  	</td>	
  </tr>	
  <tr class="sectiontableentry1">
  	<td>
  		Bojan</a></center>
    </td>	
  	<td>
  	Vlasnik, menadžer, PR person. Čovek koji ima 2m, 100kg i  producira nikobudžetne trash skečeve. Voli sunce, more, plazmu  keks, seriju 'Otvorena Vrata' i armirane betonske blokove. Ni  njemu nemojte slati pitanja bilo koje vrste.	
  	</td>	
  </tr>	  
</tbody></table>
<?php } ?>
			