
<div id="categories" class="round white center">
    {{{if $category eq -1}}}
        <i>All</i><br />
	{{{else}}}
    	<a href="/" class="category">All</a><br />
    {{{/if}}}
{{{section name=cat loop=$categories}}}

	{{{if $category eq $categories[cat].id}}}
    	<i>{{{$categories[cat].name}}}</i>
    {{{else}}}
    	<a href="/categories/{{{$categories[cat].name}}}" class="category">{{{$categories[cat].name}}}</a>
     {{{/if}}}
     <br />
{{{/section}}}

</div>