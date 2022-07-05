<?php
class Module{
	static $objects = array();

	static function &loadInstance($component){
		if ( !isset(self::$objects[$component]) ){
			include BASE_PATH.DS.'component'.DS.$component.DS.'module.class.php';
			$moduleName = 'module'.ucfirst($component);
			self::$objects[$component] = new $moduleName;
		}
		return self::$objects[$component];
	}

	public function execute($action, $params){
		$methodName = 'execute'.ucfirst($action);
		foreach ($params as $k => $v){
			$this->$k = $v;
		}
		$this->$methodName();
		$attrs = get_object_vars($this);
		foreach ($attrs as $k => $v){
			if ( $k[0] == '_' ) continue;
			$$k = $v;
		}
		include BASE_PATH.DS.'component'.DS.'tmpls'.DS.'_'.$name.'.php';
	}
}