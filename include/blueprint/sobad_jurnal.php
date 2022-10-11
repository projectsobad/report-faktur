<?php

class sobad_jurnal extends _class{
	
	public static $table = ''. base .'jurnal';

	public static function blueprint(){
		$args = array(
			'type'	=> 'jurnal',
			'table'	=> self::$table
		);

		return $args;
	}

	public static function _check($id=0, $type=0, $reff=0, $jurnal=0, $post_date='',$id_akun=0){
		$date = empty($post_date) ? date('Y-m-d') : $post_date;
		$date = strtotime($date);

		$y = date('Y',$date); $m = date('m',$date);

		$limit = !empty($id_akun) ? "AND id_akun='$id_akun'" : '';
		$where = "WHERE post_id='$id' AND type_akun='$type' AND reff_akun='$reff' AND type_jurnal='$jurnal' AND YEAR(close_date)='$y' AND MONTH(close_date)='$m' $limit";
		$check = self::_check_join($where,array('ID','post_id'));

		return isset($check[0]) ? $check[0]['ID'] : 0;
	}

	public static function _get_jurnal($id=0,$type=0,$reff=0,$jurnal=0){
		$where = "WHERE post_id='$id' AND type_akun='$type' AND reff_akun='$reff' AND type_jurnal='$jurnal'";
		$check = self::_check_join($where,array('ID'));

		return isset($check[0]) ? $check[0]['ID'] : 0;
	}

	public static function gets_jurnal($jurnal=0,$post_date='',$limit=''){
		$date = empty($post_date) ? date('Y-m-d') : $post_date;
		$date = strtotime($date);

		$y = date('Y',$date); $m = date('m',$date);

		$where = "WHERE YEAR(close_date)='$y' AND MONTH(close_date)='$m' AND type_jurnal='$jurnal' $limit ORDER BY close_date";
		
		return self::_check_join($where);
	}

	public static function _gets_bigBook($post_date=''){
		$date = empty($post_date) ? date('Y-m-d') : $post_date;
		$date = strtotime($date);

		$y = date('Y',$date); $m = date('m',$date);

		$where = "WHERE type_jurnal='10' AND YEAR(close_date)='$y' AND MONTH(close_date)='$m'";
		return self::_check_join($where);
	}

	public static function _get_jurnal_by_bbu($post_date=''){
		$date = empty($post_date) ? date('Y-m-d') : $post_date;
		$date = strtotime($date);

		$y = date('Y',$date); $m = date('m',$date);

		$where = "WHERE YEAR(close_date)='$y' AND MONTH(close_date)='$m' AND type_jurnal IN (0,3,4)";
		
		return self::_check_join($where,array('ID','type_akun','reff_akun','id_akun','close_date','kredit','debit','type_jurnal'));
	}

	public static function _get_jurnal_by_bpp($post_date='',$limit=''){
		$date = empty($post_date) ? date('Y-m-d') : $post_date;
		$date = strtotime($date);

		$y = date('Y',$date); $m = date('m',$date);

		$where = "WHERE YEAR(close_date)='$y' AND MONTH(close_date)='$m' AND type_jurnal='1' $limit";
		
		return self::_check_join($where,array('ID','type_akun','reff_akun','id_akun','close_date','kredit','debit'));
	}

	public static function _get_jkm_by_bpp($type=0, $reff=0, $akun=0, $post_date='', $limit=''){
		$date = empty($post_date) ? date('Y-m-d') : $post_date;
		$date = strtotime($date);

		$y = date('Y',$date); $m = date('m',$date);

		$where = "WHERE YEAR(close_date)='$y' AND MONTH(close_date)='$m' AND type_akun='$type' AND reff_akun='$reff' AND id_akun='$akun' AND type_jurnal='4' $limit";
		
		return self::_check_join($where,array('ID','debit','kredit'));
	}

	public static function _get_jurnal_by_bpu($post_date=''){
		$date = empty($post_date) ? date('Y-m-d') : $post_date;
		$date = strtotime($date);

		$y = date('Y',$date); $m = date('m',$date);

		$where = "WHERE YEAR(close_date)='$y' AND MONTH(close_date)='$m' AND type_jurnal='2'";
		
		return self::_check_join($where,array('ID','post_id','type_akun','reff_akun','id_akun','close_date','kredit','debit'));
	}

	public static function _get_jkk_by_bpu($type=0, $reff=0, $akun=0, $post_date='', $limit=''){
		$date = empty($post_date) ? date('Y-m-d') : $post_date;
		$date = strtotime($date);

		$y = date('Y',$date); $m = date('m',$date);

		$where = "WHERE YEAR(close_date)='$y' AND MONTH(close_date)='$m' AND type_akun='$type' AND reff_akun='$reff' AND id_akun='$akun' AND type_jurnal='3' $limit";
		
		return self::_check_join($where,array('ID','debit','kredit'));
	}

	public static function _get_bukuBesar_reff($type=0, $reff=0, $post_date='', $limit=''){
		$date = empty($post_date) ? date('Y-m-d') : $post_date;
		$date = strtotime($date);

		$y = date('Y',$date); $m = date('m',$date);

		$where = "WHERE YEAR(close_date)='$y' AND MONTH(close_date)='$m' AND type_akun='$type' AND reff_akun='$reff' AND type_jurnal IN (10,11,12) $limit";
		
		return self::_check_join($where,array('ID','type_akun','reff_akun','id_akun','type_jurnal','close_date','kredit','debit','type_jurnal'));
	}

