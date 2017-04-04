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
 defined( 'CREW' ) or die( 'Restricted access' );

class ModelForumCats extends JModel
{  
  function get()
  {  
  	 $query = 'select a.*,b.time,b.last_username,b.last_id,b.subject,b.last_userid from #__forum_cats AS a LEFT JOIN #__forum_topics AS b ON b.id=(SELECT c.id FROM #__forum_topics AS c WHERE c.cid=a.id ORDER BY c.time DESC LIMIT 1) WHERE a.group=0 order by a.time_created';
  	 return $this->_getList( $query );
  }	
}
?>