<h3>Estad&iacute;sticas</h3>
<p>

<table border="0" cellspacing="0" cell padding="0">
<tr><td><b>usuarios inscritos:</b></td><td align="right">{$users}</td></tr>
<tr><td><b>memes:</b></td><td align="right">{$memes}</td></tr>
</table>

<h3>estos son los usuarios mas destacados</h3>
<table border="0" cellspacing="2"cellpadding="2">
<tr>
<td align="right">#</td>
<td colspan="2">usuario</td>
<td>memes</td>
<td>votos</td>
<td>coments.</td>
<td><a href="stats.php?sort=infl">influencia</a></td>
<td><a href="stats.php?sort=pop">popularidad</a></td>
</tr>
{foreach name=posters  from=$posters item=poster}
<tr><td>{$smarty.foreach.posters.index+1}</td>
    <td><img border="0" src="{$poster->small_gravatar}" /></td>
    <td><a href="profile.php?user_name={$poster->username}">{$poster->username}</a></td>
    <td align="right">{$poster->memes}</td>
    <td align="right">{$poster->votes}</td>
    <td align="right">{$poster->comments}</td>
    <td align="right">{$poster->influence|string_format:"%4.2f"}</td>
    <td align="right">{$poster->popularity|string_format:"%4.2f"}</td>
    </tr>
{/foreach}
</table>

<a id="help"></a>
<p>La <b>influencia</b> mide la manera en que un usuario logra que un meme ascienda en la cola de promoción.
Esto quiere decir, que un usuario con un valor de influencia m&aacute;s alto ha logrado que m&aacute;s 
memes se destaquen en primera p&aacute;gina.</p>
<p>La <b>popularidad</b> es una funcion de los votos recibidos por los memes publicados por el usuario</p>

<p>Para mejorar tu influencia te sugerimos votar y hacer comentarios. Para mejorar tu popularidad publica
memes interesantes que reciban mucha votacion y comentarios</p>


