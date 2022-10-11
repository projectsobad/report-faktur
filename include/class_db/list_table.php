<?php
(!defined('AUTHPATH'))?exit:'';

class sobad_table{

	public static function _get_table($func){
		$func = str_replace('-','_',$func);
				
		$obj = new self();
		if(is_callable(array($obj,$func))){
			$list = $obj::{$func}();
				return $list;
			}
		
		return false;
	}
		
	public static function _get_list($func=''){
		$list = array();
		$lists = self::_get_table($func);
		if($lists){
			foreach ($lists as $key => $val) {
				$list[] = $key;
			}
		}
		
		return $list;
	}
		

	private static function _list_table(){
		// Information data table
		
		$table = array(
				'sbd-about'		=> self::sbd_about(),
				'sbd-account'		=> self::sbd_account(),
				'sbd-account-saldo'		=> self::sbd_account_saldo(),
				'sbd-cashflow'		=> self::sbd_cashflow(),
				'sbd-company'		=> self::sbd_company(),
				'sbd-company-meta'		=> self::sbd_company_meta(),
				'sbd-integration'		=> self::sbd_integration(),
				'sbd-item'		=> self::sbd_item(),
				'sbd-item-currency'		=> self::sbd_item_currency(),
				'sbd-item-detail'		=> self::sbd_item_detail(),
				'sbd-item-join'		=> self::sbd_item_join(),
				'sbd-item-meta'		=> self::sbd_item_meta(),
				'sbd-jurnal'		=> self::sbd_jurnal(),
				'sbd-meta'		=> self::sbd_meta(),
				'sbd-module'		=> self::sbd_module(),
				'sbd-post'		=> self::sbd_post(),
				'sbd-post-meta'		=> self::sbd_post_meta(),
				'sbd-sentiment'		=> self::sbd_sentiment(),
				'sbd-track-history'		=> self::sbd_track_history(),
				'sbd-transaksi'		=> self::sbd_transaksi(),
				'sasi-city'		=> self::sasi_city(),
				'sasi-country'		=> self::sasi_country(),
				'sasi-province'		=> self::sasi_province(),
				'sasi-subdistrict'		=> self::sasi_subdistrict(),
				'sasi-village'		=> self::sasi_village(),
		);
		
		return $table;
	}
		

		private static function sbd_about(){
			$list = array(
				'config_name'	=> '',
				'config_value'	=> '',
				'status'	=> 0,	
			);
			
			return $list;
		}

		private static function sbd_account(){
			$list = array(
				'name'	=> '',
				'no_rek'	=> '',
				'user'	=> 0,
				'currency'	=> '',
				'balance'	=> 0,
				'bank'	=> 0,
				'address'	=> '',
				'updated'	=> date('Y-m-d H:i:s'),
				'trash'	=> 0,
				'saldo_awal'	=> 0,
				'reff'	=> 0,	
			);
			
			return $list;
		}

		private static function sbd_account_saldo(){
			$list = array(
				'cash_id'	=> 0,
				'balance'	=> 0,
				'date'	=> date('Y-m-d'),
				'status'	=> '',	
			);
			
			return $list;
		}

		private static function sbd_cashflow(){
			$list = array(
				'reff'	=> 0,
				'user'	=> 0,
				'type'	=> '',
				'payment'	=> 0,
				'saldo_awal'	=> 0,
				'saldo_akhir'	=> 0,
				'account'	=> 0,
				'status'	=> 0,
				'note'	=> '',
				'date_log'	=> date('Y-m-d H:i:s'),	
			);
			
			return $list;
		}

		private static function sbd_company(){
			$list = array(
				'name'	=> '',
				'username'	=> '',
				'password'	=> '',
				'email'	=> '',
				'phone_no'	=> '',
				'type'	=> 0,
				'inserted'	=> date('Y-m-d H:i:s'),
				'updated'	=> date('Y-m-d H:i:s'),
				'reff'	=> 0,
				'no_cp'	=> 0,
				'note'	=> 0,
				'alamat'	=> '',	
			);
			
			return $list;
		}

		private static function sbd_company_meta(){
			$list = array(
				'meta_id'	=> 0,
				'meta_key'	=> '',
				'meta_value'	=> '',	
			);
			
			return $list;
		}

		private static function sbd_integration(){
			$list = array(
				'name'	=> '',
				'int_curl'	=> '',
				'int_data'	=> '',
				'out_data'	=> '',
				'int_key'	=> '',
				'inserted'	=> date('Y-m-d H:i:s'),	
			);
			
			return $list;
		}

		private static function sbd_item(){
			$list = array(
				'name'	=> '',
				'part_id'	=> 0,
				'product_code'	=> '',
				'picture'	=> '',
				'price'	=> 0,
				'category'	=> 0,
				'weight'	=> 0.00,
				'satuan'	=> '',
				'company'	=> 0,
				'type'	=> 0,
				'var'	=> 0,
				'stock'	=> 0.00,
				'inserted'	=> date('Y-m-d H:i:s'),
				'updated'	=> date('Y-m-d H:i:s'),
				'sync_item'	=> 0,
				'trash'	=> 0,	
			);
			
			return $list;
		}

