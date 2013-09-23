$.fn.dataTableExt.afnSortData['dom-class'] = function  ( oSettings, iColumn )
{
	var aData = [];
	$(oSettings.oApi._fnGetTrNodes(oSettings)).find('td:eq('+iColumn+')').each( function () {
		aData.push( this.bgColor=="#00ff00" ? "1" : "0" );
	} );
	return aData;
}

$(function(){customInit();resetCustomForm();});

var customSortedTable=null;
var compSortedTable=null;

function customInit(){ 
	var params={"aaSorting": [[ 0, "desc" ]],  "sPaginationType":"two_button", "bAutoWidth":false,  "iDisplayLength":10, "aoColumns": [{"sType":"html"}, {"sSortDataType": "dom-class" }, {"sSortDataType": "dom-class" }, {"sSortDataType": "dom-class" }]};
	if(PUB){
		params.aoColumns.push({"sSortDataType": "dom-class" });
		params.aoColumns.push({"sSortDataType": "dom-class" });
	}
	if(RSS)
		params.aoColumns.push({"sSortDataType": "dom-class" });
	 customSortedTable=$('#customList').dataTable(params);
	 compSortedTable=$('#compList').dataTable();
	 
}
function resetCustomForm(){
		$('#customForm :input').removeClass('redborder');	
		$('#customForm tr:[id=submittr] td').html('<input type=submit value=Add ><br /><input type=button value="Select all" onclick="javascript:$(compSortedTable.fnGetNodes()).find(\':input\').attr(&quot;checked&quot;, true);" /><input type=button value="Select none" onclick="javascript:$(compSortedTable.fnGetNodes()).find(\':input\').attr(&quot;checked&quot;, false);" /><div id=errordiv ></div>');	
		$('#customForm :input[type=text]').val('');
		$('#customForm :input[type=checkbox]').attr('checked', false);
		$('#customForm').attr('url', BASE+'/ajax/custom/create/');
		$(compSortedTable.fnGetNodes()).find(':input').attr("checked", false);		
}
	function populateCustomForm(id){
		$(compSortedTable.fnGetNodes()).find(':input').attr("checked", false);	
		var cats=$('#customList'+id).attr("competitions").split(",");
		if(cats){
			var selector='#compList'+cats.join(",#compList");
			$(compSortedTable.fnGetNodes()).filter(selector).find(':input').attr("checked", true);						
			compSortedTable.fnSort([[0, 'desc']]);
		}
		$('#customForm :input').removeClass('redborder');
		$('#customForm :input:eq(0)').val($('#customList'+id+' td:nth-child(1) a').html());
		$('#customForm :input:eq(1)').attr("checked",$('#customList'+id+' td:nth-child(2)').attr("bgcolor")=="#00ff00");
		$('#customForm :input:eq(2)').attr("checked",$('#customList'+id+' td:nth-child(3)').attr("bgcolor")=="#00ff00");
		$('#customForm :input:eq(3)').attr("checked",$('#customList'+id+' td:nth-child(4)').attr("bgcolor")=="#00ff00");
		var row=4;
		if(RSS){$('#customForm :input:eq('+(row++)+')').attr("checked",$('#customList'+id+' td:nth-child('+(row)+')').attr("bgcolor")=="#00ff00");
		$('#customForm :input:eq('+(row++)+')').attr("checked",$('#customList'+id+' td:nth-child('+(row)+')').attr("bgcolor")=="#00ff00");
		}
		if(PUB)$('#customForm :input:eq('+(row++)+')').attr("checked",$('#customList'+id+' td:nth-child('+(row)+')').attr("bgcolor")=="#00ff00");
		
		$('#customForm').attr('url', BASE+'/ajax/custom/edit/'+id);
		$('#customForm tr[id=submittr] td').html('<div id=errordiv ></div><br /><input type=submit value="Update" /> &nbsp; <input type=button value="Delete" onclick="javascript:askDeleteCustom('+id+')"> &nbsp; <input type=button value="Reset" onclick="javascript:resetCustomForm()"><br /><input type=button value="Select all" onclick="javascript:$(compSortedTable.fnGetNodes()).find(\':input\').attr(&quot;checked&quot;, true);" /><input type=button value="Select none" onclick="javascript:$(compSortedTable.fnGetNodes()).find(\':input\').attr(&quot;checked&quot;, false);" />');
	}
