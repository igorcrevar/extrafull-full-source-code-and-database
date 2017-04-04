<?php 
defined( '_JEXEC' ) or die( 'Restricted access' ); 

function PhotoBuildRoute(&$query)
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

	if(isset($query['a_id']))
	{
		$segments[] = 'a'.$query['a_id'];
		unset($query['a_id']);
	}
	
	if(isset($query['c_id']))
	{
		$segments[] = 'c'.$query['c_id'];
		unset($query['c_id']);
	}
	
	if(isset($query['l_id']))
	{
		$segments[] = 'l'.$query['l_id'];
		unset($query['l_id']);
	}

	if(isset($query['date']))
	{
		$segments[] = $query['date'];
		unset($query['date']);
	}
									
	if(isset($query['image_file_name']))
	{
		$segments[] = $query['image_file_name'];
		unset($query['image_file_name']);
	}
	
	if(isset($query['image_id']))
	{
		$segments[] = $query['image_id'];
		unset($query['image_id']);
	}
	
	if(isset($query['year']))
	{
		$tmp = $query['year'];		
		if (isset($query['month'])) $tmp .= '-'.$query['month'];
		if (isset($query['day'])) $tmp .= '-'.$query['day'];
		$segments[] = $tmp;  
		unset($query['year']);
		unset($query['month']);
		unset($query['day']);
	}
	
	return $segments;
}

function PhotoParseRoute($segments) 
{ 
	$vars = array();

	$vars['view'] = $segments[0];
	$cnt = count($segments);
	if ($cnt > 1) 
	{
		if ($segments[0] == 'events')	
		{
			for ( $i = 1; $i < $cnt; ++$i )
			{
				 switch ( $segments[$i][0] )
				 {
				 	  case 'l':
				 	    $vars['l_id'] = substr($segments[$i],1);
				 	    break;
				 	  case 'c':
				 	    $vars['c_id'] = substr($segments[$i],1);
				 	    break;  
				 	  case 'a':
				 	    $vars['a_id'] = substr($segments[$i],1);
				 	    break;  
				 	  default:  
				 	    $dt = str_replace( ':', '-', $segments[$i] );
				 	    $res = explode('-',$dt);
							$vars['year'] = $res[0];
							if (count($res) > 1) {
								$vars['month'] = $res[1];
							}
							if (count($res) > 2) {
								$vars['day'] = $res[2];
							}
				 }
			}
		}  
		else
		{
		  $vars['id'] = $segments[1];  	  
		  if ($cnt > 2)
			  $vars['image_file_name'] = $segments[2];  		  
			if ($cnt > 3)
			  $vars['image_id'] = $segments[3];  
		}  
	}
	return $vars;
}

?>
