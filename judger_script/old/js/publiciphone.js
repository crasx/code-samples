setInterval( "check_hash()", 50 );
var current_hash = window.location.hash;
window.location.hash="0";
var jhistory=new Array();
jhistory[0]=Array("public.php", "page");
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
				if (f[i].type == "text"||f[i].type == "hidden") {
					getstr += f[i].name + "=" + f[i].value + "&";
				}
				if (f[i].type == "password") {
					getstr += f[i].name + "=" + f[i].value + "&";
				}	
				if (f[i].type == "checkbox") {
					if (f[i].checked) {
					getstr += f[i].name + "=" + f[i].value + "&";
					} else {
					getstr += f[i].name + "=&";
					}
				}
				if (f[i].type == "radio") {
					if (f[i].checked) {
					getstr += f[i].name + "=" + f[i].value + "&";
					}
				} 
			}			  
	  	  }
	  }
	  	 f=document.getElementsByTagName("select");
	  for(i=0;i<f.length;i++){
		  if(f[i].form.id==name){
				getstr += f[i].name + "=" + f[i].options[f[i].selectedIndex].value + "&";
		  }			  
	  }	  	 
	   f=document.getElementsByTagName("textarea");
	  for(i=0;i<f.length;i++){
		  if(f[i].form.id==name){
				getstr += f[i].name + "=" + f[i].value + "&";
		  }			  
	  }
      getHttp2(httpurl+getstr, divr);
   }
   var cal=false;
   function loadcal(){
	if(!cal){
		loadjs("dayjs.js", "js");
		loadjs("daycss.css", "css");
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


function crefresh(){
	if( document.refreshForm.check.value == "" )
	{
		document.refreshForm.check.value = "1";
	}
	else
	{
		var current_hash1=current_hash.substr(1);
		if(!isNaN(current_hash1)&&jhistory.length>current_hash1){
		getHttp2(jhistory[current_hash1][0], jhistory[current_hash1][1]);
		}
	}
	
}
function switchImg(id, input){
	
	if(document.getElementById(id).src.substring(document.getElementById(id).src.indexOf("/img/")+5)=="cbe.png"){document.getElementById(id).src="img/cbf.png";
	document.getElementById(input).value="1";
	}else{
	document.getElementById(input).value="0";
		document.getElementById(id).src="img/cbe.png";
	}
}