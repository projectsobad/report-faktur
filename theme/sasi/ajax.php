<?php

if(!isset($_POST['ajax'])){
	include '../../include/err.php';

	$err = new _error();
	$err = $err->_alert_db("ajax not load");
	die($err);
}

// definisi path theme
if(!defined('THEMEPATH')){
	define('THEMEPATH',dirname(__FILE__));
}

include '../../include/config/defined.php';

require THEMEPATH.'/class_ajax.php';
require THEMEPATH.'/template.php';
require THEMEPATH.'/view.php';

$key = $_POST['object'];
$key = str_replace("sobad_","",$key);
$func = str_replace("sobad_","",$_POST['ajax']);

$value = isset($_POST['data']) ? $_POST['data'] : "";

$data['class'] = $key;
$data['func'] = $func;
$data['data'] = $value;

if(!class_exists($key)){
	$ajax = array(
		'status' => "failed",
		'msg'	 => "object not found!!!",
		'func'	 => 'sobad_'.$key
	);
	$ajax = json_encode($ajax);
		
	return print_r($ajax);
}

define('_object',$key);
sasi_ajax::_get($data);