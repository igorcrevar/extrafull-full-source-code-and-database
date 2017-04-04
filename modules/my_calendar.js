var dragObject = null;
function showPopUp(el,titleA){
	if (dragObject == null){
		dragObject = document.createElement('DIV');
  	document.body.appendChild(dragObject);
  }	
  titles = titleA.split(',');
  max = titles[0].length;
  for (i=1;i < titles.length; ++i){
  	if ( titles[i].length > max ){
  		max = titles[i].length;
  	}
  }
  dragObject.className = 'tool-text';
 	dragObject.style.cssText = 'position:absolute;display:block';
 	pos = getPosition(el);
 	//odedjivanje da li upada u x
 	width = getWindowSize().width - 40;
 	html = '';
 	for (i = 0; i < titles.length; ++i){
 		 html += '<div>'+titles[i]+'</div>';
 	}
 	dragObject.innerHTML = html;
 	wid = getWidth(dragObject);
 	if (pos.x+wid >= width){
 		pos.x = width-wid;
 	}
 	dragObject.style.left = pos.x + "px";
 	dragObject.style.top = pos.y + 22 + "px";
}
function hidePopUp(el){
	dragObject.style.cssText = 'display:none';
	document.body.removeChild( dragObject );
	dragObject = null;
}

function getWidth(docObj){
  tmphght = docObj.offsetWidth;
  if (!tmphght){
    var tmphght1 = document.defaultView.getComputedStyle(docObj, "").getPropertyValue("width");
    tmphght = tmphght1.split('px');
    tmphght = tmphght[0];
  }
  return tmphght;
}