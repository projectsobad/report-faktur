<?php

class sobad_saldo extends _class{
	
	public static $table = ''. base .'account-saldo';

	public static function blueprint(){
		$args = array(
			'type'	=> 'saldo',
			'table'	=> self::$table
		);

		return $args;
	}

	public static function get_saldo_month($cash=0,$date=''){
		$where = "AND cash_id='$cash' AND date='$date' AND status='0'";
		$args = self::get_all(array('balance'),$where);

		$check = array_filter($args);
		if(empty($check)){
			return 0;
		}

		return $args[0]['balance'];
	}
}