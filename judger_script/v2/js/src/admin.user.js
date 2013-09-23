$.fn.dataTableExt.afnSortData['dom-class'] = function  ( oSettings, iColumn )
{
	var aData = [];
	$(oSettings.oApi._fnGetTrNodes(oSettings)).find('td:eq('+iColumn+')').each( function () {
		aData.push( this.bgColor=="#00ff00" ? "1" : "0" );
	} );
	return aData;
}

$(function(){userInit();resetUserForm();});

var userSortedTable=null;

function userInit(){
	userSortedTable=$('#userList').dataTable({"aaSorting": [[ 0, "asc" ]],  "sPaginationType":"two_button", "bAutoWidth":false,  "iDisplayLength":10, "aoColumns": [{"sType":"html"}, null, null, {"sSortDataType": "dom-class" }, {"sSortDataType": "dom-class" }, {"sSortDataType": "dom-class" }, {"sSortDataType": "dom-class" }]});
}
function resetUserForm(){
		$('#userForm :input').removeClass('redborder');	
		$('#userForm :input[type=password]').val('');	
		$('#userForm tr:eq(10) td').html('<input type=submit value=Add ><div id=errordiv ></div>');	
		$('#userForm :input[type=text]').val('');
		$('#userForm :input[type=checkbox]').attr('checked', false);
		$('#userForm').attr('url', BASE+'/ajax/user/create/');
	
}
	function populateUserForm(id){
		$('#userForm :input').removeClass('redborder');
		$('#userForm :input:eq(0)').val($('#userList'+id+' td:nth-child(1) a').html());
		$('#userForm :input:eq(1)').val($('#userList'+id+' td:nth-child(2)').html());
		$('#userForm :input:eq(2)').val($('#userList'+id+' td:nth-child(3)').html());
		$('#userForm :input[type=password]').val('');
		$('#userForm :input:eq(5)').attr("checked",$('#userList'+id+' td:nth-child(4)').attr("bgcolor")=="#00ff00");
		$('#userForm :input:eq(6)').attr("checked",$('#userList'+id+' td:nth-child(5)').attr("bgcolor")=="#00ff00");
		$('#userForm :input:eq(7)').attr("checked",$('#userList'+id+' td:nth-child(6)').attr("bgcolor")=="#00ff00");
		$('#userForm :input:eq(8)').attr("checked",$('#userList'+id+' td:nth-child(7)').attr("bgcolor")=="#00ff00");
		$('#userForm').attr('url', BASE+'/ajax/user/edit/'+id);
		$('#userForm tr:eq(10) td').html('<div id=errordiv ></div>Leave password blank to keep it the same<br /><input type=submit value="Update" /> &nbsp; <input type=button value="Delete" onclick="javascript:askDeleteUser('+id+')"><br /><input type=button value="Reset" onclick="javascript:resetUserForm()">');						
	}
function askDeleteUser(id){
		$('#userForm tr:eq(11) td').html('Are you sure you want <br />to delete this user?<br /><input type=button value="No" onclick="javascript:resetUserForm()"/><br /><br /> \<input type=button value="Delete" onclick="javascript:deleteUser('+id+')">');								
	}
	
	
	function deleteUser(id){
		$.getJSON(BASE+'/ajax/user/delete/'+id, '', function(data){
			if(data.success=='False'){			
				resetUserForm();							
				$('#userForm tr:eq(10) td div[id=errordiv]').html('<font color=red >Error deleting user</font>');
			}else{
				resetUserForm();						
				$('#userForm tr:eq(10) td div[id=errordiv]').html('User deleted');
				del=$(userSortedTable.fnGetNodes()).filter('#userList'+id)[0];				
				userSortedTable.fnDeleteRow(del);
			}
			});								
	}

		function createUserCallback(data){
			$('#userForm :input').removeClass('redborder');
			$('#userForm tr:eq(10) td div[id=errordiv]').html('');
			data=$.parseJSON(data);
			if(data.success){
				name=$('#userForm :input:eq(0)').val();																												
				var isUp=$(userSortedTable.fnGetNodes()).filter('#userList'+data.id)[0]; 			
				if(isUp){
					$(isUp).html('<td><a href=\'javascript:populateUserForm("'+data.id+'")\' >'+name+'</a></td><td>'+$('#userForm :input:eq(1)').val()+'</td><td>'+$('#userForm :input:eq(2)').val()+'</td><td bgcolor="'+($('#userForm :input:eq(5)').attr("checked")?"#00ff00":"#ff0000")+'">&nbsp;</td><td bgcolor="'+($('#userForm :input:eq(6)').attr("checked")?"#00ff00":"#ff0000")+'">&nbsp;</td><td bgcolor="'+($('#userForm :input:eq(7)').attr("checked")?"#00ff00":"#ff0000")+'">&nbsp;</td><td bgcolor="'+($('#userForm :input:eq(8)').attr("checked")?"#00ff00":"#ff0000")+'">&nbsp;</td>');
				}else{
				var a=userSortedTable.fnAddData(['<a href=\'javascript:populateUserForm("'+data.id+'")\' >'+name+'</a>',$('#userForm :input:eq(1)').val(),$('#userForm :input:eq(2)').val(), '&nbsp;','&nbsp;','&nbsp;','&nbsp;']);
					var oSettings = userSortedTable.fnSettings();
					var nTr = oSettings.aoData[ a[0] ].nTr;
					nTr.setAttribute('id', 'userList'+data.id);
					$('#userList'+data.id+' td:nth-child(4)').attr("bgcolor",$('#userForm :input:eq(5)').attr("checked")?"#00ff00":"#ff0000");
					$('#userList'+data.id+' td:nth-child(5)').attr("bgcolor",$('#userForm :input:eq(6)').attr("checked")?"#00ff00":"#ff0000");
					$('#userList'+data.id+' td:nth-child(6)').attr("bgcolor",$('#userForm :input:eq(7)').attr("checked")?"#00ff00":"#ff0000");
					$('#userList'+data.id+' td:nth-child(7)').attr("bgcolor",$('#userForm :input:eq(8)').attr("checked")?"#00ff00":"#ff0000");
					
				}
				resetUserForm();
				if(isUp)
					$('#userForm tr:eq(10) td div[id=errordiv]').html('User updated');
				else
					$('#userForm tr:eq(10) td div[id=errordiv]').html('User added');
			}else{
				err=$('#userForm :input[name='+data.error+']');
				if(err.size()){
					err.addClass('redborder');
				}else $('#userForm tr:eq(10) td div[id=errordiv]').html("<font color=red >"+data.error+"</font>");
			}
		}
		
	function submitForm(id, callback){
		form="#"+id;
		data=$(form).serialize();
		$.ajax({url:$(form).attr("url"), data:data, type:"POST", dateType:"json", success:callback});
		
	}
			
		function updateUserCheck(id, row){
		$.getJSON(BASE+'/ajax/user/check/'+id+'?row='+row, '', function(data){
			if(data.success=='False'){					
				alert("Error updating check! "+data.error);	
			}else{
				$('#userList'+id+' td:nth-child('+row+')').animate({"backgroundColor":"'"+data.color+"'"}, 500);
				$('#userList'+id+' td:nth-child('+row+')').attr("bgcolor",data.color);
			}
			});								
	}	
	