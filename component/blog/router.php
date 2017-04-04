<?php 
defined( 'CREW' ) or die( 'Restricted access' ); 

function BlogBuildRoute(&$query)
{
	$segments = array();
	if (isset($query['view'])) {
		$segments[] = $query['view'];
		unset($query['view']);
	}

	if(isset($query['id']))
	{
		$segments[] = $query['id'];
		unset($query['id']);
	}	
	return $segments;
}

function BlogParseRoute($segments) 
{ 
	$vars = array();
	$cnt = count($segments);
  if ($cnt > 0){
  	if (!is_numeric($segments[0])){
			$vars['view'] = $segments[0];
			if ($cnt > 1) 
			  $vars['id'] = $segments[1];  	  	
		}
		else	
		  $vars['id'] = $segments[0];
	}
	return $vars;
}

?>
