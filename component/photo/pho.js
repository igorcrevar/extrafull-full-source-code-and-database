var img_file_name='';
function thumbChoose(id){
	tmp=id.split('-', 2);
  img_file_name=tmp[1];
  selectedImage=tmp[0];
	i=document.getElementById("mid_image");	
	i.name=img_file_name;
	i.src=jpath+'b_'+img_file_name;
	_option=1;
	sendAJAX('view=image&image_id='+selectedImage+'&useraction=imageview', 'ajax_msg');
}
function galPag(o,eid,cnt){
	_option=4;
	sendAJAX('view=event&id='+eid+'&cnt='+cnt+'&limitstart='+o,'s_images');
}
function imgPag(o,eid,st,cnt){
	_option=5;
	sendAJAX( 'view=images&id='+eid+'&cnt='+cnt+'&stat='+st+'&limitstart='+o,'s_images');	
}

function vote(v){
	_option=3;
	sendAJAX('view=image&image_id='+selectedImage+'&useraction=user_vote'+'&user_vote='+v, 'ajax_msg');
}

function ajaxReq(_o){
	_option=_o;
  if (selectedImage==-1)return;
  switch (_option)
  {
  	case 2:	   
  	 t_area=document.getElementById("user_comment");
  	 v=t_area.value;
  	 t_area.value='';
  	 if (v=='') return;
  	 _action='user_comment'+'&user_comment='+encodeURIComponent(v);
  	 break;
  	case 3:
  	 v=document.getElementById("user_vote").value;
	 _action='user_vote'+'&user_vote='+v;
  	 break;
	case 6:
	   _action='favourite';    
  }
  sendAJAX('view=image&image_id='+selectedImage+'&useraction='+_action, 'ajax_msg');
}

function delCmnt(_i){	
	_option=1;
	sendAJAX('view=image&task=change&id='+selectedImage+'&cid='+_i, 'ajax_msg');
}

function ajaxPag(s,o)
{	
	_option=4+(o==0?0:1);
	if (o==0) f='view=event&id='+event_id;
	else{	
		f='view=images';
		if (event_id>0) f+='&id='+event_id;
		if (o>0) f+='&stat='+(o-1);
	}	
	sendAJAX(f+'&limitstart='+s,'s_images');
}

function requestdone(){
	if (http_request.readyState==4){ 
    _label.innerHTML='';
 	  if (http_request.status==200 || window.location.href.indexOf("http")==-1){ 
		  rv=http_request.responseText;
			error=rv.indexOf('!');
			if (error>=0 && error<6){}
			else if (_option==1){
		 	setInner("image_info",rv);			
		  }else if (_option==2){
		    c = document.getElementById('comment_start');
		    c.innerHTML = rv + c.innerHTML;
		    c = document.getElementById('comm_cnt');
		    c.innerHTML = parseInt(c.innerHTML)+1;
		  }else if (_option==3){
				p=eval("("+rv+")");
				vs = document.getElementById('vote_sum');
				vc = document.getElementById('vote_count');
				p.vote += parseInt(vs.innerHTML);
				vs.innerHTML = p.vote;
				p.inc += parseInt(vc.innerHTML);
				vc.innerHTML = p.inc;
				avg = (p.vote / p.inc).toFixed(2);
				setInner("vote_count",p.inc);
				setInner("vote_avg", avg);
				c = document.getElementById('starCur');
				c.title = parseInt( avg / 5.0 * 100.0 );
				c.style.width = parseInt( avg / 5.0 * 85.0 )+'px';
		  }else if (_option==4){
		  	setInner("image_info",'');
		  	document.getElementById('mid_image').src=img_base+'default_galerija.jpg';
		  	setInner("s_images",rv);
		  }
		  else if (_option==5) setInner("s_images",rv);	
		  else if (_option==6){
		  	setInner('favourite','<img src="'+img_base+'rating_star.png"/>')
		  }
		  else if (_option==101) alert('Lista je promenjena');
		}
	}  
}
				
function ViewFullImage(){
	mi=document.getElementById('mid_image');
	if (mi.name!='defPic'){		
	  base = jbase+'/photos/'+event_id+'e/b_'+mi.name;
	  html="<HTML><HEAD><TITLE>Photo</TITLE>"+
	  "</HEAD>"+
    "<BODY LEFTMARGIN=0 MARGINWIDTH=0 TOPMARGIN=0 MARGINHEIGHT=0><CENTER>"+
    "<IMG SRC='"+base+"' BORDER=0 NAME=image onload='maxx=document.image.width+10;maxy=document.image.height+56;if (maxx>1000) maxx=1000;if(maxy>720) maxy=720;window.resizeTo(maxx,maxy);'>"+
    "</CENTER>"+
    "</BODY></HTML>";
    popup=window.open('','image','top=0,left=0,status=0,toolbar=0,location=0,directories=0,menuBar=0,scrollbars=1,resizable=1');
	  popup.document.open();
    popup.document.write(html);
    popup.focus();
    popup.document.close()
	} 
}		

/* AJAX Star Rating : v1.0.3 : 2008/05/06 */
/* http://www.nofunc.com/AJAX_Star_Rating/ */

function $(v,o) { return((typeof(o)=='object'?o:document).getElementById(v)); }
function $S(o) { return((typeof(o)=='object'?o:$(o)).style); }
function agent(v) { return(Math.max(navigator.userAgent.toLowerCase().indexOf(v),0)); }
function abPos(o) { var o=(typeof(o)=='object'?o:$(o)), z={X:0,Y:0}; while(o!=null) { z.X+=o.offsetLeft; z.Y+=o.offsetTop; o=o.offsetParent; }; return(z); }
function XY(e,v) { var o=agent('msie')?{'X':window.event.clientX+document.body.parentElement.scrollLeft,'Y':window.event.clientY+document.body.parentElement.scrollTop}:{'X':e.pageX,'Y':e.pageY}; return(v?o[v]:o); }

star={};

star.mouse=function(e,o) { 
if(star.stop || isNaN(star.stop)) { 
	document.onmousemove=function(e) { 
	star.stop=0;
	
		var p=abPos($('star')), x=XY(e), oX=x.X-p.X, oY=x.Y-p.Y;
		if(oX<1 || oX>84 || oY<0 || oY>19) { star.stop=1; star.revert(); }
		
		else {
			document.body.style.cursor='pointer';
			var grade = parseInt(oX / 17) + 1;
			$('starUser').innerHTML = grade;
			$S('starUser').color='#111';
			tmpX = grade * 17 - 1;
			$S('starCur').width=tmpX+'px';
		}
	};
} };

star.update=function(e,o) { var n=star.num, 
	v = parseInt($('starUser').innerHTML);
	sendAJAX('view=image&image_id='+selectedImage+'&useraction=user_vote&user_vote='+v, 'ajax_msg');
	_option=3;
};

star.revert=function() { 
	document.body.style.cursor='default';
	v=parseInt($('starCur').title);
	$S('starCur').width=Math.round(v*84/100)+'px';
	$('starUser').innerHTML='';
	$('starUser').style.color='#888';	
	document.onmousemove='';
};