		private static function sbd_item_currency(){
			$list = array(
				'item_id'	=> 0,
				'name'	=> '',
				'currency'	=> '',
				'price'	=> 0.00,	
			);
			
			return $list;
		}

		private static function sbd_item_detail(){
			$list = array(
				'item'	=> 0,
				'sku'	=> '',
				'off_date'	=> date('Y-m-d'),
				'notes'	=> '',
				'reff'	=> 0,
				'status'	=> 0,	
			);
			
			return $list;
		}

		private static function sbd_item_join(){
			$list = array(
				'item_id'	=> 0,
				'join_id'	=> 0,
				'item_qty'	=> 0,
				'status'	=> 0,
				'note'	=> '',	
			);
			
			return $list;
		}

		private static function sbd_item_meta(){
			$list = array(
				'meta_id'	=> 0,
				'meta_key'	=> '',
				'meta_value'	=> '',	
			);
			
			return $list;
		}

		private static function sbd_jurnal(){
			$list = array(
				'post_id'	=> 0,
				'type_akun'	=> 0,
				'reff_akun'	=> 0,
				'id_akun'	=> 0,
				'type_report'	=> 0,
				'currency'	=> '',
				'debit'	=> 0.00,
				'kredit'	=> 0.00,
				'type_jurnal'	=> 0,
				'close_date'	=> date('Y-m-d'),
				'insert_jurnal'	=> date('Y-m-d H:i:s'),
				'reff_jurnal'	=> 0,	
			);
			
			return $list;
		}

		private static function sbd_meta(){
			$list = array(
				'meta_key'	=> '',
				'meta_value'	=> '',
				'meta_note'	=> '',
				'inserted'	=> date('Y-m-d H:i:s'),
				'updated'	=> date('Y-m-d H:i:s'),
				'meta_reff'	=> 0,	
			);
			
			return $list;
		}

		private static function sbd_module(){
			$list = array(
				'name'	=> '',
				'meta_name'	=> '',
				'detail'	=> '',	
			);
			
			return $list;
		}

		private static function sbd_post(){
			$list = array(
				'title'	=> 0,
				'company'	=> 0,
				'contact'	=> 0,
				'type'	=> 0,
				'user'	=> 0,
				'payment'	=> 0,
				'post_date'	=> date('Y-m-d'),
				'status'	=> 0,
				'inserted'	=> date('Y-m-d H:i:s'),
				'updated'	=> date('Y-m-d H:i:s'),
				'var'	=> '',
				'notes'	=> '',
				'reff'	=> 0,
				'reff_note'	=> 0,
				'trash'	=> 0,	
			);
			
			return $list;
		}

		private static function sbd_post_meta(){
			$list = array(
				'meta_id'	=> 0,
				'meta_key'	=> '',
				'meta_value'	=> '',	
			);
			
			return $list;
		}

		private static function sbd_sentiment(){
			$list = array(
				'id_integration'	=> 0,
				'name'	=> '',
				'value'	=> 0,
				'id_curl'	=> 0,
				'val_error'	=> 0,	
			);
			
			return $list;
		}

		private static function sbd_track_history(){
			$list = array(
				'post_id'	=> 0,
				'barang'	=> 0,
				'first_code'	=> 0,
				'last_code'	=> 0,
				'inserted'	=> date('Y-m-d H:i:s'),	
			);
			
			return $list;
		}

		private static function sbd_transaksi(){
			$list = array(
				'post'	=> 0,
				'barang'	=> 0,
				'qty'	=> 0.00,
				'unit'	=> '',
				'price'	=> 0.00,
				'discount'	=> 0,
				'note'	=> '',
				'keyword'	=> '',
				'extends'	=> 0,
				'temporary'	=> '',	
			);
			
			return $list;
		}

		private static function sasi_city(){
			$list = array(
				'id_province'	=> 0,
				'city'	=> '',
				'type'	=> '',	
			);
			
			return $list;
		}

		private static function sasi_country(){
			$list = array(
				'country'	=> '',
				'code'	=> '',
				'code1'	=> '',
				'currency'	=> '',	
			);
			
			return $list;
		}

		private static function sasi_province(){
			$list = array(
				'id_country'	=> 0,
				'province'	=> '',	
			);
			
			return $list;
		}

		private static function sasi_subdistrict(){
			$list = array(
				'id_city'	=> 0,
				'subdistrict'	=> '',	
			);
			
			return $list;
		}

		private static function sasi_village(){
			$list = array(
				'id_subdistrict'	=> 0,
				'village'	=> '',
				'postal_code'	=> 0,	
			);
			
			return $list;
		}

}