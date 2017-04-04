<?php 
defined( '_JEXEC' ) or die( 'Restricted access' ); 

function MembersBuildRoute(&$query)
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

	if(isset($query['c_id']))
	{
		$segments[] = 'c'.$query['c_id'];
		unset($query['c_id']);
	}
 
	return $segments;
}

function MembersParseRoute($segments) 
{ 
	$vars = array();

	$vars['view'] = $segments[0];
	if ( count($segments) > 1 )
	  $vars['id'] = $segments[1];
	if ( count($segments) > 2 )
	  $vars['c_id'] = substr($segments[2],1);
	
	return $vars;
}

?>
