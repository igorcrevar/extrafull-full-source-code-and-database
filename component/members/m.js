	 function vote_m(_id,_v){
	 	 _option=-1; 
	 	 sendAJAX('task=addKarma&id='+_id+'&vote='+_v,'v_cs');
	 } 
	 function del_c(_id,_c_id){
	 	 _option=0;
	 	 sendAJAX('task=deleteComment&id='+_id+'&c_id='+_c_id,'p_cs');
	 }
	 function prv_msg(_id){
	 	 s=document.getElementById('subject').value;
 	   t=document.getElementById('text').value;
 	   if (s.length<2 || t.length<3)
 	   {
   	  alert('Duzina naslova ne sme biti kraca od 2 znaka, a duzina poruke kraca od 3 znaka!');
   	  return false;
 	   } 
 	   _option=3;
	 	 sendAJAX('task=privateMsg&id='+_id+'&subject='+encodeURIComponent(s)+'&text='+encodeURIComponent(t),'msgMSG');
	 }
	 function add_friend(_id){
 	   _option=4;
	 	 sendAJAX('task=friendRequest&id='+_id,'msgFRIEND');
	 }
	 function com_pag(_page,_id){
	 	_option=1;
	 	sendAJAX('view=profile&id='+_id+'&limitstart='+_page,'p_cs');
	 }	 
	 function comment(_id){
	 	 _option=2;
	 	 t=document.getElementById('_comment').value;
	 	 if (t.length<4)
	 	 {
	 	   alert('Komentar mora da sadrzi barem cetiri znaka'); 
	 	   return false;
  	 } 
  	 document.getElementById('_comment').value = '';
	 	 sendAJAX('task=comment&comment='+encodeURIComponent(t)+'&id='+_id,'cmta');	 	 
	 }
 	 function requestdone(){	
		 if (http_request.readyState == 4){ 
 	 	   if (http_request.status == 200 || window.location.href.indexOf("http")==-1){ 
 	 	   	 r=http_request.responseText;
 	 	   	 if (_option==-1){
 	 	   	 	 rs = eval('('+r+')');
				 	 a = document.getElementById('vsum');
				 	 b = document.getElementById('vcnt');
				 	 a.innerHTML = parseInt(a.innerHTML)+rs.vote;
				 	 b.innerHTML = parseInt(b.innerHTML)+rs.inc;
				 	 _label.innerHTML = '';
 	 	   	 }else if (_option==2){
 	 	   	 	 	e=document.getElementById('p_cs');
 	 	   	 	 	e.innerHTML=r+e.innerHTML;
 	 	   	 	  _label.innerHTML='';
 	 	   	 }else if (_option<3){
 	 	   	 	 _label.innerHTML=r;
 	 	   	 }else if (_option==3){
 	   	 	   document.getElementById('subject').value='';
           e=document.getElementById('text');e.value='';
           commentChanged(e,1000,'cl_2');
           _label.innerHTML='<center><b>'+r+'</b></center>';
 	 	   	 }else if (_option==4){ 	 	   	 	 
           _label.innerHTML='<center><b>'+r+'</b></center>';
 	 	   	 }	
			 }	
		 }  
	 }

	 function showMyDiv(el,el2)
	 {
	 	  dv=document.getElementById(el).style;
	 	  dv2=document.getElementById(el2).style;
	 	  dv2.display="none";
	 	  dv.display=dv.display=="none"?"block":"none";
	 }
	 
	 function loadStat(_id,_posts){
	 		_option=1;
	 	  sendAJAX('view=profile&id='+_id+'&what=stat&posts='+_posts,'stats');	 	 
	 }
	 
	 function loadDesc(_id){
	 		_option=1;
	 	  sendAJAX('view=profile&id='+_id+'&what=descs','descs');	 	 
	 }

	 function loadLocs(_id){
	 		_option=1;
	 		str='view=myprofile';
	 		if (_id>0) str += '&id='+_id;
	 	  sendAJAX(str,'locs');
	 }
	 	
	 function writeDesc(_id){
	 		_option=1;
	 		txt = document.getElementById('mydesc').value;
	 		if (txt.length < 15){ alert('Moras opisati prijatelja opsirnije :)! Barem 15 znakova. Ajde, sta je to za tebe :)'); return;}
	 	  sendAJAX('view=profile&id='+_id+'&what=descs&txt='+txt,'descs');	 	 
	 }
	 
	function mDetails(el,name,avatar,loc,birth){
		dateJS=new Date();
		y=parseInt(dateJS.getFullYear());
		o=''+(dateJS.getMonth()<9?'0':'')+(dateJS.getMonth()+1)+'-'+(dateJS.getDate()<10?'0':'')+dateJS.getDate();
		hy=parseInt(birth.substr(0,4));
		hO=birth.substr(5);
		year=y-hy;//-parseInt(o<hO);
		if (avatar==null || avatar=='') avatar='s_nophoto.jpg';
		html='<center>'+name+' ('+year+')<br /><img align="center" src="'+jbase+'avatars/'+avatar+'" /><br />'+loc+'</center>';
		showPopUp(el,html,0);
	}