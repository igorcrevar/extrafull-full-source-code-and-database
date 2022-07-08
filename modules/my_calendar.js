var dragObject = null;
function showPopUp(el, titleA) {
	if (dragObject == null) {
		dragObject = document.createElement('div');
		document.body.appendChild(dragObject);
	}
	titles = titleA.split(',');
	dragObject.className = 'tool-text';
	pos = getPosition(el);
	//odedjivanje da li upada u x
	width = getWindowSize().width - 4;
	html = '';
	for (i = 0; i < titles.length; ++i) {
		html += '<div>' + titles[i] + '</div>';
	}
	dragObject.innerHTML = html;
	wid = getWidth(dragObject);
	if (pos.x + wid >= width) {
		pos.x = width - wid;
	}
	dragObject.style.left = pos.x + "px";
	dragObject.style.top = (pos.y + 18) + "px";
}

function hidePopUp() {
	dragObject.style.cssText = 'display:none';
	document.body.removeChild(dragObject);
	dragObject = null;
}

function getWidth(docObj) {
	var result = docObj.offsetWidth;
	if (!result) {
		result = document.defaultView.getComputedStyle(docObj, "").getPropertyValue("width");
		result = result.split('px')[0];
	}
	return result;
}