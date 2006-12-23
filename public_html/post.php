<?php
/**
 * @package AkarruCPE
 * @version 0.6
 * @copyright (c) 2006 Eduardo Diaz Cortes
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License 
 * @author Eduardo Diaz <ediaz@lnds.net>
 */

include_once('akarru/common.php');
$user = new User();
if (!$user->is_logged_in()) {
	redirect_to('/login');
	return;
}
$form = 'postform';
$template = new Template('master');
$template->hide_tabstrip();
$template->no_preview();
$template->add_css('blogmemes_forms.css');
if (is_post_back()) 
{
	$req = request_uri();
	if ($req == '/post_verify')
	{
		$errors = 0;
		$template->validate_required_text('title', '', 'debe ingresar el t&iacute;tulo del meme');
		$url = $template->validate_optional_url('url', '', 'la url no es valida');
		if (Meme::check_exists_url(request_value('url', '')))
			$template->add_error('este enlace ya ha sido publicado');
		if (!$template->has_errors()) 
		{
			$template->assign('action', '/post_submit');
			$form = 'postform_validate';
			$cats = array('-1' => '--- elegir categoria ---');
			foreach ($template->cats->categories as $cat)
			{
				$cats[$cat->ID] = $cat->cat_title;
			}
			$template->assign('categories', $cats);
		}
	}
	else if ($req == '/post_submit') 
	{
		$template->validate_required_text('title', '', 'debe ingresar el t&iacute;tulo del meme');
		$url = $template->validate_optional_url('url', '', 'la url no es valida');
		$template->validate_required_text('description', '', 'debe ingresar el t&iacute;tulo del meme');
		$template->validate_select('category', -1, 'debe elegir una categor&iacute;a');
		$template->validate_radio('content_type', array(0,1,2), 'debe elegir un tipo de contenido');
		if (!$template->has_errors()) 
		{
			$meme = Meme::create_meme($_REQUEST);
			redirect_to('/meme/'.$meme->id);
			return;
		}
	}
}
else
{
	$url = request_value('url', 'http://');
	$title = request_value('title', '');
	$template->assign('url', $url);
	$template->assign('title', $title);
	$template->assign('action', '/post_verify');
}
$template->assign('cats', false);
$template->display($form)
?>
