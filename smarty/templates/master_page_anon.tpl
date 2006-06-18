<head>
<title>{$page_title}</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">@import "/styles/memes.css";</style>
<!--[if gte IE 5.5000]>
<script type="text/javascript" src="/scripts/pngfix.js"></script>
<![endif]-->
<link rel="icon" href="/favicon.ico" type="image/x-icon" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="{#url_feeds#}" />
{include file="java_script.tpl"}
</head>
<body>
	<div id="container">
		<div id="header">
			<h1><a href="{#home_url#}">{#site_caption#}</a></h1>
			<form action="/search.php" method="post" name="search-form" id="search-form">
				<label for="search" accesskey="100" class="inside">{#search_label#}</label>
				<input type="text" name="search" id="search" size="10" value="" />
			</form>
			<ul>
				<li>
					<a href="/help.php">{#help_label#}</a>
				</li>
				<li>
					{if $from}                                                   
					<a href="/login.php?from={$from}" alt="ingresar">{#login_label#}</a>
					{else}
					<a href="/login.php" alt="ingresar">{#login_label#}</a>
					{/if}
				</li>
				<li>
					<a href="/register.php">{#register_label#}</a>
				</li>
				<li>
				<a href="http://www.blogmemes.info/">red</a>
				</li>
				<li>
				<a href="http://trac.blogmemes.com/trac_script/wiki/SpanishWiki">wiki</a>
				</li>
				<li>
				<a href="http://trac.blogmemes.com/trac_script/newticket">bugs</a>
				</li>
				
			</ul>
			<div id="header-banner">
				{literal}
				<script type="text/javascript"><!--
google_ad_client = "pub-7333949631042964";
google_alternate_ad_url = "http://www.citascitables.info/citas728x90.aspx";
google_ad_width = 728;
google_ad_height = 90;
google_ad_format = "728x90_as";
google_ad_type = "text_image";
google_ad_channel ="0939171733";
google_color_border = "FFFFFF";
google_color_bg = "FFFFFF";
google_color_link = "315D84";
google_color_url = "315D84";
google_color_text = "000000";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
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
				{include file="$content.tpl"}
			</div> 
		</div>
		<div id="sidebar">
			<div id="sidebar-tab1"><a href="/memes_queue.php">{#promote_label_url#}</a></div>
			<div id="sidebar-tab2"><a href="/post.php">{#post_label_url#}</a></div>
			<div id="sidebar-tab3"><a href="/popular.php">{#popular_label_url#}</a>
				<br /><a href="/unpopular.php">{#unpopular_label_url#}</a>
			</div>
			<div id="sidebar-cat">
				<h1><span style="padding-right: 1.5em;">{#categories_label#}</span></h1>
				<div style="padding-left:8px">
				{html_table cols="3" loop=$cats_array table_attr='border="0" align="center" width="100%" cellpadding="2" cellspacing="2"'}
				</div>
				
				<div id="folk" style="padding-right: 15px;">
{if $community}				
{else}
				<h2 >{#community_label#}</h2>
				<div style="padding-left:2em">
{html_table loop=$community_sample table_attr='border=0 cellpadding=4 align=center' cols="3"}</div>
{/if}
<a href="/community.php">{#community_link#}</a>
					<h2>
					<span style="padding-right: 0.5em;"><a style="font-size:16pt;" href="/show_folksonomy.php">{#folksonomy_caption#}</a></span>
					</h2>
					<div style="letter-spacing: normal; width: 190px; margin: 10px; margin-top: 0;">{#folksonomy_bar_text#}</div>
					<div>
						{foreach name="tags" from=$tags item=tag}
						<a href="/tag/{$tag->tag}">{$tag->tag}</a>&nbsp;&nbsp;
						{/foreach}
						<br/>
						<a href="/show_folksonomy.php">{#show_all_folksonomy_label#}</a>
					</div>
				</div>
				<div>
					<h2>enlaces destacados  &nbsp;&nbsp;</h2>
				<div>
					<div style="padding-right: 1em;padding-left: 1em">
						participa activamente en blogmemes, con votos y comentarios  y lograr&aacute;s destacar tu sitio o tu blog en este espacio.
					</div>

					<br />
					<a href="http://blog.pinceladas.net/">pinceladas</a><br />
					<a href="http://www.ricardodiaz.org/">kimniekan</a><br />
					<a href="http://www.viejoblues.com/Bitacora/">viejos blues</a><br />
					<a href="http://desinformados.net/blog">desinformados</a><br />
					<a href="http://www.blogalaxia.com/top100.php?top=1"><img src="http://botones.blogalaxia.com/img/blogalaxia0.gif" alt="BloGalaxia" style="border:0" /></a>
					<a href="http://www.blogtopsites.com/internet/"><img border="0" src="http://www.blogtopsites.com/tracker.php?do=in&amp;id=20703" alt="Internet Blog Top Sites" /></a>
					<div id="botones">
					
					</div>
<div style="background:none;background-color:white;padding-left:2em;padding-right:2em">
</div>					

<div style="background:none;background-color:white;padding-left:2em;padding-right:2em;height:600px"> 
{literal}
<script type="text/javascript"><!--
google_ad_client = "pub-7333949631042964";
google_alternate_ad_url = "http://www.citascitables.info/citas160x600.aspx";
google_ad_width = 160;
google_ad_height = 600;
google_ad_format = "160x600_as";
google_ad_type = "text_image";
google_ad_channel ="7131951087";
google_color_border = "FFFFFF";
google_color_bg = "FFFFFF";
google_color_link = "315D84";
google_color_url = "315D84";
google_color_text = "000000";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
{/literal}
</div>
</div>
			</div>
		</div>
		
	</div>      <!-- main wrapper -->
	
	</div>
	<div id="main-end">
	</div>
	<div id="bar-creditos" class="creditos">
		<span class="creditos-texto"><a href="/about.php">{#about_label#}</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="http://trac.blogmemes.com/trac_script/newticket">{#suggestions_label#}</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="mailto:admin@blogmemes.com">contacto</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/promote.php">promu&eacute;venos</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/stats.php">ranking</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="http://trac.blogmemes.com/">usamos akarr&uacute;</a></span>
		<span class="creditos-texto" style="float:right">
		<a href="http://english-63510149780.spampoison.com"><img src="http://pics3.inxhost.com/images/sticker.gif" border="0" width="80" height="15"/></a>
		<a href="http://feeds.feedburner.com/BlogMemes"><img src="http://feeds.feedburner.com/~fc/BlogMemes?bg=315d84&amp;fg=FFFFFF&amp;anim=0" height="26" width="88" style="border:0" alt="" /></a>
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
			<script src="http://spy.foxcorp.org/spy.js" type="text/javascript"></script>
			{/literal}
		</span>
	</div>
</body>
</html>
