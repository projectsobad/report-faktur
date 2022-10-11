<?php

class sobad_track extends _class{
	
	public static $table = ''. base .'track-history';

	public static function blueprint(){
		$args = array(
			'type'	=> 'track',
			'table'	=> self::$table,
			'detail'=> array(
				'barang'	=> array(
					'key'		=> 'ID',
					'table'		=> ''. base .'item',
					'column'	=> array('name','product_code','type','var')
				)
			)
		);

		return $args;
	}

	public static function get_first_code($item=0,$year=0){
		$code = self::get_all(array('last_code'),"AND barang='$item' AND YEAR(inserted)='$year'");
		$code = isset($code[0]) ? $code[0]['last_code'] : 0;

		return $code;
	}
}