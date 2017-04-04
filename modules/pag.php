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

class Pagination
{
	protected $pages = null;
	public $current = null; 
	protected $step = null;
	protected $params = array();
	protected $type;
	protected $maxPages;
	protected $total;
	protected $header = 0;
	
	public function __construct($total, $limitstart, $limit, $params = null, $type = 'html', $maxPages = 6)
	{
		$limitstart	= (int) max($limitstart, 0);
		$limit		= (int) max($limit, 0);

		if ($limit > $total) {
			$limitstart = 0;
		}

		if ($limitstart >= $total) {
			$limitstart = floor(($total-1) / $limit)*$limit;
		}
   
    $this->step = $limit;  
		$this->pages = ceil( $total / $limit );
	  $this->current = ceil( ($limitstart + 1) / $limit );
	  if ($params == null && $type != 'ajax'){
	  	$params = Basic::requestURI(array('limitstart'));
	  }
	  $this->params = $params;
	  $this->type = $type;
	  $this->maxPages = $maxPages;
	  $this->total = $total;
	}

  public function getHeader(){
  	$this->header = 1;
  }
  
  public function generateLink( $offs, $inner, $class = ''){
  	  if ($class != ''){
  	  	echo '<span class="'.$class.'">';
  	  }
  	  if ($this->type == 'ajax'){
  	  	$a = '<a href="javascript:';
  	  	$tmp = '';
  	  	for ($i = 0; $i < count($this->params); ++$i) {
  	  		$param = &$this->params[$i];
  	  		if ( $i == 0){
  	  			$tmp .= $param.'(';
  	  		}
  	  		else{
  	  			if ($i > 1) $tmp .= ',';
  	  			if ( $param === 'id' ){
  	  				$tmp .= $offs;
  	  			}
  	  			else {
  	  				$tmp .= '\''.$param.'\'';
  	  			}
  	  		}
  	  	}
  	  	$a .= $tmp.')" href="#">'.$inner.'</a>';
	  		echo $a;
  	  }
  	  else {
  	  	$link = $this->params;
  	  	$a = '<a href="'.$link;
  	  	$pos = strpos($link,'?');
  	  	if ( $pos !== false){
  	  		if ($link[strlen($link)-1] != '&'){
  	  			$a .= '&';
  	  		}
  	  	}
  	  	else{
  	  		$a .= '?';
  	  	}
  			$a .= 'limitstart='.$offs.'">'.$inner.'</a>'; 
	  		echo $a;
  	  }
  		if ($class != ''){
  	  	echo '</span> ';
  	  }
  }

	public function getPagesLinks($type=1)
	{		
		global $mainframe;
		$step = $this->step;  
		$pages = $this->pages;
	  $current = $this->current;
	  if ($pages < 2){
	  	 if ($this->header){
	  	 	  echo '<div class="pagination">'.JText::_('PAG_NUMBER').$this->total.'</div>';
	  	 }
	  	 return;
	  }	 
	  if ($type == 0)
	  	echo '<div class="pagination2">';
	  else if ($type == 2)
	  	echo '<span class="pagination3">';	
	  else 
	  	echo '<div class="pagination">';
	  if ($this->header){
	  	echo JText::_('PAG_NUMBER').$this->total;
	  }	
    if ( $current > 1 && $type != 2)
    {
    	 $this->generateLink( ( $current - 2) * $step, '<img src="'.$mainframe->getImageDir().'left.png" />' );
    }
    $tmpR = $tmpL = $this->maxPages / 2;
  	if ( $this->maxPages % 2 == 0 ){
  		--$tmpL;
  	}
    $mystPos = $current - $tmpL;
    $myendPos = $current + $tmpR;
    if ( $myendPos > $pages ){
    	$mystPos -= $this->maxPages - 1;
    	$myendPos = $pages;
    } 
    if ( $mystPos < 1 ){
    	$mystPos = 1;
    }
    /*
    $mystPos = $current > $this->maxPages ? $current - $this->maxPages : 1;
    if ($mystPos + $this->maxPages <= $pages){
    	 $myendPos = $mystPos + $this->maxPages;		         	 
    }
    else {
    	 $myendPos = $pages;
    	 $mystPos = $myendPos - $this->maxPages;
    	 if ($mystPos < 1) $mystPos = 1;
    }
    */
    if ($mystPos > 1){
    	 $this->generateLink( 0, '1', 'pageoff' );
    	 if ($mystPos > 2){
    	 		echo '<span class="pageoff">...</span>';
    	 }
    }
	  for ($i = $mystPos; $i <= $myendPos; ++$i )
	  {
	 	  if ( $i == $current ){
	 	    echo '<span class="pageactive">'.$i.'</span> ';
	 	  }
	 	  else
	 	  {
	 	  	$this->generateLink( $step * ($i - 1), $i.'', 'pageoff' );
	 	  }  
	  } 
    if ($myendPos < $pages){
    	 if ($myendPos + 1 < $pages){
    	 	echo '<span class="pagoff">...</span> ';
    	 }
    	 $this->generateLink( ($pages-1) * $step, $pages.'', 'pageoff' );
    }
    if ( $current < $pages  && $type != 2)
    {
    	$this->generateLink( $current * $step, '<img src="'.$mainframe->getImageDir().'right.png" />' );
    }  
	  if ($type != 2) echo '</div>'; else echo '</span>';
	}
}