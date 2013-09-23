

$(function(){criteriaInit();resetAll();});

var criteriaSortedTable=null;

function criteriaInit(){
	criteriaSortedTable=$('#contestantList').dataTable({"bSort": false, "bLengthChange":false, "sPaginationType":"two_button", "bAutoWidth":false,  "iDisplayLength":10, "bFilter": false,"aoColumns": [{"sType":"html"}]});
}


function startScore(id){
	$("#slider"+id).animate( { "background-color" : "#ff0000"}, 500);	
}
function disableAll(){
$.each($('div [id*=slider]'), function(index,el){ $(el).slider("option","value",$(el).slider("option","min")); $(el).animate( { 'background-color' : '#ff0000'}, 500);$(el).slider("disable");});
$.each($('div [id*=score]'), function(index,el){ $(el).attr("readonly", true);$(el).val($(el).attr("min"));});
$("#noShow").attr("disabled","disabled");
$("#noShow").attr("value","No show");
}
function resetAll(){
$.each($('div [id*=slider]'), function(index,el){ $(el).slider("option","value",$(el).slider("option","min")); $(el).animate( { 'background-color' : '#ff0000'}, 200);$(el).slider("enable");});
$.each($('div [id*=score]'), function(index,el){ $(el).attr("readonly", false); $(el).val($(el).attr("min"));});
$("#noShow").attr("disabled","");
$("#noShow").attr("value","No show");
}

function noShow(){
	per=$("#jform").attr("edit");
	if($('#contestantList'+per).attr("noshow")==1){
		resetAll();
		$('#contestantList'+per).attr("noshow", 0);
	}else
	$.getJSON($("#jform").attr("url")+"?cont="+per+"&ns=1", '', function(data){
			if(data.success=='False'){					
				alert("Error setting no show! "+data.error);	
			}else{
				$('#contestantList'+per).attr("noshow", 1);
				$('#contestantList'+per).attr("scores", "[]");
					disableAll();$("#noShow").attr("disabled","");
					$("#noShow").attr("value","Contestant here");
				$('#contestantList'+per+' td:nth-child(1)').animate({backgroundColor:"'#ff0000'"}, 500);
				$('#contestantList'+per+' td:nth-child(1)').attr("bgcolor",'#ff0000');
				$('#contestantList'+per+' td:nth-child(1)').attr("color",'#FFFFFF');
				$('#contestantList'+per+' td:nth-child(1)').animate({color:"'#FFFFFF'"}, 500);
			}
			});			
}


function updateScore(id){
	score=$("#score"+id).val();
	$.getJSON($("#jform").attr("url")+"?cont="+$("#jform").attr("edit")+"&c="+id+"&s="+score, '', function(data){
			if(data.success=='False'){					
				alert("Error updating! "+data.error);	
			}else{
				uid=$("#jform").attr("edit");
				$('#contestantList'+uid).attr("scores", data.scores);
				$('#contestantList'+uid+' td:nth-child(1)').animate({backgroundColor:"'"+data.color+"'"}, 500);
				$('#contestantList'+uid+' td:nth-child(1)').attr("bgcolor",data.color);
				if(data.color=="#ff0000")
				$('#contestantList'+uid+' td:nth-child(1)').animate({color:"'#FFFFFF'"}, 500);
				else	
				$('#contestantList'+uid+' td:nth-child(1)').animate({color:"'#000000'"}, 500);
				$("#slider"+id).animate( { "background-color" : "#00ff00"}, 500);	
			}
			});		
}

function displayUser(id){
	$("#infoDiv").html($("#contestantList"+id).attr("info"));	
	resetAll();
	
	$("#jform").attr("edit",id);
	data=$.parseJSON($("#contestantList"+id).attr("scores"));
	$.each(data,function(sid, val){$("#slider"+sid).slider("option","value",val);$("#score"+sid).val(val);$("#slider"+sid).animate( { 'background-color' : '#00FF00'}, 500);});
	if($("#contestantList"+id).attr("noshow")==1){
		disableAll();
		$("#noShow").attr("disabled","");
		$("#noShow").attr("value","Contestant here");
	}
			
}