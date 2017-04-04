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
class Database{
  public $connection;
  public $error;
  protected $magic_quotes;
  public $query = '';
  protected static $dbo = null;	
	
	public function __construct(){
		$this->error = 0;
		$this->connection = @mysql_connect( DB_HOST, DB_USER, DB_PASSWORD, true ); 
    if ( !$this->connection ){
			//$this->error = 1;
			echo 'Database greska:previse konekcija';
			exit(0);
		} 
		mysql_query( "SET NAMES 'utf8'", $this->connection );
		if ( !mysql_select_db( DB_NAME, $this->connection ) ){
			//$this->error = 2;
      echo 'Database '.DB_NAME.' does not exist';
			exit(0);
		} 		
		$this->magic_quotes = get_magic_quotes_gpc();
		// Register faked "destructor" in PHP4 to close all connections we might have made
		/*if (version_compare(PHP_VERSION, '5') == -1) {
			register_shutdown_function( array(&$this, '__destruct') );
		}	*/
	}

	public function __destruct()
	{
		$this->release();
	}
		
	public function setQuery($query, $offset = -1, $limit = -1){	
		if ($offset >= 0){
			 $query .= ' LIMIT '.$offset;
			 if ($limit >= 0){
			 	 $query .= ','.$limit;
			 }
		}	
	  $this->query = str_replace( '#__', DB_PREFIX, $query );
	}
	
	public static function &getInstance(){	
		if (self::$dbo == null){
			  self::$dbo = new Database();
		}	 
		return self::$dbo;
	}
	
	public function isError(){
		return $this->error != 0;
	}
	
	public function query( $query = null, $update = false ){
	  if ($query == null){
	    $query = $this->query;
	  }
	  else{
	  	$query = str_replace( '#__', DB_PREFIX, $query );
	  }  
		if ( $update ){
			$res = mysql_query( $query, $this->connection );
			return mysql_affected_rows($this->connection);
		}
		else {
			return mysql_query( $query, $this->connection );
		}		
	}
	
	public function loadResultArray($numinarray=0){
		$result = mysql_query( $this->query, $this->connection );
		if ( !$result ) {
			$this->error = 3;
			return null;
		}
		$array = array();
		while ($row = mysql_fetch_row( $result )) {
			$array[] = $row[$numinarray];
		}
		mysql_free_result( $result );
		return $array;
	}
	
	public function loadObjectList( $query = null ){
	  if ($query == null){
	    $query = $this->query;
	  }  
		$result = mysql_query( $query, $this->connection );
		if ( !$result ) {
			$this->error = 3;
			return null;
		}
		$array = array();
		while ( $row = mysql_fetch_object( $result ) ) {
			$array[] = $row;
		}
		mysql_free_result( $result );
		return $array;
	}
	
	public function loadObject( $query  = null){		
	  if ($query == null){
	    $query = $this->query;
	  }  
		$result = mysql_query( $query, $this->connection );
		if ( !$result ) {
			$this->error = 3;
			return null;
		}
		$ret = null;
		if ( $object = mysql_fetch_object( $result ) ) {
			$ret = $object;
		}
		mysql_free_result( $result );
		return $ret;
	}	
	
	public function loadResult( $query = null ){
	  if ($query == null){
	    $query = $this->query;
	  }  
		$result = mysql_query( $query, $this->connection );
		if ( !$result ) {
			$this->error = 3;			
			return null;
		}
		$ret = null;
		if ($row = mysql_fetch_row( $result )) {
			$ret = $row[0];
		}
		mysql_free_result( $result );
		return $ret;
	}	
	
	public function queryBatch(){
		$this->error = 0;
		/*if (substr($this->query,strlen($this->query) - 1) != ';'){
			$this->query .= ';';
		}*/
		$sql = 'START TRANSACTION;'.$this->query.'COMMIT';
		$sqls = explode(';',$sql);
		foreach($sqls as $sql){
			 $val = mysql_query( $sql, $this->connection );
			 if (!$val){
			 	  $val = mysql_query( 'ROLLBACK', $this->connection );
			 		$this->error = 3;
		 			break;
			 }
		}
		return $this->error == 0 ? true : false;
	}
	
	public function release(){		
		$return = false;
		if ( is_resource($this->connection) ) {
			$return = mysql_close($this->connection);
			$this->connection = null;
		}
		return $return;
	}
	
	public function escape( $text ){
	  return $this->getEscaped($text);
	}
	
	//zbog jooomla kompatibilnosti
	public function getEscaped( $text ){
	  if ( $this->magic_quotes ) {
       $text = stripslashes( $text );
    }
    $text = mysql_real_escape_string( $text,$this->connection );
    return $text;
	}
		
	public function Quote( $text ){
		return '\''.$this->escape( $text ).'\'';
	}
			
	public function toDate( $date ){
		 list($day, $month, $year) = explode('.', $date);
		 return $year.'-'.$month.'-'.$day;
	}

	public function fromDate( $date ){
		 list($year, $month, $day) = explode('-', $date);
		 return $day.'.'.$month.'.'.$year.'.';
	}
}