{{{if sizeof($poems) eq 0}}}

<div class="white round full">
<div class="pad">
<h3>No haiku in here<br />
User need to add favorites <br />
Need to spread some love  &hearts;</h3>
</div>
</div><br />

{{{/if}}}

{{{section name=poem loop=$poems}}}
<div class="white round full">
<div class="pad">
	{{{if $poems[poem].title neq ""}}}
    	<h4>{{{$poems[poem].title}}}</h4>
        {{{else}}}
        <br />
    {{{/if}}}
    <div class="full center">
    
{{{$poems[poem].haiku|nl2br}}}</div><br />

	<div class="pfoot">
	    <div class="pinfo">
    		On {{{$poems[poem].approved|date_format:'%b %d %Y at %I:%M %p'}}} by 
            {{{if $poems[poem].anonymous}}}<i>Anonymous</i>{{{else}}}<a href="/users/{{{$poems[poem].username|escape}}}">{{{$poems[poem].username}}}</a>{{{/if}}} in <a href="/categories/{{{$poems[poem].category|escape}}}">{{{$poems[poem].category}}}</a>
          </div>
	 <a href="/poems/{{{$poems[poem].id}}}">{{{$poems[poem].comments}}} comment{{{if $poems[poem].comments neq 1}}}s{{{/if}}}</a> | <a class="rate" href="#" like="{{{$poems[poem].id}}}">{{{$poems[poem].likes}}} like{{{if $poems[poem].likes neq 1}}}s{{{/if}}}</a>
        
          {{{if $user->isValid() and $poems[poem].uid neq $user->getInfo('id') }}} 
          
 | 
 {{{if $user->hasAuthorFavorited($poems[poem].uid)}}}
<a href="#" class="removeAuthor" author="{{{$poems[poem].uid}}}">Remove favorite author</a>
{{{else}}}
<a href="#" class="addAuthor" author="{{{$poems[poem].uid}}}">Add favorite author</a>
{{{/if}}} | 
 {{{if $user->hasHaikuFavorited($poems[poem].id)}}}
<a href="#" class="removeHaiku" haiku="{{{$poems[poem].id}}}">Remove favorite haiku</a>
{{{else}}}
<a href="#" class="addHaiku" haiku="{{{$poems[poem].id}}}">Add favorite haiku</a>
{{{/if}}}
{{{/if}}}
<br>
    </div>
</div>
</div> <br />

{{{/section}}}