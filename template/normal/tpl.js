var http_request;
var _option = 0;
var _label;
function getXMLHTTPObject() {
	var oAsync = false;
	if (window.XMLHttpRequest) {
		oAsync = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		try {
			oAsync = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				oAsync = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) { }
		}
	} else {
		return false;
	}
	return oAsync;
}

function sendAJAX(_url, l) {
	http_request = getXMLHTTPObject();
	if (!http_request) {
		alert('Cannot create XMLHTTP instance');
		return false;
	}
	if (typeof requestdone == 'undefined') {
		http_request.onreadystatechange = function () {
			if (http_request.readyState == 4) {
				_label.innerHTML = '';
				if (http_request.status == 200 || window.location.href.indexOf("http") == -1) {
					eval(http_request.responseText);
				}
			}
		}
	}
	else {
		http_request.onreadystatechange = requestdone;
	}
	_label = document.getElementById(l);
	_label.innerHTML = '<center>Loading...<img src="' + img_base + 'working.gif"/></center>';
	http_request.open('POST', jbase + 'index.php', true);
	http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http_request.send('option=' + component + '&format=raw&' + _url);
}

function getWindowSize() {
	if (typeof (window.innerWidth) == 'number') {
		myWidth = window.innerWidth;
		myHeight = window.innerHeight;
	} else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
		myWidth = document.documentElement.clientWidth;
		myHeight = document.documentElement.clientHeight;
	} else if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
		myWidth = document.body.clientWidth;
		myHeight = document.body.clientHeight;
	}
	return { width: myWidth, height: myHeight };
}

function getPosition(e) {
	var rect = e.getBoundingClientRect();
	return {
		x: rect.left + window.scrollX,
		y: rect.top + window.scrollY
	};
}

function commentChanged(textarea, maxChars, spanId) {
	if (textarea.value.length <= maxChars) {
		cl = document.getElementById(spanId);
		if (cl) {
			cl.innerHTML = (maxChars - textarea.value.length) + '';
		}
	}
	else textarea.value = textarea.value.substr(0, maxChars);
}

function setInner(e, v) {
	document.getElementById(e).innerHTML = v;
}

function addToLink(lnk, oneOnly) {
	i = 1;
	add = '';
	loop = true;
	do {
		def = document.getElementById('tGroup' + i);
		if (def && def.checked) {
			if (add != '') add += ',' + def.value;
			else add += def.value;
			if (oneOnly) loop = false;
		}
		++i;
	} while (def && loop);
	if (add != '') {
		if (lnk.href.indexOf('?') == -1) {
			lnk.href += '?';
		}
		else {
			lnk.href += '&';
		}
		lnk.href += 'ids=' + add;
		return true;
	}
	else {
		alert('Ne mogu izvrsiti!');
		return false;
	}
}

function attachHandler(elm, evType, fn, useCapture) {
	if (elm.addEventListener) {
		elm.addEventListener(evType, fn, useCapture);
		return true;
	}
	else if (elm.attachEvent) {
		var r = elm.attachEvent('on' + evType, fn);
		return r;
	}
	else {
		elm['on' + evType] = fn;
	}
}
//create onDomReady Event
window.onDomReady = DomReady;
function DomReady(fn) {
	if (document.addEventListener) {
		document.addEventListener("DOMContentLoaded", fn, false);
	} else {
		document.onreadystatechange = function () { readyState(fn) }
	}
}
function readyState(fn) {
	if (document.readyState == "interactive") {
		fn();
	}
}
function dlgCnf(msgId) {
	msgs = new Array('Da li sigurno zelis da obrises poruku?', 'Da li sigurno zelis da obrises sve komentare?');
	return confirm(msgs[msgId - 1]);
}

function fixImgs(whichId, maxW) {
	var pix = document.getElementById(whichId).getElementsByTagName('img');
	for (i = 0; i < pix.length; i++) {
		w = pix[i].width;
		h = pix[i].height;
		if (w > maxW) {
			f = 1 - ((w - maxW) / w);
			pix[i].width = w * f;
			pix[i].height = h * f;
		}
	}
}