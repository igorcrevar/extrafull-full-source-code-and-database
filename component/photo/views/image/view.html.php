<?php
defined( 'CREW' ) or die( 'Restricted access' );

class PhotoViewImage extends JView
{
  function display($tpl = null)
	{		
		 $doc = &Document::getInstance();
     $image_id = JRequest::getInt( 'id' );
		 $model = $this->getModel();
		 $model->image_id = $image_id;
  	 $image_data = $model->loadImage();

		 if ( $image_data == null ) return;//greska}
		 if ( $image_data->published > 1  &&  $model->user_id < 1){
		 	 global $mainframe;
		 	 $mainframe->redirect( 'index.php','Morate se registrovati i ulogovati' );
		 	 return;
		 }
		
		/* block code - blocks even public images - fuck you :) */
		require_once BASE_PATH.DS.'block.php';
		if ( isUserBlockedBy($image_data->a_id, true ) ){
			return;
		} 
		/* end block code */  	
		
		 $doc->addScript( JURI::base().'component/photo/pho.js' );
		 $doc->addStyleSheet( Basic::uriBase().'/component/photo/photo.css' );		 		 
		 $read = $model->hasPrivilege('read');
		 $this->assignRef( 'read', $read );
		 $write = $model->hasPrivilege('write');
	   $this->assignRef( 'write', $write );
	   $change = $model->hasPrivilege('change');
	   $this->assignRef( 'change', $change );
		 $moderate = $model->hasPrivilege('moderate');
	   $this->assignRef( 'moderate', $moderate );
	   
	   $this->assignRef( 'image_id', $image_id );
	   if ( !$model->updateViews() ) return;
	   $read = $model->hasPrivilege('read');  
	   if ($read){  
	 	  	$comments = $model->getComments();
	     //	$vote = $model->getVote();
	     	$vote_stat = $model->getVoteStat();
       	$favourite = $write  &&  $model->isFavourite();
       	$this->assignRef( 'favourite',$favourite );
	     	$this->assignRef( 'comments',$comments );
	     //	$this->assignRef( 'vote', $vote );
	     	$this->assignRef( 'vote_stat', $vote_stat );
	     	$this->assignRef( 'image_name', $image_data->name );
	     	$this->assignRef( 'image_time', $image_data->time );
     }	
		 $mypath = Basic::routerBase().'/photos/'.$image_data->event_id.'e/';
		 ?>
<script type="text/javascript" language="javascript">
var selectedImage=<?php echo $image_id;?>;
var image_file_name='';
var parent_comment_id=-1;
var jpath='<?php echo $mypath;?>';
var event_id=<?php echo $image_data->event_id;?>;
</script>
<?php
	   echo '<center><div style="width:620px">';
		 echo '<center>';
		 $doc = & JFactory::getDocument();
		 if ($image_data->published==1){
	   	$locationname = $model->loadLocation();
	   	$doc->setTitle('Extrafull galerija slika : '.$locationname);
	   	$doc->description = 'Extrafull galerija slika kluba : '.$locationname;
	   	$doc->keywords = $locationname.', '.$doc->keywords;
	   	echo JHTML::galleryLink($image_data->event_id,'<span class="fontMy1">'.$locationname.'</span>&nbsp;- <span class="fontMy2">'.JHTML::_('date',$image_data->date,'date2').'</span>');
	   }else{
	   	 $username = $model->loadUserName();
	   	 $doc->setTitle('Extrafull galerija slika za profil: '.$username);
 	     echo JHTML::galleryLink( $image_data->event_id, '<span class="fontMy1">'.JText::_('PH_PRIVATE_EVENT').'</span>' );
	   	 echo ' by '.JHTML::profileLink($image_data->a_id, '<b>'.$username.'</b>'); 
	   }
	   echo '<br>';
		 echo '<a href="#" onclick="ViewFullImage();return false;">';
		 echo '<img name="'.$image_data->file_name.'" id="mid_image" src="'.$mypath.'b_'.$image_data->file_name.'"/>';
		 echo '</a>';		 
		 echo '</center>'; 
		 echo '<div id="ajax_msg"></div>';
		 echo '<div id="image_info">';
		 parent::display( $tpl );
		 echo '</div></div></center>';
	}
}
?>