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

class JModel
{
	protected $_name;
	protected $_db;
	protected $_error;
  
	public function __construct($name)
	{
		$this->_db = &Database::getInstance();
		$this->_name = $name;
		$this->_error = '';
	}

	public function addError($_error){
		$this->_error = $_error;
	}
	
	public function getError(){
		return $this->_error;
	}
	
	public function &getDBO()
	{
		return $this->_db;
	}

	public function setDBO(&$db)
	{
		$this->_db =& $db;
	}
	
	public function getName()
	{
		return $this->_name;
	}
	
	public function _getList( $query, $limitstart = -1, $limit = -1 )
	{
		$this->_db->setQuery( $query, $limitstart, $limit );
		//echo $this->_db->query;
		$result = $this->_db->loadObjectList();
		return $result;
	}

}