<?php
defined( 'CREW' ) or die( 'Restricted access' );?>
<?php if ($this->first){
$user = &User::getInstance();
$db = &Database::getInstance();
$db->setQuery('SELECT params FROM #__users WHERE id='.$user->id);
$param = $db->loadResult();
//$param = $user->params;
if ($param) {
	$ids = explode(',',$param);	
	$where = '';
	$i = 0;
	foreach ($ids as $fid){
		$fid = intval($fid);
		if ($fid < 20) continue;
		if ($i>0) $where .= ',';
		$where .= $fid;
		++$i;
	}
	$query = "SELECT id,username,avatar FROM #__users WHERE id in ($where)";
	$db->setQuery($query);
	$topPriv = $db->loadObjectList();
	$top = array();
  foreach ($ids as $value){
  	for ($i=0;$i< count($topPriv) && $value != $topPriv[$i]->id;++$i);
  	$top[] = &$topPriv[$i];
  }
}
else {
	$top = array();
}
$cnt = count($top);
$top_how = 4;
if ($cnt > 24 && $cnt <= 32)
   $top_how = 32;
else if ($cnt > 16 && $cnt <= 24)
   $top_how = 24;
else if ($cnt > 12 && $cnt <= 16)
   $top_how = 16;
else if ($cnt > 8 && $cnt <=12 )
  $top_how = 12;  
else if ($cnt > 4 && $cnt <=8 )
  $top_how = 8;  
?>
<script type="text/javascript">
function addHandlers(i){
	for(var j=0; j<i.childNodes.length; j++){
			// Firefox puts in lots of #text nodes...skip these
	  el = i.childNodes[j];
 	  if (el.nodeName=='#text') continue;
		if (el.getAttribute('drag') == 'yes'){
			 attachHandler(el,'mousemove', mouseMove,false);  
  		 attachHandler(el,'mousedown', mouseDown,false);
  		 attachHandler(el,'mouseup', mouseUp,false);
  		 if (el.childNodes.length) addHandlers(el);
		}		  
  }
}
function getMouseOffset(target, ev){
	ev = ev || window.event;
	var docPos    = getPosition(target);
	var mousePos  = mouseCoords(ev);
	return {x:mousePos.x - docPos.x, y:mousePos.y - docPos.y};
}

function mouseCoords(ev){
	if(ev.pageX || ev.pageY){
		return {x:ev.pageX, y:ev.pageY};
	}
	return {
		x:ev.clientX + document.body.scrollLeft - document.body.clientLeft,
		y:ev.clientY + document.body.scrollTop  - document.body.clientTop
	};
}

function mouseMove(ev){
	ev = ev || window.event;
	var mousePos = mouseCoords(ev);
	if(isDrag){		
		dragObject.style.display = 'block';	
		dragObject.style.position = 'absolute';
		dragObject.style.top = (mousePos.y - mouseOffset.y)+'px';
		dragObject.style.left= (mousePos.x - mouseOffset.x)+'px';
		//document.getElementById('ispis').innerHTML = (mousePos.y - mouseOffset.y)+':'+(mousePos.x - mouseOffset.x);
		return false;
	}	
}

function getMyId(el){
	return parseInt(el.id.substr(4));
}

function changeMe(i,j){
	el1 = document.getElementById('drop'+i);
	el2 = document.getElementById('drop'+j);
	var tmp1 = el1.innerHTML;
	var tmp2 = el1.getAttribute('userid');
	el1.innerHTML = el2.innerHTML;
	el1.setAttribute( 'userid', el2.getAttribute('userid') );
	el2.innerHTML = tmp1;
	el2.setAttribute( 'userid', tmp2 );
}
function setMeObject(i,a){
	el1 = document.getElementById('drop'+i);
	el1.innerHTML = a.innerHTML;
	el1.setAttribute( 'userid', a.getAttribute('userid') );
}
function setMeWith(i,a,b){
	el1 = document.getElementById('drop'+i);
	el1.innerHTML = a;
	el1.setAttribute( 'userid', b+'' );
}
function setMe(i,j){
	el1 = document.getElementById('drop'+i);
	el2 = document.getElementById('drop'+j);
	el1.innerHTML = el2.innerHTML;
	el1.setAttribute( 'userid', el2.getAttribute('userid') );
}
function delMe(i){
	el = document.getElementById('drop'+i);
	el.innerHTML = '';
	el.setAttribute( 'userid', '' );
}

function moveLeft(i,end){
	for (;i<end;++i){
		j = i + 1;
		setMe(i,j);
	}		
	delMe(i);
}
function moveRight(i,end){
	end = end - 1;
	for (;end>=i;--end){
		j = end + 1;
		setMe(j,end);
	}		
}

function mouseUp(ev){
	ev = ev || window.event;
	var mousePos = mouseCoords(ev);
	if (isDrag && sourceObject){		   
		  var isDropSource = sourceObject.getAttribute('drop') == 'yes';
			var dropObject = false;
			var firstEmptyInd = -1;
			var elInd = -1
			var pos, el, alreadyHas = false;
			for (i=0;i<topUsersCnt;++i){
				el = document.getElementById('drop'+i);
				if ( el.getAttribute('userid') == userid )
				  alreadyHas = true;
				if (el.innerHTML == ''  &&  firstEmptyInd == -1) firstEmptyInd = i;				
		 		pos = getPosition( el );
		 		if ( !dropObject  && pos.x <= mousePos.x && pos.y <= mousePos.y &&
		 			mousePos.x < 120+pos.x && mousePos.y < 95 +pos.y ){
		 				dropObject = el;
		 				elInd = i;
		 				//document.getElementById('ispis').innerHTML = dropObject.id;		 				
		 		}		 	
		 	}
			
		 	if ( !dropObject &&  isDropSource){		
		 		moveLeft( getMyId(sourceObject),topUsersCnt-1 ); 		
		 	}
		 	else if (dropObject){
		 		if ( isDropSource ){
		 			srcInd = getMyId(sourceObject);
		 			var oldV = sourceObject.innerHTML;
		 			if (srcInd > elInd){ //pomeram levo usera		 				
		 				moveRight( elInd,srcInd );
		 				setMeWith(elInd, oldV, userid );
		 			}
		 			else if (srcInd < elInd){ //pomeram desno usera				 				 				
		 				moveLeft(srcInd,elInd);
		 				if ( elInd >= firstEmptyInd && firstEmptyInd > -1 )
		 				  elInd = firstEmptyInd - 1;
		 				setMeWith(elInd, oldV, userid);
		 			}		 			
		 		}
		 		else{		 	  	
		 			if ( alreadyHas ){
		 				dragObject.style.display = 'none';
		 				alert('Taj korisnik vam je je vec u top prijateljima.\n Ako zelite da mu promenite poziciju prevucite njegovu sliku\n u top prijateljima na zeljeno mesto'); 
		 			}
		 	  	else if ( elInd >= firstEmptyInd  &&  firstEmptyInd > -1 ){
		 	  		setMeWith(firstEmptyInd, sourceObject.innerHTML, userid );
		 	  	}
					else{	
						moveRight(elInd,topUsersCnt-1);
						setMeWith(elInd, sourceObject.innerHTML, userid );
		 			}
		 			
		 		}	
			}
	}
	dragObject.style.display = 'none';		
	sourceObject = false;
	isDrag = false;
}

function mouseDown(ev){
	if (!dragObject){
		dragObject = document.createElement('DIV');
		dragObject.style.cssText = 'position:absolute;left:20px;top:130px;display:none;';
		document.body.appendChild(dragObject);
	}
	ev = ev || window.event;
	var fobj = ev.target || ev.srcElement;
 // while (fobj && fobj.getAttribute('drag') != "yes")
   // fobj = fobj.parentNode || fobj.parentElement;
  if (fobj.getAttribute('drag') == "yes"){
	  isDrag = true;
		//moramo ga prvo ocistiti
	  for(var i=0; i<dragObject.childNodes.length; i++) 
	     dragObject.removeChild(dragObject.childNodes[i]);
	  dragObject.appendChild(fobj.cloneNode(true));	  			  
		mouseOffset = getMouseOffset(fobj,ev);
		sourceObject = fobj.parentNode || fobj.parentElement;
		userid = fobj.getAttribute('userid');
	  return false;
	}  
}

function updateTop(){	
	_option=101;
	var param = '';
	for (i=0;i<topUsersCnt;++i){
		el = document.getElementById('drop'+i);
		uid = el.getAttribute('userid');
		if (uid){
			if (param != '') param += ',';
			param += uid;
		}		
	}	
	sendAJAX('task=updateTop&param='+param,'comment_start');
}

function changeTop(n){
	var el = document.getElementById('topFriends');
	var oldInner = new Array(), oldId = new Array();
	for (i=0;i<topUsersCnt;++i){
		var tmp = document.getElementById('drop'+i);
		oldInner[i] = tmp.innerHTML;
		oldId[i] = tmp.getAttribute('userid');
	}
	hm = '';
	for (i=0;i<n;++i){
		if (i<topUsersCnt){
		hm += '<div id="drop'+i+'" class="EX" drop="yes" userid="'+oldId[i]+'">';
		hm += oldInner[i];
	  }
	  else
	  	hm += '<div id="drop'+i+'" class="EX" drop="yes" userid="">';
	 	hm += '</div>';
	}
	el.innerHTML = hm+'<div style="clear:left;"></div>';
	topUsersCnt = n;
}

var userid;
var isDrag = false;
var mouseOffset = false;
var dragObject = false;
var sourceObject = false;
var topUsersCnt = <?php echo $top_how;?>; 
document.onmousedown = mouseDown;
document.onmouseup = mouseUp;
document.onmousemove = mouseMove;
</script>
<center><div style="width:800px">   
   <select onchange="changeTop(this.value);">
   	<?php   
   	      for ($i=4;$i<=32;$i = $i + 4){
   	      	if ($i == 20 || $i == 28) continue;
   	      	if ($top_how == $i)
   	      	  echo "<option SELECTED=\"yes\" value=\"$i\">$i</option>";
   	      	else
   	      	  echo "<option value=\"$i\">$i</option>";
   	      }
   	      ?>
  </select><input  type="button" value="<?php echo JText::_('POSALJI');?>" class="button" onclick="updateTop();return false;" style="margin:12px 4px 0px 4px"/><span id="comment_start"></span>
<br><br>
<div id="topFriends">
		<?php draw(1,$top); 
		   for ($i=$cnt;$i< $top_how;++$i)
		     echo '<div id="drop'.$i.'" userid="" drop="yes" class="EX"></div>';
		?>
    	  <div style="clear:left;"></div>
    	</div>
    	<br><span class="fontMy1">Uredi prijatelje. Prevuci SLIKU prijatelja na mesto koje zelis</span><br><br>
<span id="ajax_msg"></span>
<div id="s_images">
<?php } ?>    	

<?php 
echo '<br><div id="allFriends">';
if (count($this->imgs)>0){
  draw(0,$this->imgs);
  echo '<div style="clear:left;"></div>';
  echo '</div>';
echo $this->pagination->getPagesLinks();
}
else echo '<b>Nemate prijatelje :(</b>';
?>	

<?php 
function draw($type,$rows){
	if (!count($rows)) return;
	$i = 0;
	foreach($rows as $row){
	  if ( empty($row->avatar) )
	      $row->avatar = 's_nophoto.jpg';
		if ($type == 0) echo '<div class="EX1">';
		else echo '<div id="drop'.$i.'" userid="'.$row->id.'" drop="yes" class="EX">';
    echo '<img width="70" height="70" src="'.Basic::routerBase().'/avatars/'.$row->avatar.'" class="dragme"  userid="'.$row->id.'" drag="yes"/>';
    echo '<br>'.JHTML::profileLink($row->id,JString::substr($row->username,0,11));
    echo '</div>';
    ++$i;
  } 
}
?>
<?php if ($this->first)
 echo '</div>';
echo '</div></center>';

 	?>