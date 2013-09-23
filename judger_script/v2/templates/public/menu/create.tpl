<div id="menu" >
      <h3 style="float:left;margin=0px;">Competitions</h3><br clear=all />
		<ul>
        {{section name=competition loop=$competitions}}
        {{include file="menu/button.tpl" href="/public/?view=`$competitions[competition].id`" title=$competitions[competition].name }}
        {{/section}}
		</ul><br>
		
</div>