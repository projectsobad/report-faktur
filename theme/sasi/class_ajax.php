<?php

class sasi_ajax{
	public static function _get($args=array()){
		$develop = true;
		$start = self::_get_microtime();

		$check = array_filter($args);
		if(empty($check)){
			$ajax = array(
				'status' => "error",
				'msg'	 => "data not found!!!",
				'func'	 => ''
			);
			$ajax = json_encode($ajax);
			
			return print_r($ajax);
		}

		$_class = $args['class'];
		$_func = $args['func'];
		$data = $args['data'];

		if(!is_callable(array($_class,$_func))){
			$ajax = array(
				'status' => "failed",
				'msg'	 => "request not found!!!",
				'func'	 => 'sobad_'.$_func
			);
			$ajax = json_encode($ajax);
			
			return print_r($ajax);
		}
		
		try{
			$msg = $_class::{$_func}($data);
		}catch(Exception $e){
			return _error::_alert_db($e->getMessage());
		}

		if(empty($msg)){
			$ajax = array(
				'status' => "error",
				'msg'	 => "ada kesalahan pada pemrosesan data!!!",
				'func'	 => 'sobad_'.$_func
			);
			$ajax = json_encode($ajax);
			
			return print_r($ajax);
		}
		
		$finish = self::_set_microtime($start);

		$ajax = array(
			'status' => "success",
			'msg'    => "success",
			'data'	 => $msg,
			'func'	 => 'sobad_'.$_func
		);

		if($develop) $ajax['time'] = $finish;
		
		$ajax = json_encode($ajax);		
		return print_r($ajax);
	}

	public static function _get_microtime(){
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];

		return $time;
	}

	public static function _set_microtime($start=0){
		$finish = self::_get_microtime();
		$time = round(($finish - $start), 4);

		return $time;
	}
}