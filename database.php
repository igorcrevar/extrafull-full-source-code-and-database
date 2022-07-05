<?php
/*=============================================================================
|| ##################################################################
||	Extrafull
|| ##################################################################
||
||	Copyright		: (C) 2007-2009 Igor Crevar
||
|| ##################################################################
|| FILE VERSION: 5.VII.2022 15:51
=============================================================================*/
defined('CREW') or die();
class Database{
	public $connection;
	public $error;
	public $query = '';
	protected $magic_quotes;
	protected static $dbo = null;	
	
	private function __construct(){
		$this->error = 0;
		$this->connection = @mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME ); 
		
		if (mysqli_connect_errno()) {
	  	    echo "Failed to connect to MySQL: " . mysqli_connect_error();
		    exit(0);
		}

		$this->magic_quotes = false; // was $this->magic_quotes = get_magic_quotes_gpc();
		mysqli_set_charset( $this->connection, DB_CHARACTER_SET ); // was mysqli_query( $this->connection, "SET NAMES 'utf8'" );				
		// Register faked "destructor" in PHP4 to close all connections we might have made
		/*if (version_compare(PHP_VERSION, '5') == -1) {
			register_shutdown_function( array(&$this, '__destruct') );
		}*/
	}

	public function __destruct(){
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
	
	public function getInsertId() {
		return mysqli_insert_id($this->dbo);
	}

	public function query( $query = null, $update = false ){
	  	if ($query == null){
	    	$query = $this->query;
		}
		else{
			$query = str_replace( '#__', DB_PREFIX, $query );
		}
		if ( $update ){
			mysqli_query( $this->connection, $query );
			return mysqli_affected_rows($this->connection);
		}
		else {
			$result = mysqli_query( $this->connection, $query );
			if (!$result) {
				$this->error = 3;
			}
			return $result;
		}		
	}
	
	public function loadResultArray($numinarray=0){
		$result = $this->query();
		if ( !$result ) {
			return null;
		}
		$array = array();
		while ($row = mysqli_fetch_row( $result )) {
			$array[] = $row[$numinarray];
		}
		mysqli_free_result( $result );
		return $array;
	}
	
	public function loadObjectList( $query = null ){
		$result = $this->query( $query );
		if ( !$result ) {
			return null;
		}
		$array = array();
		while ( $row = mysqli_fetch_object( $result ) ) {
			$array[] = $row;
		}
		mysqli_free_result( $result );
		return $array;
	}
	
	public function loadObject( $query  = null){		
		$result = $this->query( $query );
		if ( !$result ) {
			return null;
		}
		$ret = null;
		if ( $object = mysqli_fetch_object( $result ) ) {
			$ret = $object;
		}
		mysqli_free_result( $result );
		return $ret;
	}	
	
	public function loadResult( $query = null ){
		$result = $this->query( $query );
		if ( !$result ) {
			return null;
		}
		$ret = null;
		if ($row = mysqli_fetch_row( $result )) {
			$ret = $row[0];
		}
		mysqli_free_result( $result );
		return $ret;
	}	
	
	public function queryBatch(){
		$this->error = 0;
		/*if (substr($this->query,strlen($this->query) - 1) != ';'){
			$this->query .= ';';
		}*/
		$sql = 'START TRANSACTION;'.$this->query.'COMMIT';
		$sqls = explode(';', $sql);
		foreach($sqls as $sql){
			$result = $this->query( $sql );
			if (!$result){
				$result = mysqli_query( $this->connection, 'ROLLBACK' );
				break;
			}
		}
		return $this->error == 0 ? true : false;
	}
	
	public function release(){		
		$return = false;
		if ( is_resource($this->connection) ) {
			$return = mysqli_close($this->connection);
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
		$text = mysqli_real_escape_string( $this->connection, $text );
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
