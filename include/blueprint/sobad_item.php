<?php

class sobad_item extends _class{	

	public static $table = ''. base .'item';

	public static $tbl_join = ''. base .'item-join';

	protected static $join = "joined.item_id ";

	public static $tbl_meta = ''. base .'item-meta';

	protected static $group = " GROUP BY `". base ."item-meta`.meta_id";

	protected static $list_meta = '';

	public static function set_listmeta(){
		$type = parent::$_type;

		switch ($type) {
			case 'part':
				self::$list_meta = array(
					'_warning_stock',
					'_note',
					'_sync_item',
					'_sync_status',
					'_finishing',
					'_unit',
					'_image',
					'_material'
				);
				break;

			case 'stdPart':
				self::$list_meta = array(
					'_warning_stock',
					'_note',
					'_sync_item',
					'_unit'
				);
				break;

			case 'assembly':
				self::$list_meta = array(
					'_warning_stock',
					'_note',
					'_detail',
					'_unit',
					'_image'
				);
				break;
			
			default:
				self::$list_meta = array(
					'_warning_stock',
					'_shape',
					'_dimension',
					'_note',
					'_detail',
					'_unit'
				);
				break;
		}
	}

	public static function blueprint($key='item'){
		self::set_listmeta();

		$args = array(
			'type'		=> $key,
			'table'		=> self::$table,
			'detail'	=> array(
				'type'	=> array(
					'key'		=> 'ID',
					'table'		=> base .'meta',
					'column'	=> array('meta_value','meta_note')
				),
			),
			'meta'		=> array(
				'key'		=> 'meta_id',
				'table'		=> self::$tbl_meta,
			),
			'joined'	=> array(
				'key'	=> 'item_id',
				'table'	=> self::$tbl_join 
			)
		);

		if($key=='assembly'){
			$args['joined'] = array(
				'key'		=> 'item_id',
				'table'		=> self::$tbl_join,
				'detail'	=> array(
					'join_id'	=> array(
						'key'		=> 'ID',
						'table'		=> ''. base .'item',
						'column'	=> array('name','part_id','product_code','picture','price','stock','var')
					)
				)
			);
		}else if($key=='join'){
			$args['detail'] = array(
				'join_id'	=> array(
					'key'		=> 'ID',
					'table'		=> ''. base .'item',
					'column'	=> array('name','part_id','product_code','picture','price','stock','var')
				)
			);

			unset($args['joined']);
		}

		return $args;
	}

// -----------------------------------------------------------------
// --- Function Item -----------------------------------------------
// -----------------------------------------------------------------

	public static function _conv_type($var=0,$type=0){
		$args = array(
			1 => 'Part',
			'Std. Part',
			'Assembly',
			'Product',
			'Packet'
		);

		if($type==1){
			$args[1] = 'Common Part';
			$args[3] = 'Common Assy';
		}

		return isset($args[$var])?$args[$var]:'';
	}		

	public static function get_max($column='ID',$limit=''){
		$args = array("MAX($column) as $column");
		$where = "WHERE 1=1 $limit";
		return parent::_get_data($where,$args);
	}
	
	public static function get_parts($args=array(),$limit=''){
		
		$where = "WHERE `". base ."item`.var='1' ".$limit;
		return self::_check_join($where,$args,'part');
	}

	public static function get_stdParts($args=array(),$limit=''){
		
		$where = "WHERE `". base ."item`.var='2' ".$limit;
		return self::_check_join($where,$args,'stdPart');
	}	

	public static function get_assemblies($args=array(),$limit=''){
		
		$where = "WHERE `". base ."item`.var='3' ".$limit;
		return self::_check_join($where,$args,'assembly');
	}

	public static function get_products($args=array(),$limit=''){
		
		$where = "WHERE `". base ."item`.var='4' ".$limit;
		return self::_check_join($where,$args);
	}

	public static function get_packets($args=array(),$limit=''){
		
		$where = "WHERE `". base ."item`.var='5' ".$limit;
		return self::_check_join($where,$args);
	}

	public static function get_image($data='',$limit=''){
		$imgs = array();
		$data = json_decode($data,true);

		if(isset($data['image'])){
			$data = implode(',', $data['image']);
			$data = empty($data)?0:$data;

			$post = sobad_post::get_all(array('notes'),"AND ID IN ($data)");
			$check = array_filter($post);

			if(!empty($check)){
				foreach ($post as $key => $val) {
					$imgs[] = $val['notes'];
				}

				return $imgs;
			}
		}

		return array('no-image.png');
	}

	public static function get_assets($args=array(),$limit=''){

		$where = "WHERE `". base ."item`.type='0' AND var='1'".$limit;
		return self::_check_join($where,$args);
	}

	public static function get_machines($args=array(),$limit=''){

		$where = "WHERE `". base ."item`.type='0' AND var='8'".$limit;
		return self::_check_join($where,$args);
	}

// -----------------------------------------------------------------
// --- Function Item Detail ----------------------------------------
// -----------------------------------------------------------------	

	public static function get_join($id=0,$args=array(),$limit=''){	
		self::$table = ''. base .'item-join';	
		$where = "WHERE `". base ."item-join`.ID='$id' $limit";
		$data = parent::_check_join($where,$args,'join');

		self::$table = ''. base .'item';
		return $data;
	}

	public static function get_joins($id=0,$args=array(),$limit=''){	
		self::$table = ''. base .'item-join';	
		$where = "WHERE item_id='$id' $limit";
		$data = parent::_check_join($where,$args,'join');

		self::$table = ''. base .'item';
		return $data;
	}	

	public static function get_detail($id=0,$args=array()){	
		self::$table = ''. base .'item-detail';	
		$where = "WHERE item='$id' $limit";
		$data = parent::_get_data($where,$args);

		self::$table = ''. base .'item';
		return $data;
	}

	public static function get_transaction($idx=0,$reff=0,$args=array()){	
		self::$table = ''. base .'item-detail';	
		$where = "WHERE item='$idx' AND reff='$reff' $limit";
		$data = parent::_get_data($where,$args);

		self::$table = ''. base .'item';
		return $data;
	}

	public static function get_currency($idx=0,$args=array()){
		self::$table = ''. base .'item-currency';	
		$where = "WHERE item_id='$idx'";
		$data = parent::_get_data($where,$args);

		self::$table = ''. base .'item';
		return $data;
	}

	public static function get_currencies($idx=0,$args=array()){
		self::$table = ''. base .'item-currency';	
		$where = "WHERE item_id='$idx'";
		$data = parent::_get_data($where,$args);

		self::$table = ''. base .'item';
		return $data;
	}
}