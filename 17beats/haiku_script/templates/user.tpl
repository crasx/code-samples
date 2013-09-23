{{{include file="header.tpl" }}}
<div class="white round"><div class="pad">
<h2>{{{$uinfo.username}}}</h2> 
{{{if $user->isValid() and $uinfo.id neq $user->getInfo('id') }}} 
{{{if $user->hasAuthorFavorited($uinfo.id)}}}
(<a href="" class="removeAuthor" author="{{{$uinfo.id}}}">Remove favorite author</a>)
{{{else}}}
(<a href="" class="addAuthor" author="{{{$uinfo.id}}}">Add  favorite author</a>)
{{{/if}}}{{{/if}}}<br />

</div></div>
<br />

{{{include file="listpoems_user.tpl"}}}
{{{include file="pagination.tpl"}}}
{{{include file="footer.tpl"}}}
