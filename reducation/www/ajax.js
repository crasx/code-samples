function getLHttp(url, divi){
loaded=false;
var httpObject = getLHTTPObject();
if (httpObject != null) {
httpObject.open("GET", url, true);
httpObject.send(null);
httpObject.onreadystatechange = function (){
if(httpObject.readyState == 4){
if(httpObject.responseText==""){
//	setTimeout(getLHttp(url, divi), 1000);
//	getLHttp(url, divi);
}
else document.getElementById(divi).innerHTML = httpObject.responseText;
loaded=true;
}
}
}
}

var loaded=false;


function getLHTTPObject(){
if (window.ActiveXObject) return new ActiveXObject("Microsoft.XMLHTTP");
else if (window.XMLHttpRequest) return new XMLHttpRequest();
else return null;
} 

   function getLUri(httpurl,name, divr) {
	   var getstr="";
//      var getstr =findInputs(document.getElementById(name));
	  var f=document.getElementsByTagName("input");
	  for(i=0;i<f.length;i++){
		  if(f[i].form!=null)
		  if(f[i].form.id==name){
			if (f[i].tagName == "INPUT") {
				if (f[i].type == "text") {
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
      getLHttp(httpurl+getstr, divr);
   }
	   
   
