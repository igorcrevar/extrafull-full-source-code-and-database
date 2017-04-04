	 function events_call(_st){
	 	 _option=1; 
	 	 sendAJAX('view=list&old='+_st,'events_block');
	 } 
	 function attend(_id){
	 	 _option=0; 
	 	 sendAJAX( 'task=attend&id='+_id, 'who_attend' );
	 } 
	 function requestdone(){	
		 if (http_request.readyState == 4){ 
 	 	   if (http_request.status == 200 || window.location.href.indexOf("http")==-1){ 
 	 	   	 r=http_request.responseText;
 	 	   	 if (_option<3)
 	 	   	 {
 	 	   	 	 _label.innerHTML=r;
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
	 function isOk(a){
	 	 if (!a){	 	 	 
	 	 	 if (document.getElementById('loc_name').value.length < 2)
	 	 	   alert('Naziv lokacije mora imati barem 2 karaktera');
	 	 	 else return true;
	 	 }
	 	 else{
	 	 	 var myDate=new Date();
			 myDate.setFullYear(document.getElementById('yyear').value,document.getElementById('mmonth').value-1,document.getElementById('dday').value);
       var today = new Date();
       var maxday = new Date();       
       var yy = today.getYear();
       yy = (yy < 1000) ? yy + 1900 : yy;
       maxday.setFullYear(yy,today.getMonth()+2,today.getDate());
       if (today>myDate) alert('Ne moze se uneti dogadjaj koji se vec desio');
       else if (myDate>maxday) alert('Ne moze se uneti desavanje za dva meseca unapred')
	 	 	 else if (document.getElementById('event_name').value.length < 3)
	 	 	   alert('Naziv dogadjaja mora imati barem 3 karaktera');	 	 	   
	 	 	 else return true;
	 	 }
 	 	 return false;	
	 }