<?php

class sobad_integration extends _class{
	
	public static $table = ''. base .'integration';

	public static function blueprint(){
		$args = array(
			'type'	=> 'integration',
			'table'	=> self::$table
		);

		return $args;
	}

	public static function send_curl($curl='',$data=array()){
		$url = $curl;

		$data = sobad_curl::get_data($curl,$data);
		$data = json_decode($data,true);

		if($data['status']=='error'){
			die(_error::_alert_db($data['msg']));
		}

		return $data['msg'];
	}
}