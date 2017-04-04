<?php
defined( 'CREW' ) or die( 'Restricted access' );

class MembersViewMyProfile extends JView
{
	function display( $tpl = null)
	{
		$model = $this->getModel();
 		$user = & JFactory::getUser();
		if ( isProfileMod( $user->id ) ){
		 	 $id = JRequest::getInt( 'id', $user->id );
		 	 // ne moze samog sebe banovati niti obrisati
		 	 if ( $id != $user->id ){
		 	  	$this->assignRef( 'id', $id );
		 	 }
		}
		else{
			 $id = $user->id;
		}
		$model->id = $id;
    $locs = $model->loadLocs();
    ?>  		
	  	<form action="<?php echo Basic::routerBase();?>/clan/mojprofil" method="post">
	  		<?php 
           $j = 1;
           echo '<table>';
      		 foreach ($locs['all'] as $loc){
      			 	$checked = in_array($loc->id,$locs['my']) ? 'CHECKED' : '';
      			 	if ($j == 1) echo '<tr>';
							echo '<td width="150px"><input type="checkbox" name="locs[]" '.$checked.' value="'.$loc->id.'" />'.JString::substr($loc->name,0,22).'</td>';
								if ($j < 2){
									++$j;
								}
								else{									
									echo '</tr>';
									$j = 1;
								}
      			 }
      			 echo '</table>';
	  		?>
  			<input type="hidden" name="task" value="upload"/>
        <input type="hidden" name="segment" value="locations"/>
        <input type="submit" value="<?php echo JText::_('POSALJI');?>" class="button" /><br/>
        <a TARGET="_blank" href="<?php echo Basic::routerBase();?>/desavanja/new?layout=locations">[Tvoje mesto za izlazak ne postoji? Dodaj ga sam :)]</a>
        <?php if ($id != null) echo '<input type="hidden" name="id" value="'.$id.'"/>';?>
	    </form>	
	<?php
	  }	  
}
?>
