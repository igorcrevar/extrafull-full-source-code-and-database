<?php
defined( 'CREW' ) or die( 'Restricted access' );

class PhotoViewImage extends JView
{
  function display($tpl = null)
	{		
     $req = JRequest::getCmd( 'useraction', '' );
		 $image_id = JRequest::getInt( 'image_id' );
		 $model = $this->getModel();
		 $model->image_id = $image_id;
  	 $image_data = $model->loadImage();

		 if ( $image_data == null ) return; //greska
		 		 
		 $read = $model->hasPrivilege('read');
		 $this->assignRef( 'read', $read );
		 $write = $model->hasPrivilege('write');
	   $this->assignRef( 'write', $write );
		 $change = $model->hasPrivilege('change');
	   $this->assignRef( 'change', $change );
		 $moderate = $model->hasPrivilege('moderate');
		 $this->assignRef( 'moderate', $moderate );	
		    
	   $this->assignRef( 'image_id', $image_id );
		 
		 switch ( $req )
		 {
		 	  case 'user_vote':
  		    $grade = JRequest::getInt( 'user_vote', 5 );
  		    $rv = '!';
  		    if ( $write ){
  		    	$rv = $model->vote($grade);
  		 	  }  
  		    echo $rv;  
  		 	  break;  
  		 	case 'user_comment':
 	 	      $comment = JRequest::getString('user_comment',null);
 	 	       //da li saljemo komentar u privatnu galeriju	
  		    if ( $write  &&  $model->insertUserComment($comment) )
  		    {
  		    	$com = new StdClass();
  		    	$user = &User::getInstance();
  		    	$com->user_id = $user->id;
  		    	$com->user_name = $user->username;
  		    	$com->date = time();
  		    	$com->comment = $comment;
  		    	$moderate = false;
  		    	$comments = array($com);
	   	      $this->assignRef( 'comments', $comments );
	   	      $this->setLayout( 'default_comments' ); 
  		    	parent::display( $tpl );
 		      }  
  	 	    else  
  	 	      echo '!';
  	 	    break;  
  	 	  case 'favourite':
  	 	    echo ($write  &&  $model->favourite() ? 'ok' : '!');
  	 	    break;
	   	  case 'imageview':
	   	    if ( !$model->updateViews() )
	   	      return;
	 	   	  	$comments = $model->getComments();
	   	    	//$vote = $model->getVote();
          	$favourite = $write  &&  $model->isFavourite();
          	$this->assignRef( 'favourite',$favourite );
	   	    	$this->assignRef( 'comments',$comments );
	        	//$this->assignRef( 'vote', $vote );
	        	$vote_stat = $model->getVoteStat();
	        	$this->assignRef( 'image_time', $image_data->time );
						$this->assignRef( 'vote_stat', $vote_stat );
	        	$this->assignRef( 'image_name', $image_data->name ); //$image_name );
	        	parent::display( $tpl );
		 }
	}
}
?>