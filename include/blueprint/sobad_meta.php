<?php

class sobad_meta extends _class{
	public static $table = ''. base .'meta';

	public static $tbl_meta = ''. base .'item-meta';

	public static function blueprint(){
		$args = array(
			'type'		=> 'meta',
			'table'		=> self::$table
		);

		return $args;
	}

	private static function _check_type($type=''){
		if(!empty($type)){
			$args = array(
				'brand',
				'category',
				'channel',
				'promotion',
				'kebutuhan',
				'type_product',
				'material_part',
				'setting',
				'marquee',
				'retail'
			);

			if(in_array($type, $args)){
				return true;
			}
		}

		return false;
	}
	
	public static function _gets($type='',$args=array(),$limit=''){
		if(self::_check_type($type)){
			$where = "WHERE meta_key='$type' $limit";
			return self::_check_join($where,$args,$type);
		}

		return array();
	}

	public static function _get_retail($id=0){
		$meta = self::_gets('retail',array('ID','meta_value','meta_note','meta_reff'),"AND meta_reff='$id'");
		return $meta;
	}
}