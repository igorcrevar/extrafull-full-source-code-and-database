<?php
function include_module($component,$action,$params = array() ){
	$object = &Module::loadInstance($component);
	$object->execute($action, $params);	
}
function url_for($url,$full = false){
	$parsed = !$full ? Basic::routerBase() : Basic::uriBase();
	$pos = strpos( '?', $url);
	if ( $pos !== false ){
		$parsed = $parsed.substr($url,0,$pos);
		$params = explode( '&', substr($url,$pos+1) );
		foreach ($params as $k => $v ){
			$parsed .= '/';
			$parsed .= $k.'_'.urlencode($v);
		}
		return $parsed;
	}
	return $parsed.$url;
}
function seo_title($title){
	return trim( preg_replace( '/ +/', '-', preg_replace('/[^a-zA-Z ]+/', ' ', $title) ) , '-' );
}
