/*Najvazniji deo koda koji se tice dodavanja tagova u IE i firefoxu je napisao Igor Crevar
Ako zelite da koristite dati kod, morate dobiti dozvolu autora.
*/
b_help = "Bold text: [b]text[/b]";
i_help = "Italic text: [i]text[/i]";
u_help = "Underline text: [u]text[/u]";
quote_help = "Quote text: [quote=name]text[/quote]";
center_help = "Center text: [center]code[/center]";
img_help = "Insert image: [img=xxx]http://image_url[/img]";
url_help = "Insert URL: [url=http://url]URL text[/url]";
color_help = "Font color: [color=red]text[/color]";
size_help = "Font size: [size=50%]small text[/size]";		
smile_help = "Pick smile from list";	

function helpline(help) {
  var helpbox = document.getElementById('editor-helper');
  helpbox.innerHTML = eval(help + "_help");
}		
	
var prevEditorHTML = '';			
 
function addYourTag( $tag, $param ){			 	 
  var $tb = document.getElementById("editor-text");	
  $tb.focus(); //mora zbog IE da se stavi focus na textarea
  var $prefix = "[";
  if ($tag == 'URL'){
  	$param="ovde_unesite_link";
 		$prefix += $tag + "=\"" + $param + "\"]";
 	}
 	else if ($param != undefined){
 		$prefix += $tag + "=" + $param + "]";
 	}
 	else{
 		$prefix += $tag + "]";
 	}
  var $sufix = "[/"+ $tag + "]";
  if ( document.selection )  //za IE
	{					    
       var sel = document.selection.createRange();
       var str = sel.text;
       if ( str.length == 0 ){
       	sel.text += $prefix+$sufix;
       }
       else{ 
	     	sel.text = $prefix + str + $sufix;
	    }
	}
  else{	  //za Firefox
       var $before, $after, $selection;
       $before = $tb.value.substring( 0, $tb.selectionStart );
       $selection = $tb.value.substring( $tb.selectionStart, $tb.selectionEnd );
       $after = $tb.value.substring( $tb.selectionEnd, $tb.value.length );
       $tb.value= $before + $prefix + $selection + $sufix + $after;
  }  
}

function addSmile($name){
	var $tb = document.getElementById("editor-text");	
  $tb.focus(); //mora zbog IE da se stavi focus na textarea
  array = new Array();
  array['smile'] = ':)';
  array['sad'] = ':(';
  array['wink'] = ';)';
  if (array[$name]){
  	$name = array[$name];
  }
  else{
  	$name = ':'+$name+':';
  }
  if ( document.selection )  //za IE
	{					    
     var sel = document.selection.createRange();
   	 sel.text = $name+' ';
	}
  else{	  //za Firefox
       var $before, $after, $selection;
       $before = $tb.value.substring( 0, $tb.selectionStart );
       $after = $tb.value.substring( $tb.selectionEnd, $tb.value.length );
       $tb.value= $before+$name+' ' + $after;
  }  
  var smiles = document.getElementById('editor-smiles');
  smiles.style.display = 'none';
}

function pogledaj(){
}			 

function addLinkImage(){    	
}

function smiles(){
	var smiles = document.getElementById('editor-smiles');
	if (!smiles){
		var tsmiles = document.getElementById('editor-tmp');
		smiles = document.createElement('div');
		smiles.id = 'editor-smiles';
		smiles.innerHTML = tsmiles.innerHTML;
		document.body.appendChild(smiles);
	}	
	if (smiles.style.display == 'block'){
		smiles.style.display = 'none';
		return;
	}	
	var origs = getPosition( document.getElementById('editor-smiles-pick') );
	smiles.style.position = 'absolute';
	smiles.style.display = 'block'; 	
	smiles.style.top = origs.y + 22 + 'px';
	smiles.style.left = origs.x + 'px';
		
}