<?php

$accounting = self::_getSidemenu('child menu.accounting');

$config = array();
$config['dashboard'] = array(
	'status'	=> 'active',
	'icon'		=> 'icon-home',
	'label'		=> 'Dashboard',
	'func'		=> 'dash_admin',
	'child'		=> null
);

$config['accounting'] = array(
	'status'	=> '',
	'icon'		=> 'fa fa-money',
	'label'		=> 'Accounting',
	'func'		=> '#',
	'child'		=> $accounting
);

$config['about'] = array(
	'status'	=> '',
	'icon'		=> 'fa fa-dashboard',
	'label'		=> 'About',
	'func'		=> '',
	'child'		=> null
);