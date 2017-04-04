<?php
 defined( 'CREW' ) or die( 'Restricted access' );

class VotesModelVote extends JModel
{
	var $table;
	var $type_id;
	var $type;
	var $user_id;
	var $inc = 0;
	var $sum = 0;
	var $idField = 'id';
	var $error = false;
			 
  function vote($grade)
  {
  	 $db = $this->getDBO();
  	 $query = 'SELECT grade FROM #__votes WHERE type='.$this->type.' AND type_id='.$this->type_id.' AND who_id='.$this->user_id;
  	 $db->setQuery($query);
  	 $prev = intval( $db->loadResult() );
  	 if ( $prev > 0){
  	 	   $new = $grade;  	 	 
  	 		 $grade -= $prev;
  	 		 if ($new >= 1  && $new <= 5){
  	 		 	  $query = "UPDATE #__votes SET grade=$new WHERE type=$this->type AND type_id=$this->type_id AND who_id=$this->user_id";
  	 		 	  $db->setQuery( $query );
  	 		 	  $ok = $db->query();
  	 		 	  if ( $ok ){  	 		 	  
  	 		 	  	$query = "UPDATE #__$this->table SET vote_sum=vote_sum+$grade WHERE $this->idField=$this->type_id";
  	 		 	  	$db->setQuery( $query );
  	 		 	  	$ok = $db->query();
  	 		 	  }
	  	 	 }
	  	 	 else{
 		  	 		$query = 'DELETE FROM #__votes WHERE type='.$this->type.' AND type_id='.$this->type_id.' AND who_id='.$this->user_id;
	  	 	 		$db->setQuery( $query );    	 	 	
	  	 	 		$ok = $db->query();
	  	 	 }
  	 }
  	 else if ($grade >= 1  && $grade <= 5){
  	 	   $query = "INSERT INTO #__votes VALUES ($this->type,$this->type_id,$this->user_id,$grade)";
  	 	   $db->setQuery( $query );  
  	 	   $ok = $db->query();
  	 	   if ($ok){
  	 	   	$query = 'UPDATE #__'.$this->table.' SET vote_sum=vote_sum+'.$grade.',vote_count=vote_count+1 WHERE '.$this->idField.'='.$this->type_id;  
  	 	   	$db->setQuery( $query );  
  	 	   //	echo $db->query;
  	 	   	$ok = $db->query();
  	 	  }
  	 }
		 
		 if ($ok){		 		
		 	  $this->inc = $prev ? 0 : 1;
		 	  $this->sum = $grade;
		 }
		 else{
		 	 $this->error = true;
		 }	  	 
  }
  

}
?>  