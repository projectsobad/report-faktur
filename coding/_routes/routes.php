<?php

(!defined('DEFPATH'))?exit:'';

// registry page
$args = array();
$args['login'] = array(
	'home'	=> true,
	'view'	=> 'Login.login',
	'page'	=> 'login_system'
);

$args['accounting'] = array(
	'page'		=> 'accounting_sasi',
	'home'		=> false,
);

reg_hook('reg_page',$args);