$.fn.dataTableExt.afnSortData['dom-checkbox'] = function  ( oSettings, iColumn )
{
	var aData = [];
	$( 'td:eq('+iColumn+') input', oSettings.oApi._fnGetTrNodes(oSettings) ).each( function () {
		aData.push( this.checked==true ? "1" : "0" );
	} );
	return aData;
}
$.fn.dataTableExt.afnSortData['dom-class'] = function  ( oSettings, iColumn )
{
	var aData = [];
	$(oSettings.oApi._fnGetTrNodes(oSettings)).find('td:eq('+iColumn+')').each( function () {
		aData.push( this.bgColor=="#00ff00" ? "1" : "0" );
	} );
	return aData;
}


$(function(){criteriaInit();});

var criteriaSortedTable=null;

function criteriaInit(){
	criteriaSortedTable=$('#criteriaList').dataTable({"aaSorting": [[ 2, "asc" ]], "bLengthChange":false, "sPaginationType":"two_button", "bAutoWidth":false,  "iDisplayLength":10, "bFilter": true,"aoColumns": [{ "sSortDataType": "dom-checkbox" }, {"sType":"html"}, null, null]});
}

		/*     competition stuff    */
		
		$(function(){competitionInit();resetCompetitionForm();});
		
		var competitionSortedTable;
		function competitionInit(){
		var params={"bAutoWidth":false,"aaSorting": [[ 2, "asc" ]],"sPaginationType":"full_numbers","aoColumns": [{"sType":"html"}, null, null, null]};
	 competitionSortedTable=$('#competitionList').dataTable();
	}
		
	function resetCompetitionForm(){
			$(criteriaSortedTable.fnGetNodes()).find(':input').attr("checked", false).attr("enabled", true);		
			criteriaSortedTable.fnSort([[1, 'asc']]);
			  $("#competitionForm :input[type=text]").val("");		
		      $('#competitionForm :input').removeClass('redborder');
			  $("#competitionForm select").val("");		
			  $("#competitionForm textarea").val("");			  		  			  
			  $("#competitionTitle").html("Add competition");		
			$('#competitionForm').attr('url', BASE+'/ajax/competition/create');
			$('#competitionForm tr[id=submittr] td').html('<input type=submit value=Add ><br /><input type=button value="Select all" onclick="javascript:$(criteriaSortedTable.fnGetNodes()).find(\':input\').attr(&quot;checked&quot;, true);" /><input type=button value="Select none" onclick="javascript:$(criteriaSortedTable.fnGetNodes()).find(\':input\').attr(&quot;checked&quot;, false);" /><div id=errordiv ></div>');				
			
	}
	
		function createCompetitionCallback(data){
			$('#competitionForm :input').removeClass('redborder');
			$('#competitionForm tr[id=submittr] td div[id=errordiv]').html('');
			data=$.parseJSON(data);
			if(data.success){
				name=$('#competitionForm :input:eq(0)').val();		
				de=makehtml($('#competitionForm :input:eq(4)').val());							
				ord=makehtml($('#competitionForm :input:eq(1)').val());							
				gp=makehtml($('#competitionForm :input:eq(2)').val());							
				g=makehtml($('#competitionForm :input:eq(2) option:selected').text());			
				tp=makehtml($('#competitionForm :input:eq(3)').val());							
				t=makehtml($('#competitionForm :input:eq(3) option:selected').text());						
				var isUp=$(competitionSortedTable.fnGetNodes()).filter('#competitionList'+data.id);	
				if(isUp.size()){
					$(isUp).children('td:nth-child(1)').html('<a href=\'javascript:populateCompetitionForm("'+data.id+'")\' >'+name+'</a><br><i>'+de+'</i>');
					isUp.attr("categories", data.categories);
				}else{
					  var a=competitionSortedTable.fnAddData(['<a href=\'javascript:populateCompetitionForm("'+data.id+'")\' >'+name+'</a><br /><i>'+de+'</i>', ord, g, t]);
						var oSettings = competitionSortedTable.fnSettings();
						var nTr = oSettings.aoData[ a[0] ].nTr;
						nTr.setAttribute('id', 'competitionList'+data.id);
						nTr.setAttribute("categories", data.categories);
				}
				resetCompetitionForm();
				$('#competitionList'+data.id+' td:nth-child(3)').attr('group', gp);
				$('#competitionList'+data.id+' td:nth-child(3)').html(gp==0?"<em>"+g+"</em>":g);
				$('#competitionList'+data.id+' td:nth-child(4)').attr('type', tp);
				$('#competitionList'+data.id+' td:nth-child(4)').html(t);
				$('#competitionList'+data.id+' td:nth-child(2)').html(ord);
			
				$('#competitionList'+data.id+' td:nth-child(3)').attr("onclick","javascript:updateCompCol("+data.id+",3)");
				$('#competitionList'+data.id+' td:nth-child(4)').attr("onclick","javascript:updateCompCol("+data.id+",4)");
				$('#competitionList'+data.id+' td:nth-child(5)').attr("onclick","javascript:updateCompCol("+data.id+",5)");
				
				
				if(isUp.size())
					$('#competitionForm tr[id=submittr] td div[id=errordiv]').html('Competition updated');
				else
					$('#competitionForm tr[id=submittr] td div[id=errordiv]').html('Competiton added');
			}else{
				err=$('#competitionForm :input[name|='+data.error+']');
				if(err.size()){
					err.addClass('redborder');
				}else $('#competitionForm tr[id=submittr] td div[id=errordiv]').html("<font color=red >"+data.error+"</font>");
			}
		}
	
	function ctypeChange(){
		if($('#competitionForm :input:eq(3)').val()==1){
			$(criteriaSortedTable.fnGetNodes()).find(':input').attr("disabled", "disabled");				
		}else{
			$(criteriaSortedTable.fnGetNodes()).find(':input').attr("disabled", "");				
		
		}
	}
		
	function populateCompetitionForm(id){
		resetCompetitionForm();
		var cats=$('#competitionList'+id).attr("categories").split(",");
		if(cats){
			var selector='#criteriaList'+cats.join(",#criteriaList");
			$(criteriaSortedTable.fnGetNodes()).filter(selector).find(':input').attr("checked", true);						
			criteriaSortedTable.fnSort([[0, 'desc']]);
		}
		$('#competitionForm :input:eq(0)').val($('#competitionList'+id+' td:nth-child(1) a').html());
		$('#competitionForm :input:eq(1)').val($('#competitionList'+id+' td:nth-child(2)').html());
		$('#competitionForm :input:eq(2)').val($('#competitionList'+id+' td:nth-child(3)').attr("group"));
		$('#competitionForm :input:eq(3)').val($('#competitionList'+id+' td:nth-child(4)').attr("type"));
		$('#competitionForm :input:eq(4)').val($('#competitionList'+id+' td:nth-child(1) i').html());

		$('#competitionForm').attr('url', BASE+'/ajax/competition/edit/'+id);
		$('tr[id=submittr] td').html('<div id=errordiv ></div><input type=submit value="Update" /> &nbsp; <input type=button value="Delete" onclick="javascript:askDeleteCompetition('+id+')"> &nbsp; <input type=button value="Clear" onclick="javascript:resetCompetitionForm()"><br /><input type=button value="Select all" onclick="javascript:$(criteriaSortedTable.fnGetNodes()).find(\':input\').attr(&quot;checked&quot;, true);" /><input type=button value="Select none" onclick="javascript:$(criteriaSortedTable.fnGetNodes()).find(\':input\').attr(&quot;checked&quot;, false);" />');						
	}
	

	
	function askDeleteCompetition(id){
		$('#competitionForm tr:eq(4) td:nth-child(1)').html('Are you sure you want <br />to delete this competition?<br /><input type=button value="No" onclick="javascript:resetCompetitionForm()"/><br /><br /> \<input type=button value="Delete" onclick="javascript:deleteCompetition('+id+')">');								
	}
	
	function deleteCompetition(id){
		$.getJSON(BASE+'/ajax/competition/delete/'+id, '', function(data){
			if(data.success=='False'){			
				resetCompetitionForm();							
				$('#competitionForm tr:eq(3) td div[id=errordiv]').html('<font color=red >Error deleting competition</font>');
			}else{
				resetCompetitionForm();						
				$('#competitionForm tr:eq(3) td div[id=errordiv]').html('Competition deleted');
				del=$(competitionSortedTable.fnGetNodes()).filter('#competitionList'+id)[0];
				competitionSortedTable.fnDeleteRow(del);
			}
			});								
	}	
	function submitForm2(id, callback){
		form="#"+id;
		data=$(form).serialize()+"&";
		data+=$(criteriaSortedTable.fnGetNodes()).find(":input").serialize();
		$.ajax({url:$(form).attr("url"), data:data, type:"POST", dateType:"json", success:callback});
		
	}
	function submitForm(id, callback){
		form="#"+id;
		data=$(form).serialize();
		$.ajax({url:$(form).attr("url"), data:data, type:"POST", dateType:"json", success:callback});
	}
	
	function makehtml(text) {
var textneu = text.replace(/&/,"&amp;");
textneu = textneu.replace(/</,"&lt;");
textneu = textneu.replace(/>/,"&gt;");
textneu = textneu.replace(/\r\n/,"<br>");
textneu = textneu.replace(/\n/,"<br>");
textneu = textneu.replace(/\r/,"<br>");
return(textneu);
}


