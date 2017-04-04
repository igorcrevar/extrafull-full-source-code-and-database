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

class BlogController extends JController
{
	function display(){	   
		$viewName = JRequest::getCmd('view', 'blogs');
		$doc = &Document::getInstance();
		$viewType = $doc->getType();
		$view = $this->getView( $viewName, $viewType );
		if ($viewName != 'post'){
			$model = $this->getModel( $viewName, 'modelblog' ); 
			$view->setModel( $model, true ); 
		}
   	$view->display(); 
  }

  function post(){
  	global $mainframe;
  	$user = &User::getInstance();
		$id = JRequest::getInt('id',-1);   
  	$subject = JRequest::getString('subject');
  	$text = JRequest::getString('text');
  	$type = JRequest::getInt('type',0);
  	$options = JRequest::getInt('options',0);
  	$types = explode( ',', JText::_('TYPES') );
  	$tag = JRequest::getString('tag','');
  	$rv = new stdClass;
  	$rv->a = $subject;
  	$rv->b = $text;
  	$rv->c = $type;
  	$rv->d = $options;
  	$err = Basic::uriBase().'/blog/post?param='.urlencode(serialize($rv));
  	if ($user->gid < 18  ||  JString::strlen($subject) < 4 || JString::strlen($text) < 600 || $type < 1  || $type > count($types) ){
  		$mainframe->redirect($err,'Naslov teme mora imati barem 4 znaka, a tekst barem 600 i morate izabrati vrstu bloga');
  	}  	
		$db = &Database::getInstance();
  	$subject = $db->Quote($subject);
    $text = $db->Quote($text);
  	$type--;
		if ($id){
			$query = 'UPDATE #__blogs SET subject='.$subject.',options='.$options.',type='.$type.',text='.$text.' WHERE id='.$id.' AND who_id='.$user->id;
		}
		else{
  		$db->setQuery('UPDATE #__fb_users SET blogs=blogs+1 WHERE userid='.$user->id);
  		$db->query();
			$query = "INSERT INTO #__blogs VALUES (0,$type,$user->id,$subject,$text,now(),0,0,0,$options)";
		}
		$db->setQuery($query);
		if (!$db->query()) $this->setRedirect( $err, 'Greska!' );
  	else $this->setRedirect( '/blog/'.$user->id );     
  }
	
	function del(){
  	$user = &User::getInstance();
  	if ($user->gid < 18) return;
		$id = JRequest::getInt('id',-1);   
		$db = &Database::getInstance();
		$q = 'DELETE FROM #__blogs WHERE id='.$id;
		if (!isMod($user->id)) $q .= ' AND who_id='.$user->id;
		$db->setQuery($q);
		if (!$db->query()) $msg = 'Greska!';
		else {
  		$db->setQuery('UPDATE #__fb_users SET blogs=blogs-1 WHERE userid='.$user->id);
  		$db->query();
			$msg= 'Blog obrisan';		
		}
  	$this->setRedirect( '/blog', $msg );     
	}
	
	function delCmnt(){
  	$user = &User::getInstance();
  	if ($user->gid < 18) return;
		$id = JRequest::getInt('id',-1);
		$db = &Database::getInstance();
		$cid = JRequest::getVar( 'cid', array(),'post','null' );
		if (!is_array($cid)) return;
		$cids =chop( implode(',', array_keys($cid) ));
		if (!isMod($user->id)){
			$db->setQuery('SELECT who_id FROM #__blogs WHERE id='.$id);
			$who = intval($db->loadResult());
			if ($who < 30  ||  $who != $user->id) return;
		}	
		
		$q = 'DELETE FROM #__comments WHERE type=1 AND type_id='.$id.' AND id in ('.$cids.');';		
		$q .= 'UPDATE #__blogs SET comments=(SELECT count(*) FROM #__comments WHERE type=1 AND type_id='.$id.') WHERE id='.$id.';';
		$db->setQuery($q);
		if (!$db->queryBatch(true,true)) $msg = 'Greska!';
		else {
			$msg = 'Komentari obrisani';		
		}
  	$this->setRedirect( '/blog/blog/'.$id, $msg );     
	}
	
	function comment(){
		$user = &User::getInstance();
  	if ($user->gid < 18) return;
		$id = JRequest::getInt('id',-1);
		$db = &Database::getInstance();
		$comment = JRequest::getString('comment','');
		if (JString::strlen($comment) < 4){
			global $mainframe;
  		$mainframe->redirect('/blog/blog/'.$id,'Komentar mora imati barem 4 znaka');
  	} 		
		$comment = $db->Quote( $comment );
		$time = time();
		$q = "INSERT INTO #__comments VALUES (0,1,$id,$comment,$user->id,$time);";
		$q .= 'UPDATE #__blogs SET comments=comments+1 WHERE id='.$id.';';
		$db->setQuery($q);
		if (!$db->queryBatch(true,true)) $msg = 'Greska!'; else $msg = null;
  	$this->setRedirect( 'blog/blog/'.$id, $msg );     
	}
	
	function vote(){
  	$db = &Database::getInstance();  	 
  	$user = &User::getInstance();
  	$id = JRequest::getInt('id',0);
  	$vote = JRequest::getInt('vote',0);
		$abs = abs($vote);
		if ( $user->gid < 18 || ($abs != 1 && $abs != 2))
		  return 0;
		$query = "SELECT grade FROM #__votes WHERE type=1 AND type_id=$id AND who_id=$user->id"; 
		$db->setQuery($query);
		$old = intval($db->loadResult());
		if ( abs($old) == 1 )
		{
			if ($old == $vote){ echo '!';return;}
		  $_v= -$old;
		  $vote = -($old*2);
		  $query = "UPDATE #__votes SET grade=$_v WHERE type=1 AND type_id=$id AND who_id=$user->id;";
		  $new = 0;
		}
		else{ 			
		  $query = "INSERT INTO #__votes VALUES (1,$id,$user->id,$vote);";
		  $new = 1;
		} 
		$query .= "UPDATE #__blogs SET vote_sum=vote_sum+$vote,vote_count=vote_count+$new WHERE id=$id;"; 
		$db->setQuery($query);  
		if ( !$db->queryBatch(true,true) )
		  echo '!'; 	 	
		else{
			echo '{ "vote":'.$vote.', "inc":'.$new.'}';
		}  
	}
}
?>