$.fn.dataTableExt.afnSortData['dom-checkbox'] = function  ( oSettings, iColumn )
{
	var aData = [];
	$( 'td:eq('+iColumn+') input', oSettings.oApi._fnGetTrNodes(oSettings) ).each( function () {
		aData.push( this.checked==true ? "1" : "0" );
	} );
	return aData;
}
$.fn.dataTableExt.afnSortData['cust-name'] = function  ( oSettings, iColumn )
{
	var aData = [];
	$( 'td:eq(0) a:eq(0)', oSettings.oApi._fnGetTrNodes(oSettings) ).each( function () {

		aData.push($(this).html());
	} );
	return aData;

}
$(function(){competitionInit();contestantInit(); resetAddForm();});


	function resetAddForm(){
		resetCompNums();
			$("#imgholder").html('');
		$('#contestantForm :input').val('');
		$('#contestantForm').attr('url', BASE+'/ajax/contestant/create');
		$('#contestantForm tr:[id=submittr] td').html('<input type=submit value=Add ><div id=errordiv ></div>');
				$('#contestantForm tr:[id=uploadtr] td').html('<br />Image upload:<br /><input type=file id=rimg name=rimg  onchange="javascript:ajaxFileUpload()" ><br /><em>Upload image before submitting</em>');	
		$("#competitionList :input").attr("checked", false);
		
	}
	function resetCompNums(){
	for(var t=0;t<competitionSortedTable.fnGetNodes().length;t++){
	competitionSortedTable.fnUpdate(competitionSortedTable.fnGetNodes(t).getAttribute("maxId"),t,2);
	}
	}
var competitionSortedTable=null;

function competitionInit(){
	competitionSortedTable=$('#competitionList').dataTable({"aaSorting": [[ 1, "asc" ]], "bLengthChange":false, "bPaginate":false,"bInfo":false,"bAutoWidth":false,"aoColumns": [{ "sSortDataType": "dom-checkbox" }, {"sType":"html"},null]});
}
var contestantSortedTable=null;

function contestantInit(){
	contestantSortedTable=$('#contestantList').dataTable({"aaSorting": [[ 0, "desc" ]], "bLengthChange":false, "sPaginationType":"two_button", "bAutoWidth":false,  "iDisplayLength":10,"aoColumns": [{ "bSearchable": false},
{"sSortDataType": "cust-name" }]});
}

function createContestantCallback(data){	
			$('#contestantForm div[id=errordiv]').html('');
			$('#contestantForm :input').removeClass('redborder');
			data=$.parseJSON(data);
			if(data.success){
				name=$('#contestantForm :input:eq(0)').val();																				
				var isUp=$(contestantSortedTable.fnGetNodes()).filter('#contestantList'+data.id);	
				if(isUp.size()){
					isUp.html("<td>"+data.id+"</td><td><a href='javascript:editContestant("+data.id+")'>"+$('#contestantForm :input:eq(0)').val()+"</a><br />"+data.compList+"<em><a href='javascript:copyContestant("+data.id+")'>Copy</a></em></td>");
					isUp.attr('competitions', $.toJSON(data.regData));
					isUp.attr('custom', $.toJSON(data.custom));
					
				}else{
				var a=contestantSortedTable.fnAddData([data.id, "<a href='javascript:editContestant("+data.id+")'>"+$('#contestantForm :input:eq(0)').val()+"</a><br />"+data.compList+"<em><a href='javascript:copyContestant("+data.id+")'>Copy</a></em>"]);
					var oSettings = contestantSortedTable.fnSettings();
					var nTr = oSettings.aoData[ a[0] ].nTr;
				
					nTr.setAttribute('id', 'contestantList'+data.id);
					nTr.setAttribute('competitions', $.toJSON(data.regData));
					nTr.setAttribute('custom', $.toJSON(data.custom));
				}
				$.each(data.maxIds, function(id, val){$("#competitionList"+id).attr("maxid",val);});
				resetAddForm();				
				if(isUp.size())
					$('#contestantForm div[id=errordiv]').html('Contestant updated');
				else
					$('#contestantForm div[id=errordiv]').html('Contestant added');
			}else{
				err=$('#contestantForm :input[name='+data.error+']');
				if(err.size()){
					err.addClass('redborder');
				}else $('#contestantForm div[id=errordiv]').html("<font color=red >"+data.error+"</font>");
			}
}

