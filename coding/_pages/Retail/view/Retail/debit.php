<?php

class debit_retail extends _page{

	protected static $object = 'debit_retail';

	protected static $table = 'sobad_post';

	protected static $post = 'order';

	// ----------------------------------------------------------
	// Layout category  ------------------------------------------
	// ----------------------------------------------------------

	public static function _array(){
		$args = array(
			'ID',
			'title',
			'contact',
			'_resi',
			'type',
			'post_date',
			'inserted',
			'_shipping_price'
		);

		return $args;
	}

	public static function _filter_search($field = '', $search = '')
	{
		return transaksi_retail::_filter_search($field,$search);
	}

	protected static function table(){
		$data = array();
		$args = self::_array();

		$start = intval(parent::$page);
		$nLimit = intval(parent::$limit);
		
		$_search = '';
		$kata = '';$where = "AND `". base ."post`.reff='1' AND `". base ."post`.trash='0'";
		if(parent::$search){
			$src = parent::like_search($args,$where);	
			$cari = $src[0];
			$where = $src[0];
			$kata = $src[1];
			$_search = $src[2];
		}else{
			$cari=$where;
		}
	
		$limit = 'LIMIT '.intval(($start - 1) * $nLimit).','.$nLimit;
		$where .= $limit;

		$object = self::$table;
		$sum_data = $object::count("`". base ."post`.var='order' ".$cari,$args,self::$post);
		$args = $object::get_orders($args,$where);
		
		$data['data'] = array('data' => $kata, 'value' => $_search);
		$data['search'] = array('Semua','No Order','Nama','No Resi');
		$data['class'] = '';
		$data['table'] = array();
		$data['page'] = array(
			'func'	=> '_pagination',
			'data'	=> array(
				'start'		=> $start,
				'qty'		=> $sum_data,
				'limit'		=> $nLimit
			)
		);

		$no = ($start-1) * $nLimit;
		foreach($args as $key => $val){
			$no += 1;
			$idx = $val['ID'];

			$view = array(
				'ID'	=> 'view_'.$idx,
				'func'	=> '_view',
				'color'	=> 'yellow',
				'icon'	=> 'fa fa-eye',
				'label'	=> 'view'
			);

			$paid = array(
				'ID'	=> 'paid_'.$idx,
				'func'	=> '_paid',
				'color'	=> 'green',
				'icon'	=> 'fa fa-money',
				'label'	=> 'paid'
			);

			// Get Total belanja
			$payment = self::detail_payment($val);


			$data['table'][$key]['tr'] = array('');
			$data['table'][$key]['td'] = array(
				'No PO'			=> array(
					'left',
					'15%',
					transaksi_retail::_post_title($val['title'],$val['meta_note_type'],$val['inserted']),
					true
				),
				'Tanggal'	=> array(
					'left',
					'15%',
					format_date_id($val['post_date']),
					true
				),
				'Customer'		=> array(
					'left',
					'auto',
					$val['name_cont'],
					true
				),
				'No Resi'		=> array(
					'left',
					'12%',
					$val['_resi'],
					true
				),
				'Piutang'		=> array(
					'right',
					'13%',
					'Rp. '.format_nominal($payment['hutang']),
					true
				),
				'Total Order'	=> array(
					'right',
					'13%',
					'Rp. '.format_nominal($payment['total']),
					true
				),
				'View'			=> array(
					'center',
					'10%',
					edit_button($view),
					false
				),
				'Paid'			=> array(
					'center',
					'10%',
					edit_button($paid),
					false
				)
			);
		}
		
		return $data;
	}

	private static function head_title(){
		$args = array(
			'title'	=> 'Piutang <small>data piutang</small>',
			'link'	=> array(
				0	=> array(
					'func'	=> self::$object,
					'label'	=> 'piutang'
				)
			),
			'date'	=> false
		); 
		
		return $args;
	}

