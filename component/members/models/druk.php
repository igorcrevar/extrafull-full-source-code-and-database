<?php
/*=============================================================================
|| ##################################################################
||	Igor Crevar Extrafull
|| ##################################################################
||
||	Copyright		: (C) 2007-2009 Igor Crevar
||	Contact			: crewce@hotmail.com
||
||	- Extrafull and all of its source code and files are protected by Copyright Laws.
||
||	- You can not use any of the code without Igor Crevar agreement
||
||	- You may also not remove this copyright screen which shows the copyright information and credits for Extrafull (Igor Crevar).
||
||	- Igor Crevar Extrafull is NOT a FREE software
||
|| ##################################################################
=============================================================================*/
 defined( 'CREW' ) or die( 'Restricted access' );
 
class MembersModelDruk extends JModel
{  	
	function loadDruks()
	{
	  $user = &JFactory::getUser();
		$id = $user->id;
		$find = -1;
		$name = $user->username;
		$query = "SELECT id,name,score FROM #__druk ORDER BY score DESC";
	  $rows = $this->_getList( $query );
	  $rv = '<table border="1" width="380"><tr><th width="80">Pozicija</th><th>Korisnik</th><th width="50" align="center">Skor</th></tr>';
	  for ($i=1;$i<=count($rows) && $i<=20; ++$i)
	  {
	  	$row = $rows[$i-1];
	  	$un=$row->name;
	  	if ($row->id==$id){ $find=$i-1; $un="<b>$un</b>";}
	  	$rv.='<tr><td>';
	  	$profile = JRoute::_('index.php?option=com_members&view=profile&id='.$row->id);
	  	$rv.="$i</td><td><a href=\"$profile\">$un</a></td><td align=\"center\">".$row->score;	  	
	  	$rv.='</td></tr>';
	  }
	  if ($find==-1   && $id>=52)
	  {
	  	 for ($i=20;$i < count($rows);++$i)
	  	   if ($rows[$i]->id==$id) $find=$i;
	  	 if ($find >= 20)
	  	 {
	  	 	 if ($find > 20)
	  	 	 { 
  	 	    $rv.='<tr><td>...</td><td>...</td><td>...</td></tr>';
	  	 	  $row=$rows[$find-1];
  	 	    $rv.='<tr><td>';
	  	    $profile = JRoute::_('index.php?option=com_members&view=profile&id='.$row->id);
	  	    $rv.="$find</td><td><a href=\"$profile\">".$row->name.'</a></td><td align="center">'.$row->score;	  	
	  	    $rv.='</td></tr>';
	  	   } 
	  	 	 $row=$rows[$find];
  	 	   $rv.='<tr><td>';
	  	   $profile = JRoute::_('index.php?option=com_members&view=profile&id='.$row->id);
	  	   $i=$find+1;
	  	   $rv.="$i</td><td><a href=\"$profile\"><b>".$row->name.'</b></a></td><td align="center">'.$row->score;	  	
	  	   $rv.='</td></tr>';
	  	 }
	  }	  
 	  $rv .= '</table>';
	  return $rv;
	}
  function addYour()
  {
		$user = &JFactory::getUser();
		$id = $user->id;
		if ($id<52) return false;
		$name = $user->username;
		$new_score = JRequest::getInt('new_score','0','POST');
		$query = "SELECT score FROM #__druk WHERE id=$id";
		$db = $this->getDBO();	
		$db->setQuery($query);	
	  $score = $db->loadResult();
	  if ( isset($score) )
	  {	  	
	  	if ( $score >= $new_score ) return;
  	  $query = "UPDATE #__druk SET score=$new_score WHERE id=$id";
	  }
	  else
	  {
	    $query = "INSERT INTO #__druk (id,name,score) VALUES ($id,'$name',$new_score)";
	  }
	  $db->setQuery($query);
	  return $db->query();	  
  }
  
}
?>	