function editContestant(id){
		resetAddForm();
		$('#contestantForm :input:[name=rname]').val($('#contestantList'+id+' td:nth-child(2) a').html());
		if($('#contestantList'+id).attr("custom")){
		data=$.parseJSON(stripslashes($('#contestantList'+id).attr("custom")));	
		$.each(data, function(key, value){$('#contestantForm :input:[name=rcust['+key+']]').val(value);});
		}
		$("#contestantForm").attr("url", BASE+"/ajax/contestant/edit/"+id);
		if($('#contestantList'+id).attr("competitions")){
		data=$.parseJSON(stripslashes($('#contestantList'+id).attr("competitions")));	
		$.each(data, function(key, value){$('#competitionList :input:[value='+key+']').attr("checked", true);$('#competitionList'+key+' td:nth-child(3)').html(value);});
		}
		data=$('#contestantList'+id).attr("image");	
		if(data=="1"){
		$('#contestantForm tr:[id=uploadtr] td').html('<br />Image upload:<br /><input type=file id=rimg name=rimg ><br /><input type=button onclick="javascript:ajaxFileUpload()" value=Upload />-<input type=button onclick="javascript:askDeleteImage('+id+')" value="Delete image" /><br /><em>Upload image before submitting<br />(will overwrite old image)</em>');
		$("#imgholder").html('<img src="'+BASE+'/image.php?i='+id+'" />');				
		}
		
		$('#contestantForm tr:[id=submittr] td').html('<input type=submit value=Update > - <input type=button onclick="javascript:askDeleteContestant('+id+')" value="Delete" /><div id=errordiv ></div>');
		
	}
function copyContestant(id){
		resetAddForm();
		$('#contestantForm :input:[name=rname]').val($('#contestantList'+id+' td:nth-child(2) a').html());
		if($('#contestantList'+id).attr("custom")){
		data=$.parseJSON(stripslashes($('#contestantList'+id).attr("custom")));	
		$.each(data, function(key, value){$('#contestantForm :input:[name=rcust['+key+']]').val(value);});
		}
	}
	jQuery.fn.htmlEncode = function() {
  return this.each(function(){
    var me   = jQuery(this);
    var html = me.html();
    me.html(html.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/'/g, "&apos;").replace(/"/g, "\\&apos;"));
  });
};
 
jQuery.fn.htmlDecode = function() {
  return this.each(function(){
    var me   = jQuery(this);
    var html = me.html();
    me.html(html.replace(/&amp;/g,'&').replace(/&lt;/g,'<').replace(/&gt;/g,'>'));
  });
};
 
	function askDeleteContestant(id){
		ohtm=$('#contestantForm tr:[id=submittr] td').htmlEncode().html();		
		$('#contestantForm tr:[id=submittr] td').html('Are you sure you want <br />to delete this registration?<br /><input type=button value="No" onclick="$(\'#contestantForm tr:[id=submittr] td\').html(\''+ohtm+'\')"/><br /><br /> <input type=button value="Delete" onclick="javascript:deleteContestant('+id+')">');								
	}
	
		function deleteContestant(id){
		$.getJSON(BASE+'/ajax/contestant/delete/'+id, '', function(data){
			if(data.success=='False'){		
				$('#contestantForm div[id=errordiv]').html('Error deleting contestant'+data.error);
			}else{
				del=$(contestantSortedTable.fnGetNodes()).filter('#contestantList'+id)[0];
				contestantSortedTable.fnDeleteRow(del);
				if(data.maxIds)
				$.each(data.maxIds, function(id, val){$("#competitionList"+id).attr("maxid",val);});
				resetAddForm();						
				$('#contestantForm div[id=errordiv]').html('Contestant deleted');
			}
			});								
	}	

	function askDeleteImage(id){
		ohtm=$('#contestantForm tr:[id=uploadtr] td').htmlEncode().html();		
		$('#contestantForm tr:[id=uploadtr] td').html('<center>Are you sure you want <br />to delete this contestant\'s image?<br /><input type=button value="No" onclick="$(\'#contestantForm tr:[id=uploadtr] td\').html(\''+ohtm+'\')"/><br /><br /> <input type=button value="Delete" onclick="javascript:deleteImage('+id+')"></center>');								
	}
	
		function deleteImage(id){
		$.getJSON(BASE+'/ajax/contestant/deleteImage/'+id, '', function(data){
			if(data.success=='False'){					
				$('#contestantForm div[id=errordiv]').html("Error deleting image! "+data.error);	
			}else{
				$('#contestantForm div[id=errordiv]').html("Image deleted!");
		$('#contestantForm tr:[id=uploadtr] td').html('<br />Image upload:<br /><input type=file id=rimg name=rimg  onchange="javascript:ajaxFileUpload()" ><br /><em>Upload image before submitting</em>');	
			$('#contestantList'+id).attr("image", "0");	
		
				
			}
			});								
	}	

	
	function stripslashes (str) {
	return (str+'').replace(/\\(.?)/g, function (s, n1) {
        switch (n1) {
            case '\\':
                return '\\';
            case '0':return '\u0000';
            case '':return '';
			default:return n1;
			}
    });
}
function addslashes (str) {
    return (str+'').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
}

function ajaxFileUpload(){
		$('#contestantForm tr:[id=uploadtr]').attr("style","display:none;");
		$('#contestantForm tr:[id=submittr]').attr("style","display:none;");
		$('#contestantForm tr:[id=loadertr]').attr("style","");
			$.ajaxFileUpload
		(
			{
				url:BASE+'/upload.php', 
				secureuri:false,
				fileElementId:'rimg',
				dataType: 'json',
				success: function (data, status)
				{
					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							alert(data.error);							
							$('#contestantForm tr:[id=uploadtr]').attr("style","");
							$('#contestantForm tr:[id=submittr]').attr("style","");		
						}else
						{
							$('#contestantForm tr:[id=uploadtr]').attr("style","");
							$('#contestantForm tr:[id=uploadtr] td').html("<input type=hidden name=rimg value='"+data.msg+"'><em>File uploaded</em>");
							$('#contestantForm tr:[id=submittr]').attr("style","");		
						}
					}
					$('#contestantForm tr:[id=loadertr]').attr("style","display:none;");
				},
				error: function (data, status, e)
				{
					alert(data.toSource());
				}
			}
		);
		
}

	function submitForm(id, id2, callback){		
		data=$("#"+id+" :input[type!=file],#"+id2+" :input").serialize();
		$.ajax({url:$("#"+id).attr("url"), data:data, type:"POST", dateType:"json", success:callback});
	}
	
	$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};


