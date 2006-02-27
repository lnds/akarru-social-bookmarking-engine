<div class="infobox">

    <h3>circulaci&oacute;n en la blogosfera</h3>
    <div class="infoboxBody">
    <p>
    <strong>Esta es la circulaci&oacute;n de los memes mas difundidos por la blogosfera durante las &uacute;ltimas 24 horas.</strong>
    </p>
    </div>
    
<div class="infoboxFooter"><p>&nbsp;</p></div>
</div>
<table width="420" border="0" cellspacing="0" cellpadding="2" >
  <tr>
    <td colspan="2">
    </td>
   </tr>
   <tr>
   <td colspan="2">
	<table>
	<tr><th align="left">t&iacute;tulo</th><th>vistas</th><th>clicks</th><th>circulaci&oacute;n</th></tr>
	{foreach from=$views item=item}
	<tr><td><a href="{$item->url}">{$item->title}</a></td><td align="right">{$item->views}</td><td align="right">{$item->clicks}</td><td align="right">{$item->circulation}</tr>
	{/foreach}
	<tr><td>TOTAL:</td>
	<td align="right"><b>{$total->views}</b></td>
	<td align="right"><b>{$total->clicks}</b></td>
	<td align="right"><b>{$total->circulation}</b></td>
	</tr>
	</table>
   </td>
   </tr>
</table>
<p>
La circulaci&oacute;n es calculada revisando las <a href="http://www.feedburner.com/fb/a/api/awareness">estad&iacute;sticas</a> provistas por <a href="http://www.feedburner.com/">FeedBurner</a>.
</p>
