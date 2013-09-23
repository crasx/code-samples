$.fn.dataTableExt.afnSortData['dom-class'] = function  ( oSettings, iColumn )
{
	var aData = [];
	$(oSettings.oApi._fnGetTrNodes(oSettings)).find('td:eq('+iColumn+')').each( function () {
		aData.push( this.bgColor=="#00ff00" ? "1" : "0" );
	} );
	return aData;
}

var groupSortedTable=null;

$(function(){groupingInit();resetGroupForm();});

		function updateGroupCheck(id, row){
		$.getJSON(BASE+'/ajax/group/check/'+id+'?row='+row, '', function(data){
			if(data.success=='False'){					
				alert("Error updating check! "+data.error);	
			}else{
				$('#groupList'+id+' td:nth-child('+row+')').animate({"backgroundColor":"'"+data.color+"'"}, 500);
				$('#groupList'+id+' td:nth-child('+row+')').attr("bgcolor",data.color);
			}
			});								
	}	
	
		function createGroupingCallback(data){
			$('#groupForm :input').removeClass('redborder');
			$('#groupForm tr:[id=submittr] td div[id=errordiv]').html('');
			data=$.parseJSON(data);
			if(data.success){
				name=$('#groupForm :input:eq(0)').val();																												
				var isUp=$(groupSortedTable.fnGetNodes()).filter('#groupList'+data.id);	
				if(isUp.size()){	
				$(isUp).children('td:nth-child(1)').html('<a href=\'javascript:populateGroupForm("'+data.id+'")\' >'+name+'</a>');
				}else{
					
				var tmpArr=['<a href=\'javascript:populateGroupForm("'+data.id+'")\' >'+name+'</a>','&nbsp;','&nbsp;','&nbsp;'];
				if(RSS){
					tmpArr.push("&nbsp;");
					tmpArr.push("&nbsp;");
				}
				if(PUB)tmpArr.push("&nbsp;");
				var a=groupSortedTable.fnAddData(tmpArr);
					var oSettings = groupSortedTable.fnSettings();
					var nTr = oSettings.aoData[ a[0] ].nTr;
					nTr.setAttribute('id', 'groupList'+data.id);			
					
				}$('#groupList'+data.id+' td:nth-child(2)').attr("bgcolor",$('#groupForm :input:eq(1)').attr("checked")?"#00ff00":"#ff0000");
					$('#groupList'+data.id+' td:nth-child(3)').attr("bgcolor",$('#groupForm :input:eq(2)').attr("checked")?"#00ff00":"#ff0000");
					var row=5;
					if(RSS){$('#groupList'+data.id+' td:nth-child('+(row++)+')').attr("bgcolor",$('#groupForm :input:eq('+(row-2)+')').attr("checked")?"#00ff00":"#ff0000");$('#groupList'+data.id+' td:nth-child('+(row++)+')').attr("bgcolor",$('#groupForm :input:eq('+(row-2)+')').attr("checked")?"#00ff00":"#ff0000");
					
					}
					if(PUB)$('#groupList'+data.id+' td:nth-child('+(row++)+')').attr("bgcolor",$('#groupForm :input:eq('+(row-2)+')').attr("checked")?"#00ff00":"#ff0000");
					
					resetGroupForm();
				if(isUp)
					$('#groupForm tr:[id=submittr] td div[id=errordiv]').html('Group field updated');
				else
					$('#groupForm tr:[id=submittr] td div[id=errordiv]').html('Group added');
			}else{
				err=$('#groupForm :input[name='+data.error+']');
				if(err.size()){
					err.addClass('redborder');
				}else $('#groupForm tr:[id=submittr] td div[id=errordiv]').html("<font color=red >"+data.error+"</font>");
			}
		}
		
function askDeleteGroup(id){
		$('#groupForm tr:[id=submittr] td').html('Are you sure you want <br />to delete this group?<br /><input type=button value="No" onclick="javascript:resetGroupForm()"/><br /><br /> \<input type=button value="Delete" onclick="javascript:deleteGroup('+id+')">');								
	}
	
	function deleteGroup(id){
		$.getJSON(BASE+'/ajax/group/delete/'+id, '', function(data){
			if(data.success=='False'){			
				resetGroupForm();							
				$('#groupForm tr:[id=submittr] td div[id=errordiv]').html('<font color=red >Error deleting group</font>');
			}else{
				resetGroupForm();						
				$('#groupForm tr:[id=submittr] td div[id=errordiv]').html('Group deleted');
				del=$(groupSortedTable.fnGetNodes()).filter('#groupList'+id)[0];				
				groupSortedTable.fnDeleteRow(del);
			}
			});								
	}
	
	function populateGroupForm(id){
		$('#groupForm :input').removeClass('redborder');
		$('#groupForm :input:eq(0)').val($('#groupList'+id+' td:nth-child(1) a').html());
		$('#groupForm :input:eq(1)').attr("checked",$('#groupList'+id+' td:nth-child(2)').attr("bgcolor")=="#00ff00");
		$('#groupForm :input:eq(2)').attr("checked",$('#groupList'+id+' td:nth-child(3)').attr("bgcolor")=="#00ff00");
		var row=4;
		if(RSS){$('#groupForm :input:eq('+(row++)+')').attr("checked",$('#groupList'+id+' td:nth-child('+(row)+')').attr("bgcolor")=="#00ff00");
		$('#groupForm :input:eq('+(row++)+')').attr("checked",$('#groupList'+id+' td:nth-child('+(row)+')').attr("bgcolor")=="#00ff00");
		}
		if(PUB)$('#groupForm :input:eq('+(row++)+')').attr("checked",$('#groupList'+id+' td:nth-child('+(row)+')').attr("bgcolor")=="#00ff00");
		
		
		$('#groupForm').attr('url', BASE+'/ajax/group/edit/'+id);
		$('#groupForm tr[id=submittr] td').html('<div id=errordiv ></div><br /><input type=submit value="Update" /> &nbsp; <input type=button value="Delete" onclick="javascript:askDeleteGroup('+id+')"> &nbsp; <input type=button value="Reset" onclick="javascript:resetCustomForm()">');						
	}

function groupingInit(){
	
	var params={"aaSorting": [[ 0, "desc" ]],  "sPaginationType":"two_button", "bAutoWidth":false,  "iDisplayLength":10, "aoColumns": [{"sType":"html"}, {"sSortDataType": "dom-class" }, {"sSortDataType": "dom-class" }, {"sSortDataType": "dom-class" }]};
	if(RSS){
		params.aoColumns.push({"sSortDataType": "dom-class" });
		params.aoColumns.push({"sSortDataType": "dom-class" });
	}
	if(PUB){
		params.aoColumns.push({"sSortDataType": "dom-class" });
		params.aoColumns.push({"sSortDataType": "dom-class" });
	 }
	 groupSortedTable=$('#groupList').dataTable();
	 
}
function resetGroupForm(){
		$('#groupForm :input').removeClass('redborder');	
		$('#groupForm tr:[id=submittr] td').html('<input type=submit value=Add ><div id=errordiv ></div>');	
		$('#groupForm :input[type=text]').val('');
		$('#groupForm :input[type=checkbox]').attr('checked', false);
		$('#groupForm').attr('url', BASE+'/ajax/group/create/');
}


	function submitForm(id, callback){
		form="#"+id;
		data=$(form).serialize();
		$.ajax({url:$(form).attr("url"), data:data, type:"POST", dateType:"json", success:callback});
		
	}