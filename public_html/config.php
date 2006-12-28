<?php

define('DATABASE_USER', '');
define('DATABASE_PASS', "");
define('DATABASE_HOST', "localhost");
define('DATABASE_NAME', "");
define('PROMO_LEVEL', '5');
define('RECORDS_TO_PAGE', '10');
define('DOMAIN', 'blogmemes.com'); // domain for cookies
define('AES_KEY', '');   /// AES site key for password storage
define('VIDEO', '2');

define('RSS_FEEDS', '');
define('SITE_CAPTION', 'BlogMemes');

$bm_promo_level = 5;



$bm_main_feeds = '';
$bm_queue_feeds = '';
$bm_url_feeds = '';
$bm_lang = 'es-es';
$bm_url  = 'http://www.blogmemes.com/';
$bm_desc = 'Blog Memes es un servicio de promocion colectiva de urls';
$bm_site_name = 'Blog Memes';
$bm_home = 'index.php';
$bm_ajax_service = 'http://www.blogmemes.com/ajax_vote.php';
$bm_login_url =  'http://www.blogmemes.com/login.php'; 

$bf_recover_pass = "Le informamos que usted o alguien a su nombre a solicitado la recuperacion de clave.
Su identificacion de usuario es: %s
Su nueva clave es: %s
Visite esta direcci&oacute;: %s para ingresar con su nueva clave
Usted puede cambiar su clave modificando su perfil
";

$bm_akismet_api_key = '';
?>
