<?php
/**
 * @package AkarruCPE
 * @version 0.6
 * @copyright (c) 2006 Eduardo Diaz Cortes
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License 
 * @author Eduardo Diaz <ediaz@lnds.net>
 */
require_once('akarru/common.php');
$template = new Template('master');
$template->hide_tabstrip();
$template->no_preview();
$template->add_css('blogmemes_forms.css');
if (is_post_back()) 
{
	$req = request_uri();
	if ($req == '/do_login')
	{
		$user = User::login(request_value('username'), request_value('pass'), request_value('remember', 0));
		if ($user->ID)
		{
			redirect_to('/');
			return;
		}
		else
			$template->add_error('usuario no existe o la clave es incorrecta');
	}
	else if ($req == '/do_recover') 
	{
		$email = request_value('email');
		if (empty($email)) {
			$template->add_error('debe ingresar email');
		}
		else
		{
			User::gen_password($email, '[blogmemes] recuperar clave', $bf_recover_pass, 'http://www.blogmemes.com/logon');
			$template->message('Si su email es correcto recibir&aacute; un email con su clave a la brevedad', 'Atenci&oacute;n');
			$template->set_destination('/', 'continuar');
			$template->display('result');
			return;
		}
	}
}
$template->assign('cats', false);
$template->display('loginform');


?>