	public static function _get_bukuBesar_not_type($type=0, $reff=0, $post_date='', $limit=''){
		$date = empty($post_date) ? date('Y-m-d') : $post_date;
		$date = strtotime($date);

		$y = date('Y',$date); $m = date('m',$date);

		$where = "WHERE YEAR(close_date)='$y' AND MONTH(close_date)='$m' AND type_akun='$type' AND reff_akun='$reff' $limit";
		
		return self::_check_join($where,array('ID','type_akun','reff_akun','id_akun','type_jurnal','close_date','kredit','debit','type_jurnal'));
	}

	public static function _get_bukuBesar($type=0, $post_date='', $limit=''){
		$date = empty($post_date) ? date('Y-m-d') : $post_date;
		$date = strtotime($date);

		$y = date('Y',$date); $m = date('m',$date);

		$where = "WHERE YEAR(close_date)='$y' AND MONTH(close_date)='$m' AND type_jurnal='$type' $limit";
		
		return self::_check_join($where,array('ID','type_akun','reff_akun','id_akun','debit','kredit','close_date'));
	}

	public static function _get_jurnal_jkm($type=0, $reff=0, $post_date='', $limit=''){
		$date = empty($post_date) ? date('Y-m-d') : $post_date;
		$date = strtotime($date);

		$y = date('Y',$date); $m = date('m',$date);

		$where = "WHERE YEAR(close_date)='$y' AND MONTH(close_date)='$m' AND type_akun='$type' AND reff_akun='$reff' AND type_jurnal='4' $limit ORDER BY close_date";

		return self::_check_join($where,array('ID','type_akun','reff_akun','id_akun','type_jurnal','close_date','kredit','debit','type_jurnal'));
	}	

	public static function _get_jurnal_reff($type=0, $reff=0, $post_date='', $limit=''){
		$date = empty($post_date) ? date('Y-m-d') : $post_date;
		$date = strtotime($date);

		$y = date('Y',$date); $m = date('m',$date);

		$where = "WHERE YEAR(close_date)='$y' AND MONTH(close_date)='$m' AND type_akun='$type' AND reff_akun='$reff' AND type_jurnal IN (0,3,4) $limit ORDER BY close_date";

		return self::_check_join($where,array('ID','type_akun','reff_akun','id_akun','type_jurnal','close_date','kredit','debit','type_jurnal'));
	}

	public static function _get_piutang_reff($type=0, $reff=0, $id_akun=0, $post_date=''){
		$date = empty($post_date) ? date('Y-m-d') : $post_date;
		$date = strtotime($date);

		$y = date('Y',$date); $m = date('m',$date);

		$where = "WHERE YEAR(close_date)='$y' AND MONTH(close_date)='$m' AND type_akun='$type' AND reff_akun='$reff' AND id_akun='$id_akun' AND type_jurnal IN ('1','4') ORDER BY close_date";

		return self::_check_join($where,array('ID','type_akun','reff_akun','id_akun','type_jurnal','close_date','kredit','debit','type_jurnal'));
	}

	public static function _get_utang_reff($id_akun=0, $post_date=''){
		$date = empty($post_date) ? date('Y-m-d') : $post_date;
		$date = strtotime($date);

		$y = date('Y',$date); $m = date('m',$date);

		$where = "WHERE YEAR(close_date)='$y' AND MONTH(close_date)='$m' AND id_akun='$id_akun' AND type_jurnal IN ('2','3') ORDER BY close_date";

		return self::_check_join($where,array('ID','type_akun','reff_akun','id_akun','type_jurnal','close_date','kredit','debit','type_jurnal'));
	}

	public static function _get_neracaSaldo($post_date='',$report=''){
		$date = empty($post_date) ? date('Y-m-d') : $post_date;
		$date = strtotime($date);

		$y = date('Y',$date); $m = date('m',$date);

		$limit = !empty($report) ? "AND type_report='$report'" : "";
		$where = "WHERE YEAR(close_date)='$y' AND MONTH(close_date)='$m' AND type_jurnal='15' $limit";
		
		return self::_check_join($where,array('ID','type_akun','reff_akun','id_akun','type_jurnal','close_date','kredit','debit','type_jurnal','type_report'));
	}

	public static function _get_labaRugi($post_date=''){
		$date = empty($post_date) ? date('Y-m-d') : $post_date;
		$date = strtotime($date);

		$y = date('Y',$date); $m = date('m',$date);

		$where = "WHERE YEAR(close_date)='$y' AND MONTH(close_date)='$m' AND type_jurnal='16' $limit";
		
		return self::_check_join($where,array('ID','close_date','kredit','debit','type_jurnal'));
	}

	public static function _get_changeModal($post_date=''){
		$date = empty($post_date) ? date('Y-m-d') : $post_date;
		$date = strtotime($date);

		$y = date('Y',$date); $m = date('m',$date);

		$where = "WHERE YEAR(close_date)='$y' AND MONTH(close_date)='$m' AND type_jurnal='17'";
		
		return self::_check_join($where,array('ID','close_date','kredit','debit','type_jurnal'));
	}
}