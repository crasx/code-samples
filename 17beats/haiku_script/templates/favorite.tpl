{{{include file="header.tpl" }}}
<div class="white round"><div class="pad">
<h2> Favorite haiku of {{{$founduser}}}</h2> 
(<a href="/profile/{{{$founduser}}}">Visit profile</a>)
{{{if $user->isValid() and $foundid neq $user->getInfo('id') }}} 
{{{if $user->hasAuthorFavorited($foundid)}}}
(<a href="" class="removeAuthor" author="{{{$foundid}}}">Remove favorite author</a>)
{{{else}}}
(<a href="" class="addAuthor" author="{{{$foundid}}}">Add favorite author</a>)
{{{/if}}}{{{/if}}}
</div></div><br />

{{{include file="listpoems_favorite.tpl"}}}
{{{include file="pagination.tpl"}}}
{{{include file="footer.tpl"}}}
