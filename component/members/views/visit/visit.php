<?php
 defined( '_JEXEC' ) or die( 'Restricted access' );
	  $my_db = &JFactory::getDBO();
	  $my_o = &JFactory::getUser();
	  $my_id = $my_o->id;
	  $l_id = $this->event->l_id;
	  $e_id = $this->event->id;
	  $my_query = "SELECT u1.id,username FROM #__members_locations AS ml JOIN #__users AS u1 ON u1.id=ml.user_id WHERE ml.location_id=$l_id ORDER BY RAND() LIMIT 4";
	  $my_db->setQuery($my_query);
    $my_rows = $my_db->loadObjectList();
    echo '<center>';
    echo '<h3 class="normal"> Ekipa koja izlazi ovde...</b></h3>';
    if (count($my_rows)>0)
    { 
    	$i = 0;
			foreach ($my_rows as $row){
    		if ($i>0) echo ' , ';
	      	echo '&nbsp;<a href="'.JRoute::_('index.php?option=com_members&view=profile&id='.$row->id).'">';
		  	echo JString::substr($row->username,0,28).'</a>';
		  	++$i;
			}  
    	echo '<br><br><a href="'.JRoute::_('index.php?option=com_members&view=userlist&layout=list&photo_locations='.$l_id).'">[Ko jos izlazi na ovo mesto?]</a><br><br>';
    }
    else echo 'Nema jos nikog u ekipi :(. Budi prvi :)<br><br>';
    if ($my_id >= 18 )
    {
      $my_query = "SELECT user_id FROM #__members_locations WHERE location_id=$l_id AND user_id=$my_id";
	    $my_db->setQuery($my_query);
	    echo '<form action="'.Basoc::routerBase().'index.php">';	    	    
	    echo '<input type="hidden" name="option" value="com_members"/>';
	    echo '<input type="hidden" name="task" value="myVisit"/>';
	    echo '<input type="hidden" name="l_id" value="'.$l_id.'"/>';
	    echo '<input type="hidden" name="e_id" value="'.$e_id.'"/>';
  	  if ($my_db->loadResult() == null)
        echo '<input type="submit" style="width:168px" class="button" value="Hej, i ja ovde izlazim! :)"/>';
        //echo '<a href="'.JRoute::_('index.php?option=com_members&task=MyVisit&l_id='.$l_id).'">[Hej,i ja ovde izlazim! :). Ubaci me u ekipu!]</a>';
      else{
      	echo '<input type="hidden" name="what" value="remove"/>';
      	echo '<input type="submit" style="width:168px" class="button" value="Ne, ja ovde vise ne izlazim!"/>';
        //echo '<a href="'.JRoute::_('index.php?option=com_members&task=myVisit&what=remove&l_id='.$l_id).'">[Ne, ja ovde vise ne izlazim. Izbaci me iz ekipe!]</a>'; 
      }  
      echo '</form>';  
    }    
    echo '</center>';        
?>