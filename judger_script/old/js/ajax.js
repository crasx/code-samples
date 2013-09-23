setInterval( "check_hash()", 50 );
window.location.hash="0";

var current_hash = window.location.hash;

var jhistory=new Array();
jhistory[0]=Array("", "page");
var changingHash=false;
function check_hash() {
	if(changingHash)return;
    if ( window.location.hash != current_hash ) {
		current_hash=window.location.hash;
		var current_hash1=current_hash.substr(1);
		if(!isNaN(current_hash1)&&jhistory.length>current_hash1){
		getHttp2(jhistory[current_hash1][0], jhistory[current_hash1][1]);
		}
    }
}

function getHttp(url, divi){
 changingHash=true;
loaded=false;
var nextH=jhistory.length;
jhistory[nextH]=new Array(url, divi);
window.location.hash=nextH;
current_hash=window.location.hash;
changingHash=false;
var httpObject = getHTTPObject();
if (httpObject != null) {
httpObject.open("GET", url, true);
httpObject.send(null);
httpObject.onreadystatechange = function (){
if(httpObject.readyState == 4){
if(httpObject.responseText=="")document.getElementById(divi).innerHTML = "Error occurred loading page";
else document.getElementById(divi).innerHTML = httpObject.responseText;
loaded=true;
}
}
}
}

function getHttp2(url, divi){
loaded=false;
var httpObject = getHTTPObject();
if (httpObject != null) {
httpObject.open("GET", url, true);
httpObject.send(null);
httpObject.onreadystatechange = function (){
if(httpObject.readyState == 4){
if(httpObject.responseText=="")document.getElementById(divi).innerHTML = "Error occurred loading page";
else document.getElementById(divi).innerHTML = httpObject.responseText;
loaded=true;
}
}
}
}

var loaded=false;

function getHttpWait(url, div, init){
if(loaded){
if(!init){
getHttp(url, div);
waitLoadScroll();
return;
}
} 

setTimeout("getHttpWait('"+url+"', '"+div+"',0)", 50);
}

function getHTTPObject(){
if (window.ActiveXObject) return new ActiveXObject("Microsoft.XMLHTTP");
else if (window.XMLHttpRequest) return new XMLHttpRequest();
else return null;
} 
   function getUri(httpurl,name, divr) {
	   var getstr="";
//      var getstr =findInputs(document.getElementById(name));
	  var f=document.getElementsByTagName("input");
	  for(i=0;i<f.length;i++){
		  if(f[i].form.id==name){
			if (f[i].tagName == "INPUT") {
				if (f[i].type == "text") {
					getstr += f[i].name + "=" + escape(f[i].value) + "&";
				}
				if (f[i].type == "password") {
					getstr += f[i].name + "=" + escape(f[i].value) + "&";
				}	
				if (f[i].type == "checkbox") {
					if (f[i].checked) {
					getstr += f[i].name + "=" + escape(f[i].value) + "&";
					} else {
					getstr += f[i].name + "=&";
					}
				}
				if (f[i].type == "radio") {
					if (f[i].checked) {
					getstr += f[i].name + "=" + escape(f[i].value) + "&";
					}
				} 
			}			  
	  	  }
	  }
	  	 f=document.getElementsByTagName("select");
	  for(i=0;i<f.length;i++){
		  if(f[i].form.id==name){
				getstr += f[i].name + "=" + escape(f[i].options[f[i].selectedIndex].value) + "&";
		  }			  
	  }	  	 
	   f=document.getElementsByTagName("textarea");
	  for(i=0;i<f.length;i++){
		  if(f[i].form.id==name){
				getstr += f[i].name + "=" + escape(f[i].value) + "&";
		  }			  
	  }
      getHttp2(httpurl+getstr, divr);
   }
   var cal=false;
   function loadcal(){
	if(!cal){
		loadjs("js/dayjs.js", "js");
		loadjs("css/daycss.css", "css");
		cal=true;
	}		
   }
   
      var slider=false;
   function loadslider(){
	if(!slider){
		loadjs("slider/slider.js", "js");
		loadjs("slider/slider.css", "css");
		slider=true;
	}		
	
   }
   function waitLoadScroll(){
	if(!loaded)setTimeout(waitLoadScroll, 2);
	else{ 
	carpeInit();
	}
   }
	   
   
function loadjs(filename, filetype){
	var fileref
 if (filetype=="js"){ //if filename is a external JavaScript file
   fileref=document.createElement('script')
  fileref.setAttribute("type","text/javascript")
  fileref.setAttribute("src", filename)
 }
 else if (filetype=="css"){ //if filename is an external CSS file
   fileref=document.createElement("link")
  fileref.setAttribute("rel", "stylesheet")
  fileref.setAttribute("type", "text/css")
  fileref.setAttribute("href", filename)
 }
 if (typeof fileref!="undefined")
  document.getElementsByTagName("head")[0].appendChild(fileref)
 
}


//////////////////////
function editFrameHeight(){
	var frme=document.getElementById("rframe");
	if(frme.height<frme.contentWindow.document.body.scrollHeight)
	frme.height=frme.contentWindow.document.body.scrollHeight+15;
	if(frme.width<frme.contentWindow.document.body.scrollWidth )
	frme.width=frme.contentWindow.document.body.scrollWidth+ 70;
}
function createIframe(url){
	document.getElementById('page').innerHTML = "<table><tr><td valign=top ><iframe src='"+url+"' onload=editFrameHeight() onchange=editFrameHeight() frameborder=0 id=rframe allowTransparency='true' ><p>Your browser doesnt supprort iframes</p></iframe><div id=loader ><img src=images/load.gif ></div></td><td valign=top ><div id=clist ></div></td></tr></table>";
	document.getElementById("loader").style.display="none";
	getHttp("ajax-php.php?page=reg&list=1", "clist");
}

function createIframe2(url){
	document.getElementById('page').innerHTML = "<iframe src='"+url+"' onload=editFrameHeight() onchange=editFrameHeight() frameborder=0 id=rframe allowTransparency='true' ><p>Your browser doesnt supprort iframes</p></iframe>";
	document.getElementById("loader").style.display="none";
	getHttp("ajax-php.php?page=reg&list=1", "clist");
}
statsbar=0;
doneload=0;
function regEvent(evt){
	frame=document.getElementById("rframe");
	switch(evt){
	case "0"://nothing
	break;
	case "2":// upload
	case "1": //load
	butn=frame.contentWindow.document.getElementById('upbutton');
		 butn.onclick = function(event)
		{
			frame=document.getElementById("rframe").contentWindow.document; 		
		   document.getElementById("rframe").style.display="none";
		   document.getElementById("loader").style.display="block";
		   frame.getElementById('upform').submit();
		   document.getElementById("clist").style.display="none";
		}

	}
		  document.getElementById("loader").style.display="none";
		 document.getElementById("rframe").style.display="block";
		 document.getElementById("clist").style.display="block";
		getHttp("ajax-php.php?page=reg&list=1", "clist");
		setTimeout("editFrameHeight()", 100);
		   
}

function setFrameLoc(loc){
	document.getElementById("rframe").src=loc;
	setTimeout('getHttp("ajax-php.php?page=reg&list=1", "clist")',2000);

}
