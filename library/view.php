<?php
/*=============================================================================
|| ##################################################################
||	Extrafull
|| ##################################################################
||
||	Copyright		: (C) 2007-2009 Igor Crevar
||
|| ##################################################################
|| FILE VERSION: 14.III.2009 1:51
=============================================================================*/
defined('CREW') or die();

class JView{
	protected $_name;
	protected $_componentName;
	protected $_models = array();
	protected $_defaultModel = null;
	protected $_layout = 'default';

  	function __construct( $name, $componentName ){
		$this->_name = $name;
		$this->_componentName = $componentName;
	}

	function display($tpl = null){
		$this->loadTemplate($tpl);
	}

	function loadTemplate( $tpl = null, $directOutput = true){
		global $mainframe;
		$appTmpl = $mainframe->getTemplate();
		$file = isset($tpl) ? $this->_layout.'_'.$tpl : $this->_layout;
		$file = preg_replace('/[^A-Z0-9_]/i', '', $file);
		if ( $appTmpl == 'normal' ){
			$path = BASE_PATH.DS.'component'.DS.$this->_componentName.DS.'views'.DS.$this->_name.DS.'tmpl'.DS.$file.'.php';
			if ( !file_exists($path) ){ return; }
		}
		else{
			$path = BASE_PATH.DS.'template'.$appTmpl.DS.'cmps'.DS.$this->_componentName.DS.'views'.DS.$this->_name.DS.'tmpl'.DS.$file.'.php';
			if ( !file_exists($path) ){
				$path = BASE_PATH.DS.'component'.DS.$this->_componentName.DS.'views'.DS.$this->_name.DS.'tmpl'.DS.$file.'.php';
				if ( !file_exists($path) ){ return; }
			}
		}

		if ($directOutput){
			include $path;
		}
		else{
			ob_start();
			include $path;
			$tmp = ob_get_contents();
			ob_end_clean();
			return $tmp;
		}
	}

	public function loadParticle($_component,$_name, $_params = array(), $_directOutput = true){
		global $mainframe;
		$_appTmpl = $mainframe->getTemplate();
		$_name = '_'.preg_replace('/[^A-Z0-9_]/i', '', $_name);
		if ( $_appTmpl == 'normal' ){
			$_path = BASE_PATH.DS.'component'.DS.$_component.DS.'tmpl'.DS.$_name.'.php';
			if ( !file_exists($_path) ){ return; }
		}
		else{
			$_path = BASE_PATH.DS.'template'.$appTmpl.DS.'cmps'.DS.$_component.DS.'tmpl'.DS.$_name.'.php';
			if ( !file_exists($_path) ){
				$_path = BASE_PATH.DS.'component'.DS.$_component.DS.'tmpl'.DS.$_name.'.php';
				if ( !file_exists($_path) ){ return; }
			}
		}
		foreach ($_params as $_key => $_val){
			$$_key = $_val;
		}
		if ($_directOutput){
			include $_path;
		}
		else{
			ob_start();
			include $_path;
			$tmp = ob_get_contents();
			ob_end_clean();
			return $tmp;
		}
	}

	function assignRef($key, &$val, $parse = true ){
		/*hajde da sebi verujem */
		$this->$key = &$val;
		/*if (is_string($key) && substr($key, 0, 1) != '_')
		{
			$this->$key =& $val;
			return true;
		}

		return false;*/
	}

	function getModel( $name = null ){
		if ($name === null) {
			$name = $this->_defaultModel;
		}
		return $this->_models[strtolower( $name )];
	}

	function getLayout(){
		return $this->_layout;
	}

	function getName(){
		$name = $this->_name;
		return $name;
	}

	function setModel( &$model, $default = false ){
		$name = strtolower($model->getName());
		$this->_models[$name] = &$model;

		if ($default) {
			$this->_defaultModel = $name;
		}
		return $model;
	}

	function setLayout($layout){
		$previous		= $this->_layout;
		$this->_layout = $layout;
		return $previous;
	}


	function load( $component = '', $viewname = '', $params = array(), $display = true, $type = '' ){
		if ( empty($comp) ){
			$component = $this->_name;
		}
		$name	 = preg_replace( '/[^A-Z0-9_]/i', '', $viewname );
		$type = preg_replace( '/[^A-Z]/i', '', $type );
		$name = strtolower($name);
		$type = strtolower($type);

		$path = BASE_PATH.DS.'component'.DS.$component.DS.'views'.DS.$name.DS.'view.'.$type.'.php';
		if ( !file_exists($path) ) die();
		require_once $path;
		$viewname = ucfirst($component).'View'.$name;
		if ( !class_exists($viewname) ) die();
		$view = new $viewname($name, $component );
		foreach ($params as $k => $v){
			$view->assignRef( $k, $v );
		}
		if ( $display ){
			$view->display();
		}
	}
}