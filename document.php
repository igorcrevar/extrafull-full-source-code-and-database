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
class Document{
	public $title;
	public $description = DESCRIPTION;
	public $keywords = KEYWORDS;
	public $styleSheets = array();
  	public $scripts = array();
	public $type;
	protected $onLoad;
	public static $document = null;
  
	public static function &getInstance(){
		if (self::$document == null){
			self::$document = new Document();
		}
		return self::$document;
	}	
	
	public function __construct(){
   		$this->type = isset($_REQUEST['format']) && $_REQUEST['format'] == 'raw' ? 'raw' : 'html';
	}
	
	public function setTitle($title){
		$this->title = $title;
	}
	
	public function getType(){
		return $this->type;
	}
	
	public function setDescription($desc){
		$this->description = $desc;
	}

	public function setKeywords($keys){
		$this->keywords = $keywords;
	}

	public function addStyleSheet($url){
		if ( !in_array($url, $this->styleSheets) ){
			$this->styleSheets[] = $url;
		}
	}

	public function addScript($url){
		if ( !in_array($url, $this->scripts) ){
			$this->scripts[] = $url;
		}		
	}
	
	public function toString(){
		echo '<title>'.$this->title.'</title>';			
		echo '<meta content="'.$this->description.'" name="description"/>';
		echo '<meta content="'.$this->keywords.'" name="keywords"/>';
		foreach ($this->styleSheets as $styleURL){
			echo '<link href="'.$styleURL.'"  type="text/css" rel="stylesheet"></link>';
		}
		foreach ($this->scripts as $scriptURL){
			echo '<script src="'.$scriptURL.'" type="text/javascript" language="javascript"></script>';
		}
	}
	
	public function addOnLoad($jsFnc){
		if ( !isset($this->onLoad) ){
			$this->onLoad = array();
		}
		$this->onLoad[] = $jsFnc;
	}
  
	public function getOnLoad(){
		if ( !isset($this->onLoad) ) return '';
		return ' onload="'.implode( ';', $this->onLoad).'"';
	}
}	