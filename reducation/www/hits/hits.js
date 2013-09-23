function updateHits(){
httpObject = getHTTPObject();
if (httpObject != null) {
	alert("HI");
//httpObject.open("GET", "http://fast.crasxit.net/hits/hits.php?echo=1", true);
httpObject.send(null);
httpObject.onreadystatechange = doHitsUpdate;
setTimeout("updateHits()",5000);
}
}
function doHitsUpdate(){
if(httpObject.readyState == 4){
document.getElementById('counter').innerHTML = httpObject.responseText;
}
}

function getHTTPObject(){
if (window.ActiveXObject) return new ActiveXObject("Microsoft.XMLHTTP");
else if (window.XMLHttpRequest) return new XMLHttpRequest();
else return null;
} 

var httpObject;