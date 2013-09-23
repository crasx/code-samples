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
