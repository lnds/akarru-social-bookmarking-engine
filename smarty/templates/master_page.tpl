<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{#site_caption#}</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css" media="all">@import "styles/memes.css";</style>
<!--[if gte IE 5.5000]>
<script type="text/javascript" src="scripts/pngfix.js"></script>
<![endif]-->
{config_load file="site.conf"}
<link rel="icon" href="/favicon.ico" type="image/x-icon" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="{#url_feeds#}" />
{include file="java_script.tpl"}
</head>
<body>
	<div id="container">
		<div id="header">
			<h1><a href="{#home_url#}">{#site_caption#}</a></h1>
			<form action="search.php" method="post" name="search-form" id="search-form">
				<label for="search" accesskey="100" class="inside">{#search_label#}</label>
				<input type="text" name="search" id="search" size="10" value="" />
			</form>
			<ul>
				<li>
					<a href="help.php">{#help_label#}</a>
				</li>
				<li>
					{if $logged_in}
					<a href="profile.php?user_name={$logged_username}">{$logged_username}</a>
					{else}
					{if $from}                                                   
					<a href="login.php?from={$from}" alt="ingresar">{#login_label#}</a>
					{else}
					<a href="login.php" alt="ingresar">{#login_label#}</a>
					{/if}
					{/if}
				</li>
				<li>
					{if $logged_in}
					<a href="logout.php">{#logoff_label#}</a>
					{else}
					<a href="register.php">{#register_label#}</a>
					{/if}
				</li>
				<li>
				<a href="stats.php">ranking</a>
				</li>
				<li>
				<a href="portugues/index.php" alt="portugues">PT</a>
				</li>
			</ul>
			<div id="header-banner">
				{literal}
				{/literal}
			</div>
		</div>   <!-- end header --> 
	</div> <!-- end container -->
	<div id="main-wrapper">
		<h1>
		<span style="padding-top: 1.5em;">
		{if $content_feed_link}
		{$content_feed_link}&nbsp;
		{/if}
		{$content_title}&nbsp;&nbsp;&nbsp;&nbsp;</span>
		</h1>
		<div id="top-bg"></div>
		<div id="content-wrapper">
			<div id="meme">
				{if $logged_in}
				<p>
				{#login_welcome_message#}</p>
				{/if}
				{include file="$content.tpl"}
			</div> 
		</div>
		<div id="sidebar">
			<div id="sidebar-tab1"><a href="memes_queue.php">{#promote_label_url#}</a></div>
			<div id="sidebar-tab2"><a href="post.php">{#post_label_url#}</a></div>
			<div id="sidebar-tab3"><a href="popular.php">{#popular_label_url#}</a>
				<br /><a href="unpopular.php">{#unpopular_label_url#}</a>
			</div>
			<div id="sidebar-cat">
				<h1><span style="padding-right: 1.5em;">{#categories_label#}</span></h1>
				<div style="padding-left:8px">
				{html_table loop=$cats_array table_attr='border="0" align="center" width="100%" cellpadding="2" cellspacing="2"'}
				</div>
				<div id="folk" style="padding-right: 15px;">
					<h2>
					<span style="padding-right: 0.5em;"><a style="font-size:16pt;" href="show_folksonomy.php">{#folksonomy_caption#}</a></span>
					</h2>
					<div style="letter-spacing: normal; width: 190px; margin: 10px; margin-top: 0;">{#folksonomy_bar_text#}</div>
					<div>
						{foreach name="tags" from=$tags item=tag}
						<a href="memes_by_tag.php?tag_id={$tag->tag_id}">{$tag->tag}</a>&nbsp;&nbsp;
						{/foreach}
						<br/>
						<a href="show_folksonomy.php">{#show_all_folksonomy_label#}</a>
					</div>
				</div>
				<div>
					<h2>enlaces destacados  &nbsp;&nbsp;</h2>
				<div>
					<div style="padding-right: 1em;padding-left: 1em">
						participa activamente en blogmemes, con votos y comentarios  y lograr&aacute;s destacar tu sitio o tu blog en este espacio.
					</div>

					<br />
					<div id="botones">
					
					</div>
<div style="background:none;background-color:white;padding-left:2em;padding-right:2em">
</div>					

{if $show_ads }
{literal}
{/literal}
</div>
{/if}
</div>
			</div>
		</div>
		
	</div>      <!-- main wrapper -->
	
	</div>
	<div id="main-end">
	</div>
	<div id="bar-creditos" class="creditos">
		<span class="creditos-texto"><a href="about.php">{#about_label#}</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="http://www.lnds.net/2005/12/sugerencias_para_akarru.html#comments">{#suggestions_label#}</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="mailto:admin@blogmemes.com">contacto</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="promote.php">promu&eacute;venos</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="stats.php">ranking</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="http://sourceforge.net/projects/akarru">usamos akarr&uacute;</a></span>
		<span class="creditos-texto" style="float:right">
			{literal}    
			<!--WEBBOT bot="HTMLMarkup" startspan ALT="Site Meter" -->
			<script type="text/javascript" language="JavaScript">var site="s18BlogMemes"</script>
			<script type="text/javascript" language="JavaScript1.2" src="http://s18.sitemeter.com/js/counter.js?site=s18BlogMemes">
			</script>
			<!--- 
			<noscript>
			<a href="http://s18.sitemeter.com/stats.asp?site=s18BlogMemes" target="_top">
			<img src="http://s18.sitemeter.com/meter.asp?site=s18BlogMemes" alt="Site Meter" border="0"/></a>
			</noscript>
			-->
			<!-- Copyright (c)2005 Site Meter -->
			<!--WEBBOT bot="HTMLMarkup" Endspan -->
			{/literal}
		</span>
		<span class="creditos-texto" style="float:right"> 
		<a href="http://feeds.feedburner.com/BlogMemes"><img src="http://feeds.feedburner.com/~fc/BlogMemes?bg=315d84&amp;fg=FFFFFF&amp;anim=0" height="26" width="88" style="border:0" alt="" /></a>
		</span>
		<span class="creditos-texto" style="float:right">
		<a href="http://www.bitacoras.com/top500/"><img src="http://www.bitacoras.com/top500/top.php?url=http://www.blogmemes.com/" border="0" title="Top500 de Bitacoras.com" /></a>
		
		</span>
	</div>
</body>
</html>
