<?php

class sobad_account extends _class{
	
	public static $table = ''. base .'account';

	public static function blueprint(){
		$args = array(
			'type'	=> 'account',
			'table'	=> self::$table
		);

		return $args;
	}

	public static function get_assets(){
		$data = sobad_accounting::class_asset();

		$args = array(
			'name',
			'bank',
			'balance'
		);

		$akun = self::get_all($args);
		foreach ($akun as $key => $val) {
			if($val['bank']==0){
				$data['Kas']['data'][$val['name']] = array(
					'ID'		=> $val['ID'],
					'balance'	=> $val['balance']
				);
			}else{
				$name = account_purchase::_get_bank($val['bank']);
				$data['Kas Bank']['data'][$name['bank']] = array(
					'ID'		=> $val['ID'],
					'balance'	=> $val['balance']
				);
			}
		}

		$tot_kmi = 0;
		$post = sobad_post::get_invoices(array('ID','reff','type','_nominal'),"AND `". base ."post`.status='0' AND `". base ."post`.trash='0'" );
		foreach ($post as $key => $val) {
			//Nominal
			$value = faktur_accounting::_get_nominalInvoice($val['reff_reff'], $val['reff']);
			$nominal = $value['nominal'];
			$ppn = $value['ppn'];
			$discount = $value['discount'];
			$total = $value['total'];

			// Get DP Invoice
			$dp = invoice_marketing::_get_dp($val['_nominal'],$nominal,$val['type'],$val['reff']);

			// Get Termin Invoice
			$termin = invoice_marketing::_get_termin($dp, $val['_nominal'],$total,$discount,$val['type'],$val['reff'],0,$val['ID']);

			if($val['type']==4){
				$tagihan = invoice_marketing::_get_lunas($dp,$termin['nominal'],$nominal);
			}else if($val['type']==2){
				$tagihan = $termin['nominal'];
			}else{
				$tagihan = $dp;
			}

			$tot_kmi += $tagihan;
		}

		$tot_malika = 0;
		$post = sobad_post::get_orders(array('ID','_shipping_price'),"AND `". base ."post`.reff='1' AND `". base ."post`.trash='0'");
		foreach ($post as $key => $val) {
			$pay = debit_retail::detail_payment($val);
			$tot_malika += $pay['hutang'];
		}

		$data['Piutang Usaha']['data'] = array(
			'Perusahaan'=> array('ID' => 1,'balance' => $tot_kmi),
			'Retail'	=> array('ID' => 2,'balance' => $tot_malika),
		);

		return $data;
	}
}