<?php

function EventsBuildRoute( &$query )
{
	$segments = array();

	if(isset($query['view'])) 
	{
		$segments[] = $query['view'];
		unset($query['view']);
	};
	
	if (isset( $query['year'] ))
	{
		$segments[] = $query['year'];
		unset( $query['year'] );
	};
	if (isset( $query['month'] ))
	{
		$segments[] = $query['month'];
		unset( $query['month'] );
	};
	if (isset( $query['day'] ))
	{
		$segments[] = $query['day'];
		unset( $query['day'] );
	};
  if (isset( $query['id'] ))
	{
		$segments[] = $query['id'];
		unset( $query['id'] );
	};	
	return $segments;
}

function EventsParseRoute( $segments )
{
	$vars = array();
  $vars['view'] = $segments[0];	
	$cnt = count($segments);
	if ($cnt > 1 ){
	  if ($vars['view']!='list'){
		  $vars['id'] = $segments[1];
	  }
	  else{
			$vars['year'] = $segments[1];	  	
			if ($cnt > 2) $vars['month'] = $segments[2];	  	
			if ($cnt > 3) $vars['day'] = $segments[3];	  	
	  }
	} 
	return $vars;
}