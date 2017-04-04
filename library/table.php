<?php
/*=============================================================================
|| ##################################################################
||	Extrafull
|| ##################################################################
||
||	Copyright		: (C) 2007-2009 Igor Crevar
||
|| ##################################################################
=============================================================================*/
defined('CREW') or die();

define('PAGE_MAX', 20);

class Table{
	protected $colonsNames;
	protected $colonsTypes; // $key => $type
	protected $colonsParams; //$key => array(param1, param2)
	protected $colonsLinks; //$key => array($colon,$link)
	protected $sql;
	protected $offset;
	protected $page;
	protected $sortColon;
	protected $sortDirection;
	protected $groupKey;	
	protected $pageSQL;
	protected $imagesPath;
	protected $link;
	
	function __construct( $link, $sql = '', $colonsNames = array(), $colonsTypes = array(), $colonsParams = array(), $colonsLinks = array(), $pageSQL = '', $groupKey = '', $page = -1 ){
		$this->link = $link;
		$this->colonsNames = $colonsNames;
		$this->colonsTypes = $colonsTypes;
		$this->colonsLinks = $colonsLinks;
		$this->colonsParams = $colonsParams;
		$this->sql = $sql;	
		$this->page  = $page > PAGE_MAX ? PAGE_MAX : $page;
		$this->groupKey = $groupKey;
		$this->pageSQL = $pageSQL;
		$this->sortColon = JRequest::getInt('sortColon', 0 );
		$this->sortDirection = JRequest::getInt('sortDirection', 0 );
		$this->offset = JRequest::getInt('offset', 0 );
	}
	
	public function show(){
		$db = &JFactiry::getDBO();
		$db->setQuery( $this->generateSQL() );
		$res = $db->loadObjectList();
		echo '<table id="tTable" cellspacing="1" cellpadding="0">';
		echo '<tr>';	
		if ( $this->groupKey != ''){
			 echo '<td class="tGroup" >';
			 echo '<input type="checkbox" name="groupAll" onclick="for (i=1;i<='.count($res).';++i){ a=document.getElementById(\'tGroup\'+i); a.checked=1-a.checked;}" />'; 
			 echo '</td>';
		}
		$i = 0;
		foreach ($this->colonsNames as $key => $colonName){
			$tmp = $this->sortDirection;
			$tmp2 = $this->sortColon;
			$class = ($i == $this->sortColon) ? 'tColonActive' : 'tColon';
			echo '<td class="'.$class.'">'.$colonName;
			if ( $i == $this->sortColon ){					
					$class = $this->sortDirection == 1 ? 'tSortDesc' : 'tSortAsc';
					$this->sortDirection = 1 - $this->sortDirection;
			}
			else{
				$this->sortDirection = 0;
				$class = 'tSortAsc';
			}			
			$this->sortColon = $i;			
			echo $this->generateLink( '<span class="'.$class.'"></span>');
			echo '</td>';
			$this->sortColon = $tmp2;
			$this->sortDirection = $tmp;
			++$i;
		}
		echo '</tr>';		
		if ( count( $res ) > 0 ){
			 $i = 0;
			 foreach ($res as $row){
			 	  ++$i;
			 	  echo '<tr>';
 	  	 	 	if ( $this->groupKey != ''){
 	  	 	 	 	$val = $this->groupKey;
 	  	 	 	 	$val = $row->$val;
						echo '<td class="tGroup">';
	 					echo '<input type="checkbox" id="tGroup'.$i.'" name="tGroup'.$i.'" value="'.$val.'" />'; 
	 					echo '</td>';
 					}			 	  
			 	  foreach ($this->colonsNames as $key => $colonName){
			 	  	 echo '<td class="t'.$this->colonsTypes[$key].'">';
			 	  	 if ( isset($this->colonsLinks[$key]) ){
			 	  	 	 $val = $this->colonsLinks[$key];
			 	  	 	 $link = $val[0];
			 	  	 	 $linkKey = $val[1];
			 	  	 	 if (strpos($link,'%s') === false){
			 	  	 	 		$link = $link.$row->$linkKey;
			 	  	 	 }
			 	  	 	 else{
			 	  	 	 	  $link = sprintf($link, $row->$linkKey);
			 	  	 	 }
			 	  	 	 echo '<a href="'.$link.'">';
			 	  	 }
			 	  	 switch ( $this->colonsTypes[$key] ){
			 	  	 	 case 'date': 
			 	  	 	 		$val = $db->fromDate( $row->$key ); 
			 	  	 	 		echo $val;
			 	  	 	 		break;
			 	  	 	 case 'int': case 'string': case 'float':
			 	  	 	    $val = $row->$key; 
			 	  	 	 		echo $val;
			 	  	 	   	break;
			 	  	 	 case 'image': case 'img': 	
			 	  	 	    if ($row->$key != null  &&  $row->$key != '' ){
				 	  	 	    $val = $row->$key;
				 	  	 	    $path = $this->colonsParams[$key];//[0];
				 	  	 	    if ( file_exists(JPATH_BASE.DS.$path.DS.$val) ){
				 	  	 	    //Basic	klasa se ucitava u php fajlu koji poziva ovaj
			 	  	 	    		echo '<img src="/'.$path.'/'.$val.'"/>';
			 	  	 	    	}
			 	  	 	    } 			 	  	 	 		
			 	  	 	 		break;
			 	  	 }
			 	  	 if ( isset($this->colonsLinks[$key]) ){
			 	  	 	  echo '</a>';
			 	  	 }
			 	  	 echo '</td>';
			 	  }
			 	  echo '</tr>';
			 }
			 echo '</table>';
			 if ($this->page > 0){
			 	  require_once( JPATH_BASE.'libraries'.DS.'joomla'.DS.'pag.php');
			 	  $db->setQuery( $this->pageSQL );
			 	  $res = $db->loadResult( );			 	  
			 	  $link = $this->generateLink(null);
			 	  $pag = new Pagination( $res, $this->offset, $this->page, $link );
			 	  echo '<br>';
			 	  $pag->getPagesLinks();
			 } 
		}
		else{
			echo '</table>Nema ni jednog podatka u tabeli.';
		}
	}	
	
  protected function generateSQL(){
  	$sortColonName = '';
 		$query = $this->sql;
  	$i = 0;
  	foreach ($this->colonsNames as $key => $colonName){
  		if ( $i == $this->sortColon ){
  			$sortColonName = $key;
  		}
  		++$i;
  	}	
    $query .= ' ORDER BY '.$sortColonName;
    $query .= $this->sortDirection == 0 ? ' ASC' : ' DESC';
    if ( $this->offset > 0 ){
    	 $query .= ' LIMIT '.$this->offset;
    }
    if ( $this->page > 0 ){
    	if ( $this->offset > 0 ){
    		$query .= ',';
    	}
    	else{
    		$query .= ' LIMIT ';
    	}
   	 	$query .= $this->page;
    }
    return $query;
  }
  
  protected function generateLink($inner){
  	$link = $this->link;
  	if ( strpos($this->link, '?') === false ){
  		 $link .= '?';
  	}
  	else{
  		$link .= '&';
  	}
  	$link .= 'sortColon='.$this->sortColon;
  	$link .= '&sortDirection='.$this->sortDirection; 
  	if ($inner == null){	
  		 return $link;
  	}
  	else	
  	{
  		$link .= '&offset='.$this->offset;
  		return '<a href="'.$link.'">'.$inner.'</a>';
    }		
  }
}
