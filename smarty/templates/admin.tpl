<h3>statistics</h3>
<ul>
<li>users:    {$users}</li>
<li>clicks:   {$clicks}</li>
<li>memes:    {$memes}</li>
<li>votes:    {$votes}</li>
<li>comments: {$comments}</li>
</ul>

<h3>top posters</h3>
<table border="0" cellspacing="0"cellpadding="0">
<tr><td>user</td><td># posts</td></tr>
{foreach from=$posters item=poster}
<tr><td><a href="/user/{$poster->username}">{$poster->username}</a></td><td align="right">{$poster->memes}</td></tr>
{/foreach}
</table>