(function($){$.toJSON=function(o)
{if(typeof(JSON)=='object'&&JSON.stringify)
return JSON.stringify(o);var type=typeof(o);if(o===null)
return"null";if(type=="undefined")
return undefined;if(type=="number"||type=="boolean")
return o+"";if(type=="string")
return $.quoteString(o);if(type=='object')
{if(typeof o.toJSON=="function")
return $.toJSON(o.toJSON());if(o.constructor===Date)
{var month=o.getUTCMonth()+1;if(month<10)month='0'+month;var day=o.getUTCDate();if(day<10)day='0'+day;var year=o.getUTCFullYear();var hours=o.getUTCHours();if(hours<10)hours='0'+hours;var minutes=o.getUTCMinutes();if(minutes<10)minutes='0'+minutes;var seconds=o.getUTCSeconds();if(seconds<10)seconds='0'+seconds;var milli=o.getUTCMilliseconds();if(milli<100)milli='0'+milli;if(milli<10)milli='0'+milli;return'"'+year+'-'+month+'-'+day+'T'+
hours+':'+minutes+':'+seconds+'.'+milli+'Z"';}
if(o.constructor===Array)
{var ret=[];for(var i=0;i<o.length;i++)
ret.push($.toJSON(o[i])||"null");return"["+ret.join(",")+"]";}
var pairs=[];for(var k in o){var name;var type=typeof k;if(type=="number")
name='"'+k+'"';else if(type=="string")
name=$.quoteString(k);else
continue;if(typeof o[k]=="function")
continue;var val=$.toJSON(o[k]);pairs.push(name+":"+val);}
return"{"+pairs.join(", ")+"}";}};$.evalJSON=function(src)
{if(typeof(JSON)=='object'&&JSON.parse)
return JSON.parse(src);return eval("("+src+")");};$.secureEvalJSON=function(src)
{if(typeof(JSON)=='object'&&JSON.parse)
return JSON.parse(src);var filtered=src;filtered=filtered.replace(/\\["\\\/bfnrtu]/g,'@');filtered=filtered.replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,']');filtered=filtered.replace(/(?:^|:|,)(?:\s*\[)+/g,'');if(/^[\],:{}\s]*$/.test(filtered))
return eval("("+src+")");else
throw new SyntaxError("Error parsing JSON, source is not valid.");};$.quoteString=function(string)
{if(string.match(_escapeable))
{return'"'+string.replace(_escapeable,function(a)
{var c=_meta[a];if(typeof c==='string')return c;c=a.charCodeAt();return'\\u00'+Math.floor(c/16).toString(16)+(c%16).toString(16);})+'"';}
return'"'+string+'"';};var _escapeable=/["\\\x00-\x1f\x7f-\x9f]/g;var _meta={'\b':'\\b','\t':'\\t','\n':'\\n','\f':'\\f','\r':'\\r','"':'\\"','\\':'\\\\'};})(jQuery);