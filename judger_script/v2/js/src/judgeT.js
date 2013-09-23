	$(function() {setDragDrop();});
	function setDragDrop(){
		var contestantT=$("#contestantList th:first").width();
		var contestantTH=$("#contestantList th:first").height();
		
		$( ".draggableContestant" ).add(".draggableContestant",criteriaSortedTable.fnGetNodes()).draggable({
			"appendTo": "body",	
			helper: "clone",
			revert: "true",
			cursorAt:{left:contestantT/2},

		}).attr("onselectstart", "return false");
		
		$( ".draggableTContestant" ).draggable({
			"appendTo": "body",	
			helper: "clone",
			revert: "false",
			cursorAt:{left:contestantT/2},
			'stop': function(event, ui) {
				var mRE=$(ui.helper).offset().left+$(ui.helper).width();
				var mBE=$(ui.helper).offset().top+$(ui.helper).height();
				
				var sRE=$(this).offset().left+$(this).width();
				var sBE=$(this).offset().top+$(this).height();
				
					if($(this).attr("dropped")=="1"){
						$(this).attr("dropped","0");
						return;
					} 
				
				if(sRE<$(ui.helper).offset().left || $(this).offset().left>mRE||mBE<$(this).offset().top ||sBE<$(ui.helper).offset().top){
					var parentTR=$(this).closest("tr");
					var parentul=$(this).closest("ul");
					var parenttd=$(this).closest("td");
					var filtered=$(criteriaSortedTable.fnGetNodes()).filter("#contestantList"+parentul.attr("pid"));
					criteriaSortedTable.fnUpdate("<ul class=draggableContestant ><li>"+$("li",ui.helper).html()+"</li></ul>", $(filtered)[0],0);
					
					$("input", parentTR).val("-1");
					parenttd.html('<ul class="topContestant"><li><i>none</i></li></ul>');
					updateScore();				
					setDragDrop();
				}
				
			},
			
		}).attr("onselectstart", "return false");
		

		$( ".topContestant" ).droppable({
			accept:".draggableContestant, .draggableTContestant",
			drop: function( event, ui ) {
				
				var rt=$(this).closest("td");
				var uiParentUL=ui.draggable.closest("ul");
				var parent=ui.draggable.closest("tr");
				var uiTD=ui.draggable.closest("td");
				var thisParent=rt.closest("tr");				
				var theInput=$("input", thisParent);
				var theInputVal=theInput.val();
				
				
				if(uiParentUL.hasClass("draggableTContestant")){
					var oldHTM=rt.html();
					rt.html( uiTD.html() );
					uiTD.html(oldHTM);
					var oldVal=theInput.val();
					theInput.val($("input", parent).val());					
					$("input", parent).val(oldVal);
					uiParentUL.attr("dropped", "1");					
					ui.helper.remove();
					updateScore();
					setDragDrop();
					return;
				}
				
				if(theInputVal!=-1){					
				var filtered=$(criteriaSortedTable.fnGetNodes()).filter("#contestantList"+theInputVal);
				criteriaSortedTable.fnUpdate("<ul class=draggableContestant ><li>"+$("li",this).html()+"</li></ul>", $(filtered)[0],0);
				theInputVal=-1;
				}
				var filtered=$(criteriaSortedTable.fnGetNodes()).filter("#"+parent.attr('id'));
				criteriaSortedTable.fnUpdate("<ul class='removeContestant draggableContestant' ><li>"+$("li",filtered).html()+"</li></ul>", $(filtered)[0],0);
				ui.helper.remove();
				rt.html( "<ul class='draggableTContestant topContestant' onclick='displayUser("+parent.attr("pid")+");return false;' pid="+parent.attr("pid")+" >"+ui.draggable.html()+"</ul>" );
				theInput.val(parent.attr("pid"));
				$(rt).draggable("destroy");
				updateScore();				
				setDragDrop();
			}			
		});
	}
	
	function updateScore(){
	sData=$("#jform").serialize();
	$.getJSON($("#jform").attr("url")+"?"+sData, '', function(data){
			if(data.success=='False'){					
				alert("Error updating! "+data.error);	
			}else{
				var a_p = "";
				var d = new Date();
				
				var curr_hour = d.getHours();
				
				if (curr_hour < 12){
					a_p = "AM";
				}
				else{
					a_p = "PM";
				}
				if (curr_hour == 0){
					curr_hour = 12;
				}
				if (curr_hour > 12){
					curr_hour = curr_hour - 12;
				}			
				var curr_min = d.getMinutes();
				if (curr_min.length == 1){
			   curr_min = "0" + curr_min;
			   }

				$("#lastUpdate").html("Last update: "+curr_hour + ":" + curr_min + " " + a_p);
			}

		});		
}
