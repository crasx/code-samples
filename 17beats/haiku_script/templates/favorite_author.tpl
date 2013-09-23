{{{include file="header.tpl" }}}

<div class="white round"><div class="pad">
<h2> Favorite authors of {{{$founduser}}}</h2> 
(<a href="/profile/{{{$founduser}}}">Visit profile</a>)
{{{if $user->isValid() and $foundid neq $user->getInfo('id') }}} 
{{{if $user->hasAuthorFavorited($foundid)}}}
(<a href="" class="removeAuthor" author="{{{$foundid}}}">Remove favorite author</a>)
{{{else}}}
(<a href="" class="addAuthor" author="{{{$foundid}}}">Add favorite author</a>)
{{{/if}}}{{{/if}}}
</div></div><br />

<div class="white round full">
<div class="pad">
{{{section name=author loop=$authors}}}
	<a href="/users/{{{$authors[author].author}}}">{{{$authors[author].author}}}</a> ({{{if $user->isValid() and $user->hasAuthorFavorited($authors[author].uid)}}}<a href="#" class="removeAuthor" author="{{{$authors[author].uid}}}">Remove favorite author</a>{{{else}}}<a href="#" class="addAuthor" author="{{{$authors[author].uid}}}">Add favorite author</a>{{{/if}}})<br />

  
{{{/section}}}    
{{{if sizeof($poems) eq 0}}}

<h3>No authors in here<br />
User need to add favorites <br />
Need to spread some love  &hearts;</h3>

{{{/if}}}

 </div></div>   
 <br />

{{{include file="listpoems_favorite.tpl"}}}
{{{include file="pagination.tpl"}}}
{{{include file="footer.tpl"}}}
