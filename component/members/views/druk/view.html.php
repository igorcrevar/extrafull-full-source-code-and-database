<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.view');

class MembersViewDruk extends JView
{
	function display( $tpl = null)
	{
		$model = $this->getModel();
	  $html = $model->loadDruks();	  
		 ?>
		 <script type="text/javascript">
    	var druk;
    	var _TIME = 20;
    	var time;
    	var drukbt;
    	var druktm;
    	var _option=1;
    	var _status=0;
    	var http_request;
   function loadme(_option)
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
			switch (_option)
			{
				case 1:break;
				case 2:break;
				default:;
			}
    	http_request = oAsync;
		  http_request.onreadystatechange = requestdone;			
			http_request.open('POST', '<?php echo JURI::base();?>inxdex.php?option=com_members&view=druk', true );
			http_request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			http_request.send('new_score='+druk+'&format=raw');
		}
		function requestdone() 
		{
			 if (http_request.readyState == 4) 
			 {
			 	  if (http_request.status == 200 || window.location.href.indexOf("http")==-1) 
					{
						alert('Press ok!');
						_status = 0;
						d = document.getElementById('showstat');
						d.innerHTML=http_request.responseText;
						druktm.innerHTML='Your score : '+druk;
						drukbt.innerHTML='Start!';
						switch (_option)
						{
							case 1:break;
							case 2:break;
							default:;
						}
				  }
			 }		
		}	 
    	function tick_me()
    	{
    		--time;    		
    		druktm.innerHTML = time+" seconds";
    		if (time==0)
    		{
    			end();
    			return;
    		}
    		setTimeout("tick_me()",1000); 		
    	}
    	function end()
    	{
    		_status = 2;
    		drukbt.innerHTML='Please wait...';
    		druktm.innerHTML='&nbsp';
    		loadme(1);
    	}
    	function start()
    	{
    		druktm = document.getElementById('seconds');
    		drukbt = document.getElementById('drukat');
    		time = _TIME;
    		druk = 0;
    		_status=1;
    		druktm.innerHTML = time+" seconds";
    		setTimeout("tick_me()",1000);
    	}
    	function drukme()
    	{
    		 if (_status>1) return;
    		 if (_status==0)
    		   start()
    		 else
    		   ++druk;
    		 drukbt.innerHTML = 'Score : '+druk;
    	}
</script>
      <table><tr valign="top"><td align="center">		
      <font size="6"><span id="seconds">&nbsp;</span></font><br>
      <button id="drukat" style="padding:0px;font-size:20pt;width:400px;height:200px;background-color:lightgreen" onmousedown="drukme();" onmouseup="">Start!</button>
		  </td><td align="center">
		  <div id="showstat">	
		  	<?php echo $html;?>
		  </div>	
		  </td></tr></table>	
		 <?php
	}	  
}
?>
