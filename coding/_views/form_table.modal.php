<?php

$portlet = array(
	'ID'		=> $ID,				// ID tag HTML
	'label'		=> $label,			// label portlet
	'tool'		=> '',
	'action'	=> $action,			// Tombol action
	'func'		=> 'sobad_table',
	'data'		=> $table 			// Config table
);

$config = array(
	'title'		=> $title,
	'button'	=> '_btn_modal_save',
	'status'	=> array(
		'link'		=> $link,
		'load'		=> isset($load)?$load:'sobad_portlet'
	),
	'func'		=> array('sobad_form','_portlet'),
	'data'		=> array($data,$portlet)
);