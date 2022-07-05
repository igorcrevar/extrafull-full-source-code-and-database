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

require_once(BASE_PATH.DS.'config.php');
require_once(BASE_PATH.DS.'database.php');
require_once(BASE_PATH.DS.'document.php');
require_once(BASE_PATH.DS.'session.php');
require_once(BASE_PATH.DS.'basic.php');
require_once(BASE_PATH.DS.'user.php');
require_once(BASE_PATH.DS.'text.php');
require_once( BASE_PATH.DS.'cache.php' );
require_once( BASE_PATH.DS.'banned.php' );

require_once(BASE_PATH.DS.'library'.DS.'controller.php');
require_once(BASE_PATH.DS.'library'.DS.'model.php');
require_once(BASE_PATH.DS.'library'.DS.'view.php');
require_once(BASE_PATH.DS.'library'.DS.'html.php');

require_once(BASE_PATH.DS.'library'.DS.'request.php');

require_once(BASE_PATH.DS.'string.php');

$mainframe = new Application();
$mainframe->init();

class Application{
	protected $language;
	protected $template;
	
	public function init(){
		if ( isset($_COOKIE['template']) ){
			$tmp = $_COOKIE['template'];
			switch ( $tmp ){
				case 'cool': break;
				default: $tmp = DEFAULT_TEMPLATE; break;
			}
			$this->template = $tmp;
		}
		else{
			$this->template = DEFAULT_TEMPLATE;
		}
				
		if ( isset($_COOKIE['lang']) ){			
			$lang = $_COOKIE['lang'];
			switch ( $lang ){
				case 'en-GB': break;
				default: $lang = DEFAULT_LANGUAGE; break;
			}
			$this->language = $lang;
		}
		else{
			$this->language = DEFAULT_LANGUAGE;
		}
	}
	
	public function setLanguage($lng){
		switch ( strtolower($lng) ){
			case 'srpski': $lng = 'sr-RS'; break;
			case 'engleski': $lng = 'en-GB'; break;
			default: return;
		}
		setcookie( 'lang', $lng, time() + 4*365*24*60*60, '/' );
		$this->language = $lng;
	}
	
	public function getLanguage(){
		return $this->language;
	}
	
	public function getImageDir(){
		return Basic::uriBase().'/template/'.$this->template.'/images/';
	}
	
	public function getTemplate(){
		return $this->template;
	}
	
	public function loadLanguage($component = null){
		 require_once(BASE_PATH.DS.'langs'.DS.$this->language.DS.$this->language.'.php');
		 if ($component != null){
		 	  $path = BASE_PATH.DS.'langs'.DS.$this->language.DS.$this->language.'.'.$component.'.php';
		 	  if ( file_exists($path) ){
		 	  	require_once($path);
		 	  } 
		 }		 
	}
	
	public function redirect($url = null, $msg = null){
		$sess = &Session::getInstance();
		if ($msg != null){			
			$sess->set('usermsgid',$msg);
		}
		$sess->set('redirect', 'yes' );
		if ($url == null){
			$url = Basic::uriBase();
		}
		else if (substr($url,0,5) == 'index'){
			$url = Basic::uriBase().'/'.$url;
		} 
		else if (Basic::routerBase() && substr($url, 0, strlen(Basic::routerBase())) !== Basic::routerBase() && 
				substr($url, 0, strlen(Basic::uriBase())) !== Basic::uriBase()) {
			if ($url && $url[0] !== '/'){
				$url = Basic::routerBase().'/'.$url;  
			}
			else {
				$url = Basic::routerBase().$url;
			}
		}
		if (headers_sent()) {
			echo "<script>document.location.href='$url';</script>\n";
		} else{
			@ob_end_clean(); // clear output buffer
			header( 'HTTP/1.1 301 Moved Permanently' );
			header( 'Location: ' . $url );
		}
		//session_write_close();//sesija se mora zapisati pre redirekcije sa headerom
		//Database::getInstance()->release();
		exit(0);
	}
}
