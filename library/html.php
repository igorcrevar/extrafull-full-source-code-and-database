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
class JHTML
{
	public static function date($date, $format = null)
	{
		if (!$date) {
		   return '';
		}
		if (is_numeric($date)){
			$date = date('Y-m-d H:i',$date);
		}
    
    if (strpos($date, ' ')) {
      list($dt,$tm) = explode(' ',$date);
    } else {
       $dt = $date;
       $tm = '';
    }
   
		list($y,$m,$d) = explode('-',$dt);
		if ( $format == 'date' )
		{
			 return "$d.$m.$y.";
		}
		else if ( $format == 'date2' )
		{
			 $dy = date( 'N', mktime(0, 0, 0, $m,$d , $y) );
			 $weekdays = explode(',',JText::_('DAYS'));
			 $tmp = $weekdays[$dy-1];
			 return "$tmp, $d.$m.$y.";
		}

	  list($h,$mn) = explode(':',$tm);
	  return "$d.$m.$y. $h:$mn";
	}
	
	public static function genlist($rows,$name,$extra,$left,$right,$selected=null){
		$rv = '<SELECT name="'.$name.'" '.$extra.'>';
		if (count($rows)){
			foreach ($rows as $row){				
				 $rv .= '<OPTION value="'.$row->$left.'"';
				 if ($row->$left == $selected){
				 	 $rv .= ' SELECTED="yes" ';
				 }
				 $rv .= '>'.$row->$right.'</OPTION>';
			}
		}
		$rv .= '</SELECT>';
		return $rv;
	}
	
	public static function _(){
		$args = func_get_args();
		$func = array(new stdClass());
		$func[0] = 'JHTML';
		$func[1] = array_shift($args);
		if ($func[1] == 'select.genericlist'){
			$func[1] = 'genlist';
		}
		return call_user_func_array($func,$args);
	}
	
	public static function image($name,$class=''){
		global $mainframe;
		return '<img src="'.$mainframe->getImageDir().$name.'" '.$class.' />';
	}
  
	public static function profileLink($id,$txt){
		return '<a href="'.Basic::routerBase().'/profil/'.$id.'">'.$txt.'</a>';
	}	 

	public static function galleryLink($id,$txt){
		return '<a href="'.Basic::routerBase().'/galerija/'.$id.'">'.$txt.'</a>';
	}	 

	public static function galleriesLink($c_id=null,$l_id=null,$a_id=null,$date=null, $txt = null){
		$lnk = Basic::routerBase().'/galerije';
		if ($c_id != null){
			 $lnk .= '/c'.$c_id;
		}
		if ($l_id != null){
			 $lnk .= '/l'.$l_id;
		}
		if ($a_id != null){
			 $lnk .= '/a'.$a_id;
		}
		if ($date != null){
			$lnk .= '/'.$date;
		}
		$rv = '<a href="'.$lnk.'">';
		if ($txt != null) $rv .= $txt.'</a>';
		return $rv;
	}	 

	public static function picLink($id,$txt){
		return '<a href="'.Basic::routerBase().'/slika/'.$id.'">'.$txt.'</a>';
	}	 

	public static function picsLink($id,$txt){
		return '<a href="'.Basic::routerBase().'/slike/'.$id.'">'.$txt.'</a>';
	}	 
	
	public static function &getPagination($total, $limitstart, $limit, $params, $type = 'html', $maxPages = 6){
		require_once (BASE_PATH.DS.'modules'.DS.'pag.php');
		$pag = new Pagination( $total, $limitstart, $limit, $params, $type, $maxPages );
		return $pag;
	}

	public static function date2Timestamp($datetime){
		 if (!$datetime) {
		    return 0;
		 }
     $val = explode(" ",$datetime);
     $date = explode("-",$val[0]);
     $time = explode(":",$val[1]);
     return mktime($time[0],$time[1],$time[2],$date[1],$date[2],$date[0]);
	}
	
	public static function lnk($link,$txt,$add = ''){
		return '<a '.$add.' href="'.Basic::uriBase().'/'.$link.'">'.$txt.'</a>';
	}
	
	public static function _time($time){
		$time = time() - $time;
		$days = intval($time / 86400);
		if ( $days > 0 ){
			echo $days.' dana';
		}
		else{
			$h = intval($time / 3600);
			$m = intval($time / 60 % 60);
			if ( $h + $m ){
				if ( $h ) echo $h.' sati ';
				if ( $m ) echo $m.' minuta';
			}
			else{
				echo intval($time % 60).' sekundi';
			}
		}		
	}
}
?>