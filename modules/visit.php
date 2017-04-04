<?php
 defined( 'CREW' ) or die( 'Restricted access' );
 	  $l_id = $this->event->l_id;
	  $e_id = $this->event->id;//treba resiti bolje
	  
    $my_db = &Database::getInstance();
	  $my_o = &User::getInstance();
	  $my_id = $my_o->id;
	  $my_query = "SELECT u1.id,username FROM #__members_locations AS ml JOIN #__users AS u1 ON u1.id=ml.user_id WHERE ml.location_id=$l_id ORDER BY RAND() LIMIT 4";
	  $my_db->setQuery($my_query);
    $my_rows = $my_db->loadObjectList();
    echo '<center>';
    echo '<h3 class="normal">'.CREW_VISIT.'</b></h3>';
    if (count($my_rows)>0)
    { 
    	$i = 0;
			foreach ($my_rows as $row){
    		if ($i>0) echo ', ';
    	  echo JHTML::profileLink($row->id,JString::substr($row->username,0,28) );
		  	++$i;
			}  
    	echo '<br><br><a href="/clan/lista?layout=list&photo_locations='.$l_id.'">'.WHO_ELSE_VISIT.'</a><br><br>';
    }
    else echo CREW_VISIT_NONE.'<br><br>';
    if ($my_id >= 18 )
    {
      $my_query = "SELECT user_id FROM #__members_locations WHERE location_id=$l_id AND user_id=$my_id";
	    $my_db->setQuery($my_query);
	    echo '<form action="'.JURI::base().'index.php">';	    	    
	    echo '<input type="hidden" name="option" value="members"/>';
	    echo '<input type="hidden" name="task" value="myVisit"/>';
	    echo '<input type="hidden" name="l_id" value="'.$l_id.'"/>';
	    echo '<input type="hidden" name="e_id" value="'.$e_id.'"/>';
	    $mn = explode('@',CREW_VISIT_OPT);
  	  if ($my_db->loadResult() == null)
        echo '<input type="submit" style="width:168px" class="button" value="'.$mn[0].'"/>';
      else{
      	echo '<input type="hidden" name="what" value="remove"/>';
      	echo '<input type="submit" style="width:168px" class="button" value="'.$mn[1].'"/>';
      }  
      echo '</form>';  
    }    
    echo '</center>';        
?>