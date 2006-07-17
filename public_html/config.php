<?php

	
define('DATABASE_USER', '');
define('DATABASE_PASS', "");
define('DATABASE_HOST', "");
define('DATABASE_NAME', "");
define('PROMO_LEVEL', '5');
define('RECORDS_TO_PAGE', '15');
define('DOMAIN', 'blogmemes.com'); // domain for cookies
define('AES_KEY', '');   /// AES site key for password storage


$bm_promo_level = 5;
$bm_main_feeds = '';
$bm_queue_feeds = '';
$bm_url_feeds = '';
$bm_url_queue_feeds = '';
$bm_lang = 'es-es';
$bm_url  = 'http://www.blogmemes.com/';
$bm_domain = 'blogmemes.com';
$bm_web_address = 'www.blogmemes.com';
$bm_admin_email_address = 'admin@blogmemes.com';
$bm_desc = 'Blog Memes es un servicio de promocion colectiva de urls';
$bm_site_name = 'Blog Memes';
$bm_home = 'index.php';

$bm_date_posted_format='Published on %d/%m/%Y at %R';

$bm_ajax_service = '';
$bm_login_url =  '';

// You need to get your own API key for Akismet. The easiest way is to register a wordpress blog at wordpress.com
//  Then follow the instructions at http://faq.wordpress.com/2005/10/19/api-key/
$bm_akismet_api_key = '';

?>
