var img_file_name='';
function thumbChoose(id){
  img_file_name=id;;
  selectedImage=document.getElementById(id).name.substr(8);
	i=document.getElementById("mid_image");	
	i.name=img_file_name;
	i.src=jpath+'b_'+img_file_name;
	ajaxReq(1);
}

function ajaxReq(_o){
	_option=_o;
  if (selectedImage==-1)return;
  switch (_option)
  {
  	case 1:
  	 _action='imageview';
  	 break;
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
		    setInner("comment_start",rv);
		  }else if (_option==3){
				p=eval("("+rv+")");
				setInner("vote_count",p.count);
				setInner("vote_avg",p.avg);
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
	  base = '/photos/'+event_id+'e/b_'+mi.name;
	  html="<HTML><HEAD><TITLE>Photo</TITLE>"+
	  "</HEAD>"+
    "<BODY LEFTMARGIN=0 MARGINWIDTH=0 TOPMARGIN=0 MARGINHEIGHT=0><CENTER>"+
    "<IMG SRC='"+ base+"' BORDER=0 NAME=image onload='maxx=document.image.width+10;maxy=document.image.height+56;if (maxx>1000) maxx=1000;if(maxy>720) maxy=720;window.resizeTo(maxx,maxy);'>"+
    "</CENTER>"+
    "</BODY></HTML>";
    popup=window.open('','image','top=0,left=0,status=0,toolbar=0,location=0,directories=0,menuBar=0,scrollbars=1,resizable=1');
	  popup.document.open();
    popup.document.write(html);
    popup.focus();
    popup.document.close()
	} 
}		