function askDeleteCustom(id){
		$('#customForm tr:[id=submittr] td').html('Are you sure you want <br />to delete this custom category?<br /><input type=button value="No" onclick="javascript:resetCustomForm()"/><br /><br /> \<input type=button value="Delete" onclick="javascript:deleteCustom('+id+')">');								
	}
	
	function deleteCustom(id){
		$.getJSON(BASE+'/ajax/custom/delete/'+id, '', function(data){
			if(data.success=='False'){			
				resetCustomForm();							
				$('#customForm tr:[id=submittr] td div[id=errordiv]').html('<font color=red >Error deleting custom field</font>');
			}else{
				resetCustomForm();						
				$('#customForm tr:[id=submittr] td div[id=errordiv]').html('Custom field deleted');
				del=$(customSortedTable.fnGetNodes()).filter('#customList'+id)[0];				
				customSortedTable.fnDeleteRow(del);
			}
			});								
	}
	
		function createCustomCallback(data){
			$('#customForm :input').removeClass('redborder');
			$('#customForm tr:[id=submittr] td div[id=errordiv]').html('');
			data=$.parseJSON(data);
			if(data.success){
				name=$('#customForm :input:eq(0)').val();	
				var isUp=$(customSortedTable.fnGetNodes()).filter('#customList'+data.id);	
				if(isUp.size()){	
				$(isUp).children('td:nth-child(1)').html('<a href=\'javascript:populateCustomForm("'+data.id+'")\' >'+name+'</a>');
						
					isUp.attr("competitions", data.clist);																						
				}else{
				var tmpArr=['<a href=\'javascript:populateCustomForm("'+data.id+'")\' >'+name+'</a>','&nbsp;','&nbsp;','&nbsp;'];
				if(RSS){
					tmpArr.push("&nbsp;");
					tmpArr.push("&nbsp;");
				}
				if(PUB)tmpArr.push("&nbsp;");
				var a=customSortedTable.fnAddData(tmpArr);
				
					var oSettings = customSortedTable.fnSettings();
					var nTr = oSettings.aoData[ a[0] ].nTr;
					nTr.setAttribute('id', 'customList'+data.id);		
					nTr.setAttribute("competitions", data.clist);			
				}	
				$('#customList'+data.id+' td:nth-child(2)').attr("bgcolor",$('#customForm :input:eq(1)').attr("checked")?"#00ff00":"#ff0000");
					$('#customList'+data.id+' td:nth-child(3)').attr("bgcolor",$('#customForm :input:eq(2)').attr("checked")?"#00ff00":"#ff0000");
					$('#customList'+data.id+' td:nth-child(4)').attr("bgcolor",$('#customForm :input:eq(3)').attr("checked")?"#00ff00":"#ff0000");
					var row=5;
					if(RSS){$('#customList'+data.id+' td:nth-child('+(row++)+')').attr("bgcolor",$('#customForm :input:eq('+(row-2)+')').attr("checked")?"#00ff00":"#ff0000");$('#customList'+data.id+' td:nth-child('+(row++)+')').attr("bgcolor",$('#customForm :input:eq('+(row-2)+')').attr("checked")?"#00ff00":"#ff0000");
					
					}
					if(PUB)$('#customList'+data.id+' td:nth-child('+(row++)+')').attr("bgcolor",$('#customForm :input:eq('+(row-2)+')').attr("checked")?"#00ff00":"#ff0000");
					
				resetCustomForm();
				if(isUp)
					$('#customForm tr:[id=submittr] td div[id=errordiv]').html('Custom field updated');
				else
					$('#customForm tr:[id=submittr] td div[id=errordiv]').html('Custom field added');
			}else{
				err=$('#customForm :input[name='+data.error+']');
				if(err.size()){
					err.addClass('redborder');
				}else $('#customForm tr:[id=submittr] td div[id=errordiv]').html("<font color=red >"+data.error+"</font>");
			}
		}
		
		
		
		function updateCustomCheck(id, row){
		$.getJSON(BASE+'/ajax/custom/check/'+id+'?row='+row, '', function(data){
			if(data.success=='False'){					
				alert("Error updating check! "+data.error);	
			}else{
				$('#customList'+id+' td:nth-child('+row+')').animate({"backgroundColor":"'"+data.color+"'"}, 500);
				$('#customList'+id+' td:nth-child('+row+')').attr("bgcolor",data.color);
			}
			});								
	}	
	
	function submitForm(id, callback){
		form="#"+id;
		data=$(form).serialize();		
		data+="&"+$(compSortedTable.fnGetNodes()).find(":input").serialize();
		$.ajax({url:$(form).attr("url"), data:data, type:"POST", dateType:"json", success:callback});
		
	}