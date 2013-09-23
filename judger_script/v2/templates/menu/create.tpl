<ul class="menu">

{{if $user->isValid()}}

	{{if $user->isAdmin()}}
    
	<li class="leaf first active-trail"><a href="{{$smarty.const.BASE}}/admin/menu">Admin</a>
    <ul class="menu">
    	<li><a href='{{$smarty.const.BASE}}/admin/competitions'>Edit competitions</a></li>
		<li><a href='{{$smarty.const.BASE}}/admin/criteria'>Edit criteria</a></li>
		<li><a href='{{$smarty.const.BASE}}/admin/custom'>Edit custom fields</a></li>
		<li><a href='{{$smarty.const.BASE}}/admin/groups'>Edit groups</a></li>
		<li><a href='{{$smarty.const.BASE}}/admin/users'>Edit users</a></li>
		<li><a href='{{$smarty.const.BASE}}/admin/visibility'>Edit visibility</a></li>
    </ul>
        </li>
        
      {{/if}}
      {{if $user->isRegister()}}
     <li class="leaf"><a href="{{$smarty.const.BASE}}/register/contestants">Register</a></li>
      {{/if}}
      {{if $user->isMC()}}
     <li class="leaf expanded">
     <a href="{{$smarty.const.BASE}}/mc/menu">MC</a>
         <ul class="menu">
       <li><a href="{{$smarty.const.BASE}}/mc/competitions">Competitions</a></li>
       <li><a href="{{$smarty.const.BASE}}/mc/results">Results</a></li>
       <li><a href="{{$smarty.const.BASE}}/mc/report">Report</a></li>
       </ul>
	</li>        
      {{/if}}
      {{if $user->isJudge()}}
      
     <li class="leaf expanded">
     <a href="{{$smarty.const.BASE}}/judge/menu">Judging</a>
         <ul class="menu">
                 {{section name=competition loop=$competitions}}
	       <li><a href="{{$smarty.const.BASE}}/judge/{{$competitions[competition].id}}">{{$competitions[competition].name }}</a></li>
        {{/section}}
        </ul>
     </li>
		{{/if}}

{{else}}

<li class="leaf last"><a href="/">Login</a></li>
{{/if}}

</ul>