	protected static function get_box(){
		$data = self::table();
		
		$box = array(
			'label'		=> 'Data Piutang',
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

	public static function detail_payment($val=array()){
	// Get Total belanja
		$total = $pajak = 0;
		$idx = $val['ID'];

		$item = sobad_post::get_transactions($idx,array('price','qty','discount','note','extends'));
		foreach ($item as $ky => $vl) {
			$ppn = $vl['note']==1 ? $vl['extends'] : 0;
			$total += (($vl['price'] - $vl['discount']) * $vl['qty']) + $ppn;

			$pajak += $ppn;
		}

		// Get pembayaran
		$hutang = 0;
		$pay = sobad_post::get_all(array('ID','price'),"AND `". base ."post`.var='paid' AND `". base ."post`.reff='$idx'",'paid');
		foreach ($pay as $_key => $_val) {
			$hutang += $_val['price'];
		}

		$shipping = isset($val['_shipping_price']) ? (int) $val['_shipping_price'] : 0;
		$total = ($total + $shipping);
		$hutang = $total - $hutang;

		return array('total' => $total, 'hutang' => $hutang, 'ppn'	=> $pajak);
	}

	// ----------------------------------------------------------
	// Form pembayaran ------------------------------------------
	// ----------------------------------------------------------
	public static function _paid($id=''){
		$id = str_replace('paid_', '', $id);
		intval($id);

		$data = self::_data_form($id);
		
		$args = array(
			'title'		=> 'Bayar piutang',
			'button'	=> '_btn_modal_save',
			'status'	=> array(
				'link'		=> '_add_pay',
				'load'		=> 'sobad_portlet'
			)
		);

		$args['func'] = array('sobad_form');
		$args['data'] = array($data);
		
		return modal_admin($args);
	}

	public static function _data_form($idx=0){
		// GET payment method
		$payment = array();
		$account = sobad_account::get_all(array('ID','name','bank'),"AND trash='0'");
		foreach ($account as $key => $val) {
			$name = $val['name'];
			if($val['bank']>0){
				$bank = account_purchase::_get_bank($val['bank']);
				$name = $bank['bank'].' a.n. <span>'.$name.'</span>';
			}
			$payment[$val['ID']] = $name;
		}

		reset($payment);
		$akun = key($payment);

		$saldo = sobad_account::get_id($akun,array('balance'));
		$saldo = $saldo[0]['balance'];

		// Get pembayaran
		$pays = 0;
		$purc = sobad_post::get_id($idx,array('ID','_shipping_price'),'','order');
		$post = sobad_post::get_all(array('ID','price'),"AND `". base ."post`.var='paid' AND `". base ."post`.reff='$idx'",'paid');
		foreach ($post as $key => $val) {
			$pays += $val['price'];
		}

		// Get Total belanja
		$total = 0;
		$item = sobad_post::get_transactions($idx,array('price','qty','discount'));
		foreach ($item as $ky => $vl) {
			$total += (($vl['price'] - $vl['discount']) * $vl['qty']);
		}

		$total += $purc[0]['_shipping_price'];
		$sisa = $total - $pays;

		$data = array(
			0 => array(
				'func'			=> 'opt_hidden',
				'type'			=> 'hidden',
				'key'			=> 'ID',
				'value'			=> $idx
			),
			array(
				'func'			=> 'opt_hidden',
				'type'			=> 'hidden',
				'key'			=> 'total',
				'value'			=> $total
			),
			array(
				'func'			=> 'opt_select',
				'data'			=> $payment,
				'key'			=> 'payment',
				'label'			=> 'Pembayaran',
				'class'			=> 'input-circle',
				'select'		=> 0,
				'status'		=> 'data-sobad="set_balance_akun" data-load="balance_akun" data-attribute="val"'
			),
			array(
				'id'			=> 'balance_akun',
				'func'			=> 'opt_input',
				'type'			=> 'text',
				'key'			=> 'balance',
				'label'			=> 'Saldo',
				'class'			=> 'input-circle',
				'value'			=> format_nominal($saldo),
				'data'			=> 'placeholder="Saldo" readonly'
			),
			array(
				'func'			=> 'opt_input',
				'type'			=> 'date',
				'key'			=> 'post_date',
				'label'			=> 'Tanggal',
				'class'			=> 'input-circle',
				'value'			=> date('Y-m-d'),
				'data'			=> ''
			),
			array(
				'func'			=> 'opt_input',
				'type'			=> 'price',
				'key'			=> 'price',
				'label'			=> 'Uang',
				'class'			=> 'input-circle',
				'value'			=> format_nominal($sisa),
				'data'			=> 'placeholder="Pembayaran"'
			)
		);
		
		return $data;
	}

	public static function set_balance_akun($id=0){
		$akun = sobad_account::get_id($id,array('balance'));
		$akun = format_nominal($akun[0]['balance']);

		return empty($akun)?$akun.' ':$akun;
	}

	// ----------------------------------------------------------
	// Form data Hutang -----------------------------------------
	// ----------------------------------------------------------

	public static function _view($id=0){
		$id = str_replace('view_', '', $id);
		intval($id);

		$data = self::_table_pay($id);

		$args = array(
			'title'		=> 'History pembayaran',
			'button'	=> '',
			'status'	=> array(
				'link'		=> '',
				'load'		=> 'sobad_portlet'
			)
		);

		$args['func'] = array('sobad_table');
		$args['data'] = array($data);
		
		return modal_admin($args);
	}

	public static function _table_pay($idx=0){
		$unit = unit::_get(array('bank'));
		$unit = $unit['bank']['unit'];

		$data['class'] = '';
		$data['table'] = array();

		$args = sobad_post::get_all(array('user','payment','inserted','price'),"AND `". base ."post`.var='paid' AND `". base ."post`.reff='$idx'",'paid');
	
		$no = 0;
		foreach($args as $ky => $vl){
			if($vl['price']<=0){
				continue;
			}
			$no += 1;

			$user = kmi_user::get_id($vl['user'],array('name'));
			$check = array_filter($user);

			$user = empty($check)?'-':$user[0]['name'];

			$account = $vl['name_paym'];
			if($vl['bank_paym']!=0){
				$account = $unit[$vl['bank_paym']]['name'].' ('.$unit[$vl['bank_paym']]['code'].')<br>a.n. '.$account;
			}

			$data['table'][$no-1]['tr'] = array('');
			$data['table'][$no-1]['td'] = array(
				'no'			=> array(
					'center',
					'5%',
					$no,
					true
				),
				'user'			=> array(
					'left',
					'auto',
					$user,
					true
				),
				'payment'		=> array(
					'left',
					'30%',
					$account,
					true
				),
				'pembayaran'	=> array(
					'right',
					'20%',
					'Rp. '.format_nominal($vl['price']),
					true
				),
				'Tanggal'		=> array(
					'left',
					'15%',
					format_date_id($vl['inserted']),
					true
				)
			);
		}
		
		return $data;
	}

	// -------------------------------------------------------------
	// Database ----------------------------------------------------
	// -------------------------------------------------------------

	public static function _add_pay($args=array()){
		$args = sobad_asset::ajax_conv_json($args);
		$reff = $args['ID'];

		if(isset($args['search'])){
			$src = array(
				'search'	=> $args['search'],
				'words'		=> $args['words']
			);
		}

		// Get pembayaran
		$pays = 0;
		$post = sobad_post::get_all(array('ID','price'),"AND `". base ."post`.var='paid' AND `". base ."post`.reff='$reff'",'paid');
		foreach ($post as $key => $val) {
			$pays += $val['price'];
		}

		$sisa = $args['total'] - $pays;

		if($args['price']>$sisa){
			die(_error::_alert_db('Pembayaran melebihi piutang!!!'));
		}

		// Get Akun
		$akun = sobad_account::get_id($args['payment'],array('balance'));
		$akun = $akun[0];

		$saldo = $akun['balance'] + $args['price'];
		sobad_db::_update_single($args['payment'],''. base .'account',array('ID' => $args['payment'], 'balance' => $saldo));

		// Update status Lunas
		if($args['price']>=$sisa){
			sobad_db::_update_single($reff,''. base .'post',array('ID' => $reff,'reff' => 0));
		}

		// Get no title
		$no = quotation_marketing::_get_max('paid');
		$q = sobad_db::_insert_table(''. base .'post',array(
			'title'			=> $no + 1,
			'user'			=> get_id_user(),
			'payment'		=> $args['payment'],
			'post_date'		=> $args['post_date'],
			'updated'		=> '0000-00-00 00:00:00',
			'var'			=> 'paid',
			'status'		=> 1,
			'reff'			=> $reff
		));

		// Add Detail pembayaran
		sobad_db::_insert_table(''. base .'transaksi',array(
			'post'		=> $q,
			'qty'		=> 1,
			'price'		=> $args['price'],
			'unit'		=> 'IDR'
		));

		if($q!==0){
			$pg = isset($_POST['page'])?$_POST['page']:1;
			return self::_get_table($pg,$src);
		}
	}
}