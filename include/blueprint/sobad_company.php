<?php

class sobad_company extends _class{
	public static $table = "". base ."company";

	public static $tbl_meta = "". base ."company-meta";

	protected static $group = "GROUP BY `". base ."company-meta`.meta_id";

	protected static $list_meta = array();

	public static function set_listmeta(){
		$type = parent::$_type;

		switch ($type) {
			case 'expedition':
				self::$list_meta = array(
					'_address',
					'_subdistrict',
					'_city',
					'_province',
					'_country',
					'_postcode',
					'_email',
					'_courier'
				);
				break;

			case 'company_profile':
				self::$list_meta = array(
					'_address',
					'_email',
					'_logo',
					'_type',
					'_ppn',
					'_pph'
				);
				break;

			case 'university':
				self::$list_meta = array(
					'_address',
					'_subdistrict',
					'_city',
					'_province',
					'_country',
					'_postcode',
					'_email',
					'_faculty'
				);

				break;
			
			default:
				self::$list_meta = array(
					'_address',
					'_subdistrict',
					'_city',
					'_province',
					'_country',
					'_postcode',
					'_email',
					'_npwp',
					'_nama_bank',
					'_account_rekening',
					'_no_rekening',
					'_address_npwp'
				);
				break;
		}
	}

	public static function blueprint($type='company'){
		$args = array(
			'type'		=> $type,
			'table'		=> self::$table,
			'meta'		=> array(
				'key'		=> 'meta_id',
				'table'		=> self::$tbl_meta,
			)
		);

		if($type=='contact' || $type=='university'){
			$args['detail'] = array(
				'reff'	=> array(
					'key'		=> 'ID',
					'table'		=> "". base ."company",
					'column'	=> array('name','phone_no')
				)
			);
		}

		return $args;
	}

// -----------------------------------------------------------------
// --- Function Company --------------------------------------------
// -----------------------------------------------------------------	
	
	public static function get_customers($args=array(),$limit=''){
		$where = "WHERE `". base ."company`.type='1' $limit";
		return self::_check_join($where,$args,'contact');
	}
	
	public static function get_suppliers($args=array(),$limit=''){
		$where = "WHERE `". base ."company`.type IN ('2','5') $limit";
		return self::_check_join($where,$args,'supplier');
	}

	public static function get_companies($args=array(),$limit=''){
		$where = "WHERE `". base ."company`.type IN ('3','5') $limit";
		return self::_check_join($where,$args);
	}

	public static function get_expeditions($args=array(),$limit=''){
		$where = "WHERE `". base ."company`.type='4' $limit";
		return self::_check_join($where,$args,'expedition');
	}

	public static function get_profile($args=array(),$limit=''){
		$where = "WHERE `". base ."company`.type='6' AND `". base ."company`.reff='0' $limit";
		return self::_check_join($where,$args,'company_profile');
	}

	public static function get_universities($args=array(),$limit=''){
		$where = "WHERE `". base ."company`.type='7' $limit";
		return self::_check_join($where,$args,'university');
	}

	public static function get_contacts($args=array(),$limit=''){
		$where = "WHERE `". base ."company`.type IN ('1','7') $limit";
		return self::_check_join($where,$args,'contact');
	}

	public static function _check_profile(){
		$args = array('ID', '_type', '_ppn','_pph');
		$comp = self::get_profile($args);
		$comp = $comp[0];

		if($comp['_type']){
			$comp['status'] = true;
			return $comp;
		}

		return array('status' => false);
	}
}