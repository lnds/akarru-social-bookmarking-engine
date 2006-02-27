<?php

include_once('akarru.lib/html_manager.php');

function form($form, $method = 'post', $action = NULL) {
  if (!$action) {
    $action = request_uri();
  }
  return '<form action="'. check_plain($action) .'" method="'. $method ."\">\n". $form ."\n</form>\n";
} 

function form_hidden($id, $value, $attributes='')
{
	return '<input type="hidden" name="'.$id.'" value="'.$value.'" '.$attributes.' />';
}

function form_input($id, $value, $type, $size='30', $attributes='',  $class='view-input-class')
{
	return '<input type="'.$type.'" name="'.$id.'" size="'.$size.'" value="'.check_plain($value).'" class="'.$class.'" '.$attributes.' />';
}

function form_text($id, $value, $size='30', $attributes='', $class='view-input-class') 
{
	return form_input($id, $value, 'text', $size, $attributes, $class);
}

function form_pass($id, $value, $size='30', $attributes='', $class='view-input-class') 
{
	return form_input($id, $value, 'password', $size, $attributes, $class);
}

function form_button($id, $value, $attributes='', $class='view-button-class')  
{
	return form_input($id, $value, 'submit', $attributes, $class);
}


function input_cell($label, $input, $error='', $required=1, $label_class='view-label-class', $error_class='error')
{
	if ($required) {
		$required = '<b class="view-required-class">*</b> ';
	}
	else
	{
		$required = '  ';
	}
	return '<tr><td class="'.$label_class.'">'.$required.
		$label.'</td></tr><tr><td>'.$input.'</td></tr><tr><td class="'.$error_class.'">'.$error.'</td></tr>';
}

function input_cell_text($label, $id, $error='', $size=30,  $required=1)
{
	global $_POST;
	return input_cell($label, form_text($id, $_POST[$id], $size), $error, $required);
}

function input_cell_pass($label, $id, $error='', $size=30, $required=1)
{
	global $_POST;
	return input_cell($label, form_pass($id, $_POST[$id], $size), $error, $required);
}


function input_cell_submit($label, $data)
{
	return '<tr><td>'.$data.form_button($label, $label).'</td></tr>';
}

function input_table($attributes, $data, $error='', $error_class='error')
{
	return '<table border="0" cellspacing="0" cellpadding="2" '.$attributes.' >'.
		'<tr><td class="'.$error_class.'">'.$error.'</td></tr>'.$data.'</table>';
}

?>
