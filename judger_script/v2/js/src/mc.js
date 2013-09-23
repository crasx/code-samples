	
		function updateCalledCheck(id, c){
		$.getJSON(BASE+'/ajax/mc/updateCalled/'+id+'?c='+c, '', function(data){
			if(data.success=='False'){					
				alert("Error updating check! "+data.error);	
			}else{
				$('#contestantList'+id+' td:nth-child(2)').animate({backgroundColor:"'"+data.color+"'"}, 500);
			}
			});								
	}	
	
		function updateLineCheck(id, c){
		$.getJSON(BASE+'/ajax/mc/updateLine/'+id+'?c='+c, '', function(data){
			if(data.success=='False'){					
				alert("Error updating check! "+data.error);	
			}else{
				$('#contestantList'+id+' td:nth-child(1)').animate({backgroundColor:"'"+data.color+"'"}, 500);
			}
			});								
	}	
