<?php defined('CREW') or die('Restricted access'); 
$opt = explode(',',JText::_('PH_EVENTS_OPTIONS'));
for ($i = 0; $i < count($opt); ++$i){
	$tmp = 7 + $i;
	echo  '<a href="'.Basic::uriBase().'/slike?stat='.$tmp.'">'.$opt[$i].'</a>&nbsp;';
}		
?>
<table width="100%">
 <tr>
	 <td valign="top">
	 	<?php 
			if ($this->rows != null)
			{
				$this->pagination->getPagesLinks();
				foreach($this->rows as $row)
				{ 
					$link = Basic::uriBase().'/galerija/'.$row->id; 
					$mypath = '/photos/'.$row->id.'e/';
					?>	
					<div class="photo_event">	
					<table>
					<tr>
						<td>
						<a class="photo_event_hd" href="<?php echo $link;?>">	
					  	<span class="fontMy1"><?php echo $row->location ?></span> - 
             	<span class="fontMy2" ><?php echo JHTML::date($row->date,'date2');?></span>
            </a>
              <span style="float:right">broj slika: <?php echo $row->image_count;?><br>
              	<a href="<?php echo $link;?>"><b>pogledaj...</b></a>	
              </span>
              <?php if ($row->name!='') echo "<b>$row->name</b><br>";?>
              <?php echo $row->category ?>
					  </td>
					</tr></table></div>
			<?php 
			  }
			  $this->pagination->getPagesLinks();
			} ?>
  </td>
  
  <?php
  if ($this->c_id > 0) $cid = $this->c_id; else $cid = null;
  if ($this->l_id > 0) $lid = $this->l_id; else $lid = null;
  if ($this->a_id > 0) $aid = $this->a_id; else $aid = null;
  ?>
	<td width="280" valign="top" align="justify">				 
     <?php 
       $this->calendar->show();
     ?>		 
		 <div class="photo_hd"><?php echo JText::_('PH_LOCS');?></div>
<?php	 
			 $address = '';
			 for ($i = 0; $i < count($this->locations); ++$i)
		   { 
		   	 $loc = $this->locations[$i];
         $locAddr = $loc->address;
         if (!$locAddr) $locAddr = $loc->name;
		   	 if ($address != $locAddr){
           $address = $locAddr;
		   	   echo '<br> - <b>'.$locAddr.' : </b>';
		   	 }
		   	 else if ($i>0) echo ', ';  
		   	 $tmp = $loc->name;
		   	 $id = $loc->id;
		   	 if ($this->l_id !=	$id){
		   	 	 echo JHTML::galleriesLink($cid,$id,$aid,null,$tmp);
		   	 }
		   	 else{
		   	 	echo $tmp;
		   	 }		   	  
  	   } ?>			 
  	<div class="photo_hd"><?php echo JText::_('PH_AUTHORS');?></div>		  
		<?php	 
			 for ($i = 0; $i < count($this->authors); ++$i)
		   { 
		   	 $name = $this->authors[$i]->name;	
		   	 $id = $this->authors[$i]->id;
		   	 if ($i>0) echo ', ';  
		   	 if ($this->a_id !=	$id){
		   	 	  echo JHTML::galleriesLink($cid,null,$id,null,$name);
		   	 }
		   	 else{
		   	 	echo $name;
		   	 }
  	   } ?>		
  	  <div class="photo_hd"><?php echo JText::_('PH_CATS');?></div>	
 <?php for ($i = 0; $i < count($this->categories); ++$i)
		   { 
		   	 $row = $this->categories[$i];
		  	 if ( $this->c_id != 0  &&  $i == 0 )
		  	 {
		  	   $id = $row->p_id;
		  	   $tmp = '&nbsp;'.$row->name;
		  	   $print = '(nazad)';
		  	 }
		  	 else
		  	 {
		  	 	 $id = $row->id;
		  	 	 $tmp = '&nbsp;- ';
		  	 	 $print = $row->name;
		  	 }  
		     echo $tmp;
		     echo JHTML::galleriesLink($id,null,null,null,$print).'<br/>';
  	   } ?>	
   </td>
 </tr>
</table>  
