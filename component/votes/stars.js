/* AJAX Star Rating : v1.0.3 : 2008/05/06 */
/* http://www.nofunc.com/AJAX_Star_Rating/ */

function $(v,o) { return((typeof(o)=='object'?o:document).getElementById(v)); }
function $S(o) { return((typeof(o)=='object'?o:$(o)).style); }
function agent(v) { return(Math.max(navigator.userAgent.toLowerCase().indexOf(v),0)); }
function abPos(o) { var o=(typeof(o)=='object'?o:$(o)), z={X:0,Y:0}; while(o!=null) { z.X+=o.offsetLeft; z.Y+=o.offsetTop; o=o.offsetParent; }; return(z); }
function XY(e,v) { var o=agent('msie')?{'X':window.event.clientX+document.body.parentElement.scrollLeft,'Y':window.event.clientY+document.body.parentElement.scrollTop}:{'X':e.pageX,'Y':e.pageY}; return(v?o[v]:o); }

star={};

star.mouse=function(e,o,_typeid) { 
if(star.stop || isNaN(star.stop)) { 
	document.onmousemove=function(e) { 
	star.stop=0;
	
		var p=abPos($('star'+_typeid)), x=XY(e), oX=x.X-p.X, oY=x.Y-p.Y;
		if(oX<1 || oX>84 || oY<0 || oY>19) { star.stop=1; star.revert(_typeid); }
		
		else {
			document.body.style.cursor='pointer';
			var grade = parseInt(oX / 17) + 1;
			$('starUser'+_typeid).innerHTML = grade;
			$S('starUser'+_typeid).color='#111';
			tmpX = grade * 17 - 1;
			$S('starCur'+_typeid).width=tmpX+'px';
		}
	};
} };

star.update=function(e,o,_type,_typeid) { var n=star.num, 
	v = parseInt($('starUser'+_typeid).innerHTML);
	old = component;
	component = 'votes';
	sendAJAX('vote='+v+'&t='+_type+'&id='+_typeid, 'ajax_stars'+_typeid);
	component = old;
};

star.revert=function(_typeid) { 
	document.body.style.cursor='default';
	v=parseInt($('starCur'+_typeid).title);
	$S('starCur'+_typeid).width=Math.round(v*84/100)+'px';
	$('starUser'+_typeid).innerHTML='';
	$('starUser'+_typeid).style.color='#888';	
	document.onmousemove='';
};
