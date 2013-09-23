 $(document).ready(function(){
	createAjaxLoaders();
 });

function createAjaxLoaders(){
   $("a.rate").click(function(e){     
	e.preventDefault();
	asd="";
	$(this).fadeOut(100, function(){
	
	$.ajax({
	  url: "/ajax/like/"+$(this).attr("like"),
	  cache: false,
	  context:$(this),
	  success: function(htm){		  
		$(this).html(htm);
		$(this).fadeIn();
	  }
	  });
	});

   });	
	ajaxAddHaiku();
	ajaxRemoveHaiku();
	ajaxAddAuthor();ajaxRemoveAuthor();
}
function ajaxAddHaiku(){
   $("a.addHaiku").bind('click', function(e){     
	e.preventDefault();
	$(this).fadeOut(100, function(){	
	$.ajax({
	  url: "/ajax/addHaiku/"+$(this).attr("haiku"),
	  cache: false,
	  context:$(this),
	  success: function(htm){
		  
		$(this).html(htm);
		$(this).removeClass("addHaiku");
		$(this).addClass("removeHaiku");
		$(this).fadeIn();
		$(this).unbind();
		ajaxRemoveHaiku();
	  }
	  });
	});

   });	
}

function ajaxRemoveHaiku(){
   $("a.removeHaiku").bind('click', function(e){     
	e.preventDefault();
	$(this).fadeOut(100, function(){	
	$.ajax({
	  url: "/ajax/removeHaiku/"+$(this).attr("haiku"),
	  cache: false,
	  context:$(this),
	  success: function(htm){
		$(this).html(htm);
		$(this).removeClass("removeHaiku");
		$(this).addClass("addHaiku");
		$(this).fadeIn();
		$(this).unbind();
		ajaxAddHaiku();
	  }
	  });
	});

   });	
}
function ajaxAddAuthor(){
   $("a.addAuthor").bind('click', function(e){     
	e.preventDefault();
	aa="a|[author="+$(this).attr("author")+"]";
	$(aa).fadeTo(100, .01);
	$.ajax({
	  url: "/ajax/addAuthor/"+$(this).attr("author"),
	  cache: false,
	  context:$(this),
	  success: function(htm){
		$(aa).html(htm);
		$(aa).fadeTo(600, 1);
		$(aa).removeClass("addAuthor");
		$(aa).addClass("removeAuthor");	
		$(aa).unbind();
		ajaxRemoveAuthor();
	  }
	  });
   });	
}

function ajaxRemoveAuthor(){
   $("a.removeAuthor").bind('click', function(e){     
	e.preventDefault();
	aa="a|[author="+$(this).attr("author")+"]";
	$(aa).fadeTo(100, .01);
	$.ajax({
	  url: "/ajax/removeAuthor/"+$(this).attr("author"),
	  cache: false,
	  context:$(this),
	  success: function(htm){
		$(aa).html(htm);
		$(aa).fadeTo(600, 1);
		$(aa).removeClass("removeAuthor");
		$(aa).addClass("addAuthor");
		$(aa).unbind();
		ajaxAddAuthor();
	  }
	  });

   });	
}