<?php

// define locales in files 

$bm_locale = $_GET['locale'];
if (empty($bm_locale)) {
	$bm_locale = 'es';
}
include_once('akarru.gui/locale.'.$bm_locale);

?>
