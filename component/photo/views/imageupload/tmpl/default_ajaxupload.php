<?php defined('_JEXEC') or die('Restricted access'); ?>
<div style="margin:20px 20px">
	<b>Uploadujem u galeriju sa id-om <?php echo $this->event_id;?></b>
	<!-- <label class="cabinet"> 
	 <input type="file" class="file" name="upload_file" id="upload_file" onchange="printFiles();" />
 	 </label> &nbsp; -->
 	 <input type="file" name="upload_file" id="upload_file" onchange="printFiles();" />
  <input type="button" id="sendAJAX" value="Posalji..."  onclick="uploadImageStart();" disabled=true /> 
  <br>
  <div id="ListOfImages"></div>
  <span id="my_span"></span>
  <div id="progress"></div>
  <br/>
  <br/>
  <span style="color:#FF0000;font-size:12px;"><b>Napomena: Slike moraju biti u rezoluciji 500x375 i ne vece od 60-70 Kb.
Slike moraju biti u formatu 4:3 i JPG. Mogu se uploadovati i slike u formatu 3:4, ali ce u tom
slucaju njihove umanjene slike izgledati razvuceno.</b></span>
<br><br>  
</div> 
<script type="text/javascript" language="javascript">
/* Ovaj javascript kod je delo Igora Crevara (crewce@hotmail.com)
   Zabranjeno je kopiranje ili koriscenje bez eksplicitne dozvole autora*/
		   		//SI.Files.stylizeById('upload_file');
		   		
		   		function getInternetExplorerVersion()
					{
  						var rv = -1; // Return value assumes failure.
  						if (navigator.appName == 'Microsoft Internet Explorer')
	  					{
    						var ua = navigator.userAgent;
    						var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
      					if (re.exec(ua) != null)
        					rv = parseFloat( RegExp.$1 );
    					}
   						 return rv;
 					 }
  
					 function getXMLHTTPObject() 
	 				 {
   	 					var oAsync = false;   
   						if (window.XMLHttpRequest) 
   						{
      					oAsync = new XMLHttpRequest();
   						}
   						else if (window.ActiveXObject)
   						{ 
      					try 
      					{
         					oAsync = new ActiveXObject("Msxml2.XMLHTTP")
      					} 
      					catch (e)
      					{
         					try
         					{
            				oAsync = new ActiveXObject("Microsoft.XMLHTTP")
         					}
         					catch (e){}
      					}
   						}
   						else 
   						{
    	  				return false;
   						}
   						return oAsync;
					}
	
          
					var http_request=null;
					var errorCnt = 0;
					var sendingStatus = 0;
					var currentDirectory;
					var selectedFilesCount = 0;
					var currImage = 0;
					var fileCount = 0;	
					//show the current percentage
  				var centerCell;
  				var size=50;
  				var increment = 100/size;
  				var origSelectedFiles;
  				
  				function createProgressBar() 
  				{
		  				var centerCellName;
	 						var tableText = '';
		  				for (x = 0; x < size; x++) 
		  				{
				 				tableText += '<td id="progress_' + x + '" width="5" height="20" bgcolor="blue"/>';
				 				if (x == (size/2)) 
				 				{
						 			centerCellName = "progress_" + x;
 				 				}
     					}
	   					var idiv = document.getElementById("progress");
     					idiv.innerHTML = '<table with="100" border="0" cellspacing="0" cellpadding="0"><tr>' + tableText + '</tr></table>';
		 					centerCell = document.getElementById(centerCellName);
		 					centerCell.innerHTML = "&nbsp;";
		 					timesInvoced = 0;
		 					origSelectedFiles = selectedFilesCount;
					}
  
  				var timesInvoced = 0;
					function showProgress() 
					{
		  				percentage = Math.round((++timesInvoced)*100 / origSelectedFiles);
	  					var percentageText = "";
	 						if (percentage < 10) 
	 						{
 								percentageText = "&nbsp;" + percentage;
 							} 
 							else 
 							{
 								percentageText = percentage;
							}
		  				centerCell.innerHTML = '<font color="white">' + percentageText + '%</font>';
							var tableText = "";
							for (x = 0; x < size; x++) 
							{
			  				var cell = document.getElementById("progress_" + x);
			  				if ((cell) && percentage/x < increment) 
			  				{
					 				cell.style.backgroundColor = "blue";
			  				} 
			  				else 
			  				{
				 					cell.style.backgroundColor = "green";
		 						}
		 					}	
					}
         			
          
					function uploadAJAX() 
					{		
						filePath = currentDirectory;
  					separator = filePath.indexOf('\\') != -1 ? '\\' : '/';
 	 					if (filePath[filePath.length-1] != separator) 
 	 	  				filePath += separator;
  					fileName = document.getElementById("fileName"+currImage).name;
  					filePath += fileName;
  					//desc = document.getElementById("description").value;
  					document.getElementById("status"+currImage).innerHTML = 'Sending...';	  	 	  
						http_request = getXMLHTTPObject();
						if (!http_request) 
						{
							alert('Cannot create XMLHTTP instance');
							return false;
						}		
						//prepare the MIME POST data
						var boundaryString = 'AaB03JoskaFisherIJaCareviOnomadx';
						var boundary = '--'+boundaryString;
						var requestbody = ''; 
    				if (selectedFilesCount == 1)
    				{
		  				//requestbody = requestbody +  '\n' + boundary + '\nContent-Disposition: form-data; name="description"\n\n'+desc;
						}	
						//else
							//requestbody = requestbody +  '\n' + boundary + '\nContent-Disposition: form-data; name="dont_update"\n\n1';	
						requestbody = requestbody +  '\n' + boundary + '\nContent-Disposition: form-data; name="file_number"\n\n' + filesSent;	
						event_id = <?php echo $this->event_id;?>;
						requestbody = requestbody +  '\n' + boundary + '\nContent-Disposition: form-data; name="event_id"\n\n' +event_id;	
    				requestbody = requestbody + 
		  				'\n' + boundary + '\nContent-Disposition: form-data; name="upload_file"; filename="' + fileName + '"' +
						  '\nContent-Type: application/octet-stream\nContent-Transfer-Encoding: binary\n\n';		
						try
						{			
		  				if (getInternetExplorerVersion()==-1)		
		  				{		
		  					netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
	   			 			netscape.security.PrivilegeManager.enablePrivilege("UniversalBrowserAccess");
	   			 			var file = Components.classes["@mozilla.org/file/local;1"].createInstance(Components.interfaces.nsILocalFile);
  	 			 			file.initWithPath( filePath );	
			  				stream = Components.classes["@mozilla.org/network/file-input-stream;1"].createInstance(Components.interfaces.nsIFileInputStream);
   				 			stream.init(file,	0x01, 00004, null);
   		 		 			var bstream =  Components.classes["@mozilla.org/network/buffered-input-stream;1"].getService();
   			 	 			bstream.QueryInterface(Components.interfaces.nsIBufferedInputStream);
     	   	 			bstream.init(stream, 1000);
   			 	 			bstream.QueryInterface(Components.interfaces.nsIInputStream);
   			 	 			binary = Components.classes["@mozilla.org/binaryinputstream;1"].createInstance(Components.interfaces.nsIBinaryInputStream);
   			 	 			binary.setInputStream (stream);
   		   	 			requestbody = requestbody + escape(binary.readBytes(binary.available()));
   		   	 			bstream.close();
   		   	 			stream.close();  
   		   	 			netscape.security.PrivilegeManager.revertPrivilege("UniversalBrowserAccess"); 		   	 			
		  					netscape.security.PrivilegeManager.revertPrivilege("UniversalXPConnect");
   		 				}
		   				else
   		 				{
           			var BinaryStream = new ActiveXObject("ADODB.Stream");
           			BinaryStream.Type = 1; //binary type
           			BinaryStream.Open();
           			BinaryStream.LoadFromFile(filePath);
           			content = BinaryStream.Read(-1);
           			BinaryStream.Close();
           			alert( content );
 					 			requestbody = requestbody + escape(content);
   		 				}	  
							requestbody = requestbody + '\n'+ boundary + '--';				
							
							http_request.onreadystatechange = requestdone;
							http_request.open('POST', 'index.php?option=com_photo&task=imagesUpload&format=raw', true ); 
							http_request.setRequestHeader("Content-type", "multipart/form-data, boundary="+boundaryString);
							//http_request.setRequestHeader("Connection", "close");
							http_request.setRequestHeader("Content-length", requestbody.length);
			  			http_request.send(requestbody);
		  				sendingStatus = 1;				
   					}
   					catch(er)
   					{
   		 				alert(er);
   					}	 							
				}
   
  			function repeatSending(message)
  			{
						el = document.getElementById("status"+currImage)
						el.innerHTML = '<font style="color:#ff0000">'+message+'</font>';
						sendingStatus = 2;
						if ( ++errorCnt < 3 ) //tri puta ako ne posalje CAO :)
   		    		uploadAJAX();
   		  		else
   		  		{
				  		document.getElementById("progress").innerHTML = 'Fajl se ne moze poslati!';
   		  			document.getElementById('sendAJAX').disabled = false;
   		  		}	  
				}
   
				function requestdone() 
				{
					if (http_request.readyState == 4) 
					{
						if (http_request.status == 200 || window.location.href.indexOf("http")==-1) 
						{
							result = http_request.responseText;
							if (result == 'ok')
							{
								++filesSent;
								showProgress();
				  			el = document.getElementById("status"+currImage);
				  			el.innerHTML = '<font style="color:#0000ff">Sent</font>';
				  			sendingStatus = 0;
				  			errorCnt = 0;
				  			--selectedFilesCount;
				  			if (!selectedFilesCount)
				  			{
				  				document.getElementById("progress").innerHTML = 'Upload slika zavrsen';
				  			}
				  			document.getElementById("fileName"+currImage).disabled = true;
				  			document.getElementById("fileName"+currImage).checked = false;
	  	    			while (++currImage < fileCount)
	  	    			{
	  	  	  			status = document.getElementById("fileName"+currImage).checked;
	  	  	  			if (status)
	  	  	  			{
 		 	        			uploadAJAX(false);
 		 	        			break;
 		 	      			}  	
		 	    			}  
		 	  			}
		 	  			else  
		 	  				repeatSending(result);
						} 
						else 
						{
							repeatSending('Greska!');
						}			
					}
				}

  			function uploadImageStart()
  			{
  	 			errorCnt = 0;
  	 			sendingStatus = 0;
		 			document.getElementById('sendAJAX').disabled = true;
		 			createProgressBar();		 
  	 			currImage = 0;
		 			do
	   			{
	   	 			el = document.getElementById("fileName"+currImage);
	  	 			if (el.checked && !el.disabled)
	   	 			{
 		      		uploadAJAX(true);
 		      		break;
 		   			}  
		 			}
		 			while (++currImage < fileCount);
  			}
  	
				function isImage(fileName)
				{
					fileName = fileName.toLowerCase();
					arr = [".jpg",".gif"];
		 			for (i = 0;i<arr.length;++i)
	    			if (fileName.indexOf(arr[i])>-1)
	       			return true;	 
	   			return false;
  			}
  
  			function readFolderIE(path)
				{
		 			var files = null;
      	   try
      	   {
      	     var fso = new ActiveXObject("Scripting.FileSystemObject");
         	   folder = fso.GetFolder(path);
      	   }
      	   catch(e)
      	   {
      	      alert(e);
      	   	  return null;
      	   }
           fc = new Enumerator(folder.files);
           s = "";
           files = [];
           for (; !fc.atEnd(); fc.moveNext())
           {
           	  if (isImage(fc.item().Name))
           	    files[files.length] = fc.item().Name;
           }
           return files
      	}   
        
      	function readFolder(path,folders)
      	{
      		 try 
      		 {
      		 	  netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect")
      		    netscape.security.PrivilegeManager.enablePrivilege("UniversalBrowserAccess");
      		    file = Components.classes["@mozilla.org/file/local;1"]
                           .createInstance(Components.interfaces.nsILocalFile);
              file.initWithPath(path);		 
      	      var entries = file.directoryEntries;
      	   } 
      	   catch (e) 
      	   {
      		    alert(e);
      		    return;
      	   }
      		 var array = [];
      		 while(entries.hasMoreElements())
      		 {
      			  var entry = entries.getNext();
        			entry.QueryInterface(Components.interfaces.nsIFile);
        			if ( (entry.isDirectory() && folders) || (!entry.isDirectory() && !folders &&
        			    isImage(entry.leafName)) )
        			  array.push(entry.leafName);
      		 }		 
           netscape.security.PrivilegeManager.revertPrivilege("UniversalBrowserAccess");
           netscape.security.PrivilegeManager.revertPrivilege("UniversalXPConnect")
           return array;
      	}   
      		
      	function printFiles()
      	{	        		
      		listOfImages = document.getElementById("ListOfImages");
      		listOfImages.innerHTML = "";
      		try
      		{
         		 el = document.getElementById("upload_file");
         		 val = el.value;
         		 if ( val.indexOf('\\') == -1 && val.indexOf('/') == -1 ){
  	     		 	 netscape.security.PrivilegeManager.enablePrivilege("UniversalFileRead");
         		 	 val = el.value;
	       		 	 netscape.security.PrivilegeManager.revertPrivilege("UniversalFileRead");
         		 }
         		 
         		 try{el.value="";}catch(er){}
           	 separator = val.indexOf('\\') != -1 ? '\\' : '/';
         		 val = val.substr(0,val.lastIndexOf(separator)+1);
         		 currentDirectory = val;
         		 if (getInternetExplorerVersion()!=-1)
         		   a = readFolderIE(val);
         		 else
         	     a = readFolder(val,false);
         		 if (a==null) return;	      	   
         	   if (a.length>0)
         	   {
         	     s = "<table>\n<tr><th>File name</th><th>State</th></tr>\n";
         	   	 for (i =0;i<a.length;++i)
         	   	 {
         	   	   s = s+'<tr><td>';
           	   	 s = s+'<input type="checkbox" name="'+a[i]+'" id="fileName'+i+'" checked onclick="checkBoxChanged('+i+');" />'+a[i]+'</td>\n';
         	   	   s = s+'<td><span id="status'+i+'">Ready</span></td>\n';
         	     }
         	     s= s+'\n</table>';
         	     listOfImages.innerHTML = s;
         	     fileCount = a.length;
         	     selectedFilesCount = a.length;
         	     document.getElementById("progress").innerHTML = '';
         	     document.getElementById("sendAJAX").disabled = false;
         	     filesSent = 0;
         	   }  
         	   else
         	     document.getElementById("sendAJAX").disabled = true;
         	 }
         	 catch(er)
         	 {
         	 	   document.getElementById("sendAJAX").disabled = true;
         	 }             	
      	}   
      		
      	function checkBoxChanged(val)
      	{
      		 el = document.getElementById("fileName"+val);			
      		 if (el.checked)
      		   ++selectedFilesCount;
      		 else
      		 	 --selectedFilesCount;  
      		 document.getElementById("sendAJAX").disabled = selectedFilesCount == 0;
      	}	 
</script>		   				  