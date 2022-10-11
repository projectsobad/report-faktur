<?php

class sobad_module extends _class{
	
	public static $table = ''. base .'module';

	public static function blueprint(){
		$args = array(
			'type'	=> 'module',
			'table'	=> self::$table
		);

		return $args;
	}
}