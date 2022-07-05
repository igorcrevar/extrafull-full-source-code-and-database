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
class JController {
	protected $_name = null;
	protected $_doTask 	= null;
	protected $_taskMap = null;
	protected $_redirect 	= null;
	protected $_message 	= null;

	function __construct(){
		//Initialize private variables
		$this->_redirect	= null;
		$this->_message		= null;
		$this->_taskMap		= array();
		$this->_name = '';
		$this->_doTask = null;

		$thisMethods	= get_class_methods( get_class( $this ) );
		$baseMethods	= get_class_methods( 'JController' );
		$methods		= array_diff( $thisMethods, $baseMethods );
		$methods[] = 'display';
		foreach ( $methods as $method )
		{
			if ( substr( $method, 0, 1 ) != '_' ) { //
				$this->_taskMap[strtolower( $method )] = $method;
			}
		}
	}

	function setName($cn){
		$this->_name = strtolower($cn);
	}

	function execute( $task ){
		$task = strtolower( $task );
		if (isset( $this->_taskMap[$task] )) {
			$doTask = $this->_taskMap[$task];
			$this->_doTask = $doTask;
			$this->$doTask();
		}
		else {
			//TBD error task
			die('Ne postoji taj kontroler');
		}
	}

	function redirect(){
		if ($this->_redirect == null) return;
		global $mainframe;
		$mainframe->redirect(	$this->_redirect, $this->_message );
	}

	function getModel( $name = '', $prefix = '' ){
		$name	 = preg_replace( '/[^A-Z0-9_]/i', '', $name );
		$prefix = preg_replace( '/[^A-Z0-9_]/i', '', $prefix );
		$name = strtolower($name);
		$prefix = strtolower($prefix);

		$path = BASE_PATH.DS.'component'.DS.$this->_name.DS.'models'.DS.$name.'.php';
		if ( !file_exists($path) ) die();
		require_once $path;
		$modelname = $prefix.$name;
		if ( !class_exists($modelname) ) die();
		return new $modelname($modelname);
	}

	function getView( $name = '', $type = '', $prefix = '' ){
		if ($prefix == ''){
			$prefix = ucfirst($this->_name).'View';
		}
		$name	 = preg_replace( '/[^A-Z0-9_]/i', '', $name );
		$type = preg_replace( '/[^A-Z]/i', '', $type );
		$prefix = preg_replace( '/[^A-Z0-9_]/i', '', $prefix );
		$name = strtolower($name);
		$prefix = strtolower($prefix);
		$name = strtolower($name);
		$type = strtolower($type);

		$path = BASE_PATH.DS.'component'.DS.$this->_name.DS.'views'.DS.$name.DS.'view.'.$type.'.php';
		if ( !file_exists($path) ) die();
		require_once $path;
		$viewname = $prefix.$name;
		if ( !class_exists($viewname) ) die();
		return new $viewname($name,$this->_name );
	}

	function registerTask( $task, $method ){
		if ( in_array( strtolower( $method ), $this->_methods ) ) {
			$this->_taskMap[strtolower( $task )] = $method;
		}
	}

	function setMessage( $text ){
		$previous		= $this->_message;
		$this->_message = $text;
		return $previous;
	}

	function setRedirect( $url, $msg = null ){
		$this->_redirect = $url;
		if ($msg !== null) {
			$this->_message	= $msg;
		}
	}

	function forward($name = null, $params = null){
		if ( $params != null ){
			$params = explode( '&', $params );
			foreach ($params as $param){
				list($key,$val) = explode('=',$param);
				JRequest::setVar( $key, $val );
			}
		}
		if ( $name != null ){
			require_once( BASE_PATH.DS.'component'.DS.$name.DS.'controller.php' );
			$controller = new ucfirst($name).'Controller';
			$controller->setName(ucfirst($name));
			$controller->execute(JRequest::getVar('task', 'display', 'default', 'cmd'));
		}
		else{
			$this->execute(JRequest::getVar('task', 'display', 'default', 'cmd'));
		}
	}
}