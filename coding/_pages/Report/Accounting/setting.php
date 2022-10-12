<?php

function pdf_setting_account_cashflow($args=array()){
	extract($args);

	$setting = array(
		'data'			=> $data,
		'style'			=> array('style_css'),
		'footer'		=> '',
		'data_footer'	=> '',
		'html'			=> $html,
		'object'		=> $object,
		'setting'		=> array(
			'posisi'		=> 'L',
			'layout'		=> 'A4-L',
		),
		'name save'		=> $title,
		'margin_top'	=> 15,
		'margin_bottom'	=> 30,
		'margin_left'	=> 17,
		'margin_right'	=> 17,
	);

	return $setting;
}

function pdf_setting_account_report($args=array()){
	extract($args);

	$setting = array(
		'data'		=> $data,
		'style'		=> array('style_css'),
		'footer'	=> 'footer',
		'html'		=> $html,
		'object'	=> $object,
		'setting'	=> array(
			'posisi'	=> 'L',
			'layout'	=> 'A4-L',
		),
		'name save'		=> $title,
		'margin_top'	=> 15,
		'margin_bottom'	=> 30,
		'margin_left'	=> 17,
		'margin_right'	=> 17,
	);

	return $setting;
}