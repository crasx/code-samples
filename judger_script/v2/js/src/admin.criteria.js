
$(function(){criteriaInit();resetCriteriaForm();});


function criteriaInit(){
	criteriaSortedTable=$('#criteriaList').dataTable({"aaSorting": [[ 1, "asc" ]], "bLengthChange":false, "sPaginationType":"two_button", "bAutoWidth":false,  "iDisplayLength":10, "bFilter": true,"aoColumns": [ {"sType":"html"}, null, null]});
}


	function populateCriteriaForm(id){
		$('#criteriaForm :input').removeClass('redborder');
		$('#criteriaForm :input:eq(0)').val($('#criteriaList'+id+' td:nth-child(1) a').html());
		$('#criteriaForm :input:eq(1)').val($('#criteriaList'+id+' td:nth-child(2)').html());
		$('#criteriaForm :input:eq(2)').val($('#criteriaList'+id+' td:nth-child(3)').html());
		$('#criteriaForm').attr('url', BASE+'/ajax/criteria/edit/'+id);
		$('#criteriaForm tr:eq(3) td').html('<div id=errordiv ></div><input type=submit value="Update" /> &nbsp; <input type=button value="Delete" onclick="javascript:askDeleteCriteria('+id+')"> &nbsp; <input type=button value="Clear" onclick="javascript:resetCriteriaForm()">');						
	}
	function resetCriteriaForm(){
		$('#criteriaForm :input').val('');
		$('#criteriaForm').attr('url', BASE+'/ajax/criteria/create');
		$('#criteriaForm tr:eq(3) td').html('<input type=submit value=Add ><div id=errordiv ></div>');						
		
	}
	function askDeleteCriteria(id){
		$('#criteriaForm tr:eq(3) td').html('Are you sure you want <br />to delete this criteria?<br /><input type=button value="No" onclick="javascript:resetCriteriaForm()"/><br /><br /> \<input type=button value="Delete" onclick="javascript:deleteCriteria('+id+')">');								
	}
	
	
	function deleteCriteria(id){
		$.getJSON(BASE+'/ajax/criteria/delete/'+id, '', function(data){
			if(data.success=='False'){			
				resetCriteriaForm();							
				$('#criteriaForm tr:eq(3) td div[id=errordiv]').html('<font color=red >Error deleting criteria</font>');
			}else{
				resetCriteriaForm();						
				$('#criteriaForm tr:eq(3) td div[id=errordiv]').html('Criteria deleted');
				del=$(criteriaSortedTable.fnGetNodes()).filter('#criteriaList'+id)[0];				
				criteriaSortedTable.fnDeleteRow(del);
			}
			});								
	}
		function createCriteriaCallback(data){
			$('#criteriaForm tr:eq(3) td div[id=errordiv]').html('');
			$('#criteriaForm :input').removeClass('redborder');
			data=$.parseJSON(data);
			
			if(data.success){
				name=$('#criteriaForm :input:eq(0)').val();																				
				var isUp=$(criteriaSortedTable.fnGetNodes()).filter('#criteriaList'+data.id);	
				if(isUp.size()){
					
					isUp.html('<td><a href=\'javascript:populateCriteriaForm("'+data.id+'")\' >'+name+'</a></td><td>'+$('#criteriaForm :input:eq(1)').val()+'</td><td>'+$('#criteriaForm :input:eq(2)').val()+'</td>');
				}else{
				var a=criteriaSortedTable.fnAddData(['<a href=\'javascript:populateCriteriaForm("'+data.id+'")\' >'+name+'</a>', $('#criteriaForm :input:eq(1)').val(),$('#criteriaForm :input:eq(2)').val()]);
					var oSettings = criteriaSortedTable.fnSettings();
					var nTr = oSettings.aoData[ a[0] ].nTr;
					nTr.setAttribute('id', 'criteriaList'+data.id);
				}
				resetCriteriaForm();
				if(isUp.size())
					$('#criteriaForm tr:eq(3) td div[id=errordiv]').html('Criteria updated');
				else
					$('#criteriaForm tr:eq(3) td div[id=errordiv]').html('Criteria added');
			}else{
				err=$('#criteriaForm :input[name='+data.error+']');
				if(err.size()){
					err.addClass('redborder');
				}else $('#criteriaForm tr:eq(3) td div[id=errordiv]').html("<font color=red >"+data.error+"</font>");
			}
		}
		
	function submitForm(id, callback){
		form="#"+id;
		data=$(form).serialize();
		$.ajax({url:$(form).attr("url"), data:data, type:"POST", dateType:"json", success:callback});
	}
	