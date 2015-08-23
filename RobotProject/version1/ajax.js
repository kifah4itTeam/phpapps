
// JavaScript Document
var xmlHttp;
if(window.ActiveXObject)
{   
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
   }
   else 
   { 
   xmlHttp= new XMLHttpRequest();  
   //alert("YYYYYY");
   }
   
   function makerequest(serverPage,objID)
   {  
     var obj=document.getElementById(objID);
	 xmlHttp.open("GET",serverPage);
	 xmlHttp.onreadystatechange=function()
	 {    
	    if(xmlHttp.readyState==4 && xmlHttp.status==200)
		   {  
		     
			 obj.innerHTML=xmlHttp.responseText;
			  }
	   
	     
		 }
    
	xmlHttp.send(null);
	 }//End Function 