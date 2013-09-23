{{include file="overall/header.tpl" }}
{{if $user->isValid()}}
<div style="{{if $smarty.const.NOCENTER neq 1}}text-align:center;{{/if}}">
{{$body}}
</div>
{{/if}}
{{include file="overall/footer.tpl"}}
