<?php

class category_retail extends _page{

	protected static $object = 'category_retail';

	protected static $table = 'sobad_meta';

	// ----------------------------------------------------------
	// Layout category  ------------------------------------------
	// ----------------------------------------------------------

	protected static function _array(){
		$args = array(
			'ID',
			'meta_value',
			'meta_note',
			'meta_key'
		);

		return $args;
	}

	protected static function table(){
		$data = category_designer::table();
		foreach ($data['table'] as $key => $val) {
			unset($data['table'][$key]['td']['Edit']);
			unset($data['table'][$key]['td']['Hapus']);
		}
		
		return $data;
	}

	private static function head_title(){
		$args = array(
			'title'	=> 'Kategori <small>data kategori</small>',
			'link'	=> array(
				0	=> array(
					'func'	=> self::$object,
					'label'	=> 'category'
				)
			),
			'date'	=> false
		); 
		
		return $args;
	}

	protected static function get_box(){
		$data = self::table();
		
		$box = array(
			'label'		=> 'Data Kategori',
			'tool'		=> '',
			'action'	=> '',
			'func'		=> 'sobad_table',
			'data'		=> $data
		);

		return $box;
	}

	protected static function layout(){
		$box = self::get_box();
		
		$opt = array(
			'title'		=> self::head_title(),
			'style'		=> array(),
			'script'	=> array('')
		);
		
		return portlet_admin($opt,$box);
	}
}