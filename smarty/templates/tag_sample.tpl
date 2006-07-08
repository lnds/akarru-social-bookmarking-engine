{config_load file="site.conf"}
<h2>
<span style="padding-right: 0.5em;"><a style="font-size:16pt;" href="/show_folksonomy.php">{#folksonomy_caption#}</a></span>
</h2>
<div style="letter-spacing: normal; width: 190px; margin: 10px; margin-top: 0;">{#folksonomy_bar_text#}</div>
<div>
    {foreach name="tags" from=$tags item=tag}
    <a href="/memes_by_tag.php?tag_name={$tag->tag}">{$tag->tag}</a>&nbsp;&nbsp;
    {/foreach}
    <br/>
    <a href="/show_folksonomy.php">{#show_all_folksonomy_label#}</a>
</div>