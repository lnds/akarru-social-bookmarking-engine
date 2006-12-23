<?php

require('Smarty/Smarty.class.php');

class Template extends Smarty 
{
	var $template;
	var $css_files;
	var $form_errors;
	var $cats;
	var $user;

	function __construct($template)
	{
		$this->css_files = array();
		$this->form_errors = array();
		application_start();
		$this->template = $template;
		parent::__construct();
		$this->template_dir = '/home/ediaz/domains/blogmemes.com/smarty/templates';
		$this->compile_dir = '/home/ediaz/domains/blogmemes.com/smarty/templates_c';
		$this->config_dir = '/home/ediaz/domains/blogmemes.com/smarty/configs';
		$this->cache_dir = '/home/ediaz/domains/blogmemes.com/smarty/cache';
		$this->plugins_dir[] = '/home/ediaz/domains/blogmemes.com/smarty/plugins';
        $this->caching = false;
		$this->compile_check = true;
		$this->assign('show_tabstrip', true);
		$this->assign('preview_links', true);
		$user = new User();
		if ($user->is_logged_in()) 
		{
			$this->user = $user;
			$this->assign('user', new User());
		}
		$cats = get_app_var('cats');
		if (!isset($cats)) 
		{
			$cats =  new CategoryList();
			set_app_var('cats', $cats);
		}
		$this->assign('cats', $cats);
		$this->cats = $cats;
	}


	public function hide_tabstrip()
	{
		$this->assign('show_tabstrip', false);
	}

	public function no_preview()
	{
		$this->assign('preview_links', false);
	}

	public function add_css($css_file)
	{
		$this->css_files[] = $css_file;
	}


	public function add_error($error)
	{
		$this->form_errors[] = $error;
	}

	public function display($content='')
	{
		$this->assign('css_files', $this->css_files);
		if (count($this->form_errors) > 0)
		{
			$this->assign('form_errors', $this->form_errors);
		}
		if (!empty($content)) 
		{
			$this->assign('content', $content);
		}
		parent::display($this->template);
		application_end();
	}

	public function set_selector($page)
	{
		$this->assign('sel_'.$page, 'class="selected"');
	}

	public function add_tab($tab_name)
	{
		$this->assign('new_tab', $tab_name);
	}

	public function set_destination($url='/', $message='continue')
	{
		$this->assign('url', $url);
		$this->assign('destination', $message);
	}

	public function message($msg,$title='')
	{
		$this->assign('title', $title);
		$this->assign('message', $msg);
	}

	public function validate_required_text($var, $def, $error, $filter='')
	{
		$value = request_value($var, $def);
		$this->assign($var, $value);
		if (empty($value))
			$this->add_error($error);
	}

	public function validate_optional_url($url, $def, $error)
	{
		if ($url == 'http://')
		{
			$this->assign('url','');
			return;
		}

		$value = request_value($url, $def);
		$this->assign($url, $value);
		if (empty($value))
			return $value;
		$url = check_url_format($value);
		if ($url != false)
		{
			if (!check_url_exists($url))
				$this->add_error($error);
		}
	}

	public function validate_select($var, $def, $error)
	{
		$value = request_value($var, $def);
		if ($value == $def) 
		{
			$this->add_error($error);
		}
	}

	public function validate_radio($var, $values, $error)
	{
		$value = request_value($var);
		if (!in_array($value, $values))
		{
			$this->add_error($error);
		}
	}

	public function has_errors()
	{
		return count($this->form_errors);
	}
}

?>
