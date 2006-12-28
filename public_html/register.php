<?php
require('akarru/common.php');
$template = new Template('master');
$template->hide_tabstrip();
$template->no_preview();
$template->add_css('blogmemes_forms.css');
if (is_post_back()) 
{
	$req = request_uri();
	if ($req == '/do_register') {
		$email = request_value('email', '');
		if (empty($email) || !is_valid_email($email)) 
			$template->add_error('debe ingresar una direcci&oacute;n correcta de email');
		else if (User::check_email_exists($email)) 
			$template->add_error('Este email ya tiene una cuenta registrada intente con otro email, o recuperando la clave');

		$username = request_value('username','');
		if (empty($username)) 
			$template->add_error('debe ingresar un nombre de usuario');
		else if (User::check_user_exists($username)) {
			$template->add_error('El usuario: '.$username.' ya ha sido registrado, intente con otro nombre');
		$pass = request_value('pass', '');
		$pass2 = request_value('pass2', '');
		if (empty($pass)) 
			$template->add_error('Debe ingresar la clave');
		else if ($pass != $pass2) 
			$template->add_error('Las claves deben coincidir');
		if ($template->has_errors() == 0) 
		{
			if (User::create_user($username, $email, $pass))
			{
				$template->message('Bienvenido a Blogmemes, su usuario ha sido dado de alta en el sistema');
				$template->set_destination('/', 'continuar');
				$template->display('result');
				return;
			}
			else
				$template->add_error('No fue posible crear el usuario');
		}
	}
}
$template->assign('cats', false);
$template->display('registerform');
?>

