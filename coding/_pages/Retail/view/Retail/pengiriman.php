<?php

class send_retail extends _page{

	protected static $object = 'send_retail';

	protected static $table = 'sobad_post';

	protected static $post = 'order';

	// ----------------------------------------------------------
	// Layout category  ------------------------------------------
	// ----------------------------------------------------------

	protected static function _array(){
		$args = array(
			'ID',
			'title',
			'contact',
			'type',
			'inserted',
			'_expedition',
			'_resi',
			'_shipping_price',
			'_payment_method'
		);

		return $args;
	}

	public static function _filter_search($field='',$search=''){
		if($field=='title'){
			$table = '`'. base .'post`.';
			return transaksi_retail::_query_filter_search($field,$search,'title',$table);
		}

		if($field=='contact'){
			return "_contact.name LIKE '%$search%'";
		}
	}

	protected static function table(){
		$data = array();
		$args = self::_array();
		$start = intval(parent::$page);
		$nLimit = intval(parent::$limit);
		
		$_search = '';
		$type = str_replace('send_','',self::$type);
		$kata = '';$where = "AND var IN ('order','non_order') AND status='$type' ";

		if(parent::$search){
			$src = parent::like_search($args,$where);	
			$cari = $src[0];
			$where = $src[0];
			$kata = $src[1];
			$_search = $src[2];
		}else{
			$cari=$where;
		}
	
		$limit = 'ORDER BY post_date DESC LIMIT '.intval(($start - 1) * $nLimit).','.$nLimit;
		$where .= $limit;

		$object = self::$table;
		$sum_data = $object::count("1=1 ".$cari,$args,self::$post);
		$args = $object::get_all($args,$where,'order');
		
		$data['data'] = array('data' => $kata, 'value' => $_search, 'type' => self::$type);
		$data['search'] = array('Semua','no. order','nama');
		$data['class'] = '';
		$data['table'] = array();
		$data['page'] = array(
			'func'	=> '_pagination',
			'data'	=> array(
				'start'		=> $start,
				'qty'		=> $sum_data,
				'limit'		=> $nLimit,
				'type'		=> self::$type
			)
		);

		$no = ($start-1) * $nLimit;
		foreach($args as $key => $val){
			$no += 1;
			$id = $val['ID'];

			$edit = array(
				'ID'	=> 'edit_'.$id,
				'func'	=> '_edit',
				'color'	=> 'blue',
				'icon'	=> 'fa fa-edit',
				'label'	=> 'edit',
				'type' 	=> self::$type
			);
			
			$save = array(
				'ID'	=> 'save_'.$id,
				'func'	=> 'save_form',
				'color'	=> 'green',
				'icon'	=> 'fa fa-save',
				'label'	=> 'simpan',
				'type'	=> self::$type
			);

			$send = array(
				'ID'	=> 'send_'.$id,
				'func'	=> '_send_order',
				'color'	=> 'yellow',
				'icon'	=> 'fa fa-truck',
				'label'	=> 'kirim',
				'spin'	=> true,
				'type'	=> self::$type
			);

			$resi = $val['_resi'];$biaya = format_nominal($val['_shipping_price']);

			$courier = $val['_expedition'];
			$comp = sobad_company::get_id($courier,array('name'));
			
			$check = array_filter($comp);
			if(!empty($check)){
				$courier = $comp[0]['name'];
			}
			
			$data['table'][$key]['tr'] = array('');
			$data['table'][$key]['td'] = array(
				'No'		=> array(
					'center',
					'5%',
					$no,
					true
				),
				'No. Order'	=> array(
					'left',
					'15%',
					transaksi_retail::_post_title($val['title'],$val['meta_note_type'],$val['inserted']),
					true
				),
				'Customer'	=> array(
					'left',
					'auto',
					$val['name_cont'],
					true
				),
				'Kurir'		=> array(
					'left',
					'20%',
					$courier,
					true
				),
				'No. Resi'	=> array(
					'left',
					'20%',
					$resi,
					true
				),
				'Biaya'		=> array(
					'left',
					'15%',
					'Rp. '.$biaya,
					true
				),
				'Edit'	=> array(
					'center',
					'10%',
					edit_button($edit),
					false
				),
				'Simpan'	=> array(
					'center',
					'10%',
					empty($resi)?edit_button($save):_click_button($send),
					false
				),
			);

			if(self::$type=='send_1'){
				unset($data['table'][$key]['td']['Simpan']);
			}else{
				unset($data['table'][$key]['td']['Edit']);
			}
		}
		
		return $data;
	}

	private static function head_title(){
		$args = array(
			'title'	=> 'Pengiriman <small>data pengiriman</small>',
			'link'	=> array(
				0	=> array(
					'func'	=> self::$object,
					'label'	=> 'pengiriman'
				)
			),
			'date'	=> false
		); 
		
		return $args;
	}

	protected static function get_box(){
		$data = self::table();
		
		$box = array(
			'label'		=> 'Data pengirman',
			'tool'		=> '',
			'action'	=> '',
			'func'		=> 'sobad_table',
			'data'		=> $data
		);

		return $box;
	}

	protected static function layout(){
		self::$type = 'send_3';
		$box = self::get_box();

		$object = self::$table;
		$tabs = array(
			'tab'	=> array(
				0		=> array(
					'key'	=> 'send_3',
					'label'	=> 'Pending',
					'qty'	=> $object::count("var IN ('order','non_order') AND status='3'")
				),
				array(
					'key'	=> 'send_1',
					'label'	=> 'Terkirim',
					'qty'	=> $object::count("var IN ('order','non_order') AND status='1'")
				),
			),
			'func'	=> '_portlet',
			'data'	=> $box
		);

		$opt = array(
			'title'		=> self::head_title(),
			'style'		=> array(),
			'script'	=> array()
		);
		
		return tabs_admin($opt,$tabs);
	}

	// ----------------------------------------------------------
	// Form data Pengiriman -------------------------------------
	// ----------------------------------------------------------

	public static function save_form($id=0){
		$id = str_replace('save_','',$id);
		intval($id);
		
		$args = static::_array();
		self::$type = isset($_POST['type'])?$_POST['type']:'';

		$post = '';
		if(property_exists(new static, 'post')){
			$post = static::$post;
		}

		$object = static::$table;
		$vals = $object::get_id($id,$args,'',$post);
		$vals = $vals[0];
		
		$args = array(
			'title'		=> 'Tambah data resi',
			'button'	=> '_btn_modal_save',
			'status'	=> array(
				'link'		=> '_save_resi',
				'load'		=> 'sobad_portlet',
				'type'		=> 'send_3'
			)
		);
		
		return self::_data_form($args,$vals);
	}

	protected static function edit_form($vals=array()){
		$check = array_filter($vals);
		if(empty($check)){
			return '';
		}
		
		$args = array(
			'title'		=> 'Edit data resi',
			'button'	=> '_btn_modal_save',
			'status'	=> array(
				'link'		=> '_update_db',
				'load'		=> 'sobad_portlet',
				'type'		=> 'send_1'
			)
		);
		
		return self::_data_form($args,$vals);
	}

	private static function _data_form($args=array(),$vals=array()){
		$check = array_filter($args);
		if(empty($check)){
			return '';
		}

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

		// GET saldo akun
		if($vals['_payment_method']==0){
			reset($payment);
			$vals['_payment_method'] = key($payment);
		}

		$saldo = sobad_account::get_id($vals['_payment_method'],array('balance'));
		$saldo = $saldo[0]['balance'];

		// GET Kurir
		$courier = $vals['_expedition'];
		$comp = sobad_company::get_id($courier,array('name'));
			
		$check = array_filter($comp);
		if(!empty($check)){
			$courier = $comp[0]['name'];
		}

		$data = array(
			0 => array(
				'func'			=> 'opt_hidden',
				'type'			=> 'hidden',
				'key'			=> 'ID',
				'value'			=> $vals['ID']
			),
			array(
				'func'			=> 'opt_hidden',
				'type'			=> 'hidden',
				'key'			=> 'title',
				'value'			=> $vals['title']
			),
			array(
				'func'			=> 'opt_input',
				'type'			=> 'text',
				'key'			=> 'order',
				'label'			=> 'No Order',
				'class'			=> 'input-circle',
				'value'			=> transaksi_retail::_post_title($vals['title'],$vals['meta_note_type'],$vals['inserted']),
				'data'			=> 'placeholder="No Order" readonly'
			),
			array(
				'func'			=> 'opt_input',
				'type'			=> 'text',
				'key'			=> 'name_cont',
				'label'			=> 'Customer',
				'class'			=> 'input-circle',
				'value'			=> $vals['name_cont'],
				'data'			=> 'placeholder="Customer" readonly'
			),
			array(
				'func'			=> 'opt_input',
				'type'			=> 'text',
				'key'			=> '_expedition',
				'label'			=> 'Kurir',
				'class'			=> 'input-circle',
				'value'			=> $courier,
				'data'			=> 'placeholder="Kurir" readonly'
			),
			array(
				'func'			=> 'opt_input',
				'type'			=> 'text',
				'key'			=> '_resi',
				'label'			=> 'No Resi',
				'class'			=> 'input-circle',
				'value'			=> $vals['_resi'],
				'data'			=> 'placeholder="No resi"'
			),
			array(
				'func'			=> 'opt_select',
				'data'			=> $payment,
				'key'			=> '_payment_method',
				'label'			=> 'Akun',
				'class'			=> 'input-circle',
				'select'		=> $vals['_payment_method'],
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
				'type'			=> 'price',
				'key'			=> '_shipping_price',
				'label'			=> 'Biaya',
				'class'			=> 'input-circle',
				'value'			=> format_nominal($vals['_shipping_price']),
				'data'			=> 'placeholder="Biaya"'
			),
		);
		
		$args['func'] = array('sobad_form');
		$args['data'] = array($data);
		
		return modal_admin($args);
	}

	public static function set_balance_akun($id=0){
		return transaksi_retail::set_balance_akun($id);
	}

	// ------------------------------------------------------------
	// ----- View Table Item --------------------------------------
	// ------------------------------------------------------------	

	public static function _view($id=0){
		$id = str_replace('view_', '', $id);
		$table = self::_table_ongkir($id);

		$args = array(
			'title'		=> 'View data Ongkir',
			'button'	=> '',
			'status'	=> array(
				'link'		=> '',
				'load'		=> 'sobad_portlet'
			)
		);

		$args['func'] = array('sobad_table');
		$args['data'] = array($table);
		
		return modal_admin($args);
	}

	protected static function _table_ongkir($id=0){
		$data = array();
		$data['class'] = '';
		$data['table'] = array();

		$args = sobad_post::get_id($id,array('title','type','inserted','_expedition','_resi','_shipping_price'),"",'order');

		foreach ($args as $key => $val) {
			$resi = $val['_resi'];$biaya = format_nominal($val['_shipping_price']);

			$courier = $val['_expedition'];
			$comp = sobad_company::get_id($courier,array('name'));
			
			$check = array_filter($comp);
			if(!empty($check)){
				$courier = $comp[0]['name'];
			}
			
			$data['table'][$key]['tr'] = array('');
			$data['table'][$key]['td'] = array(
				'No. Order'	=> array(
					'left',
					'15%',
					transaksi_retail::_post_title($val['title'],$val['meta_note_type'],$val['inserted']),
					true
				),
				'Kurir'		=> array(
					'left',
					'20%',
					$courier,
					true
				),
				'No. Resi'	=> array(
					'left',
					'20%',
					$resi,
					true
				),
				'Biaya'		=> array(
					'left',
					'15%',
					'Rp. '.$biaya,
					true
				),
			);
		}

		return $data;
	}

	// ----------------------------------------------------------
	// Function to database -------------------------------------
	// ----------------------------------------------------------

	public static function _send_order($id=0){
		$id = str_replace('send_', '', $id);
		intval($id);

		$q = sobad_db::_update_single($id,''. base .'post',array('status' => 1, 'updated' => date('Y-m-d H:i:s')));

		$args = sobad_post::get_id($id,array('ID','payment'),'','order');
		$args = $args[0];

		// Get Total Belanja
		$price = 0;
		$produk = sobad_post::get_transactions($id,array('price','qty','discount'));
		foreach ($produk as $key => $val) {
			$harga = $val['price'];
			$discount = $val['discount']<=100?$harga * ($val['discount']/100) : $val['discount'];
			$discount = round($discount,0);

			$harga = ($harga - $discount) * $val['qty'];
			$price += $harga;
		}

		// Get Total Pembayaran
		$cost = sobad_post::get_all(array('ID','price'),"AND reff='$id' AND var='paid'",'paid');
		$cost = $cost[0]['price'];

		// Total cost shipping
		$shipping = $cost - $price;
		$shipping = $shipping<=0?0:$shipping;

		$ajax = array(
			array(
				'name'		=> '_payment_method',
				'value'		=> sobad_asset::ascii_to_hexa($args['payment'])
			),
			array(
				'name'		=> '_shipping_price',
				'value'		=> sobad_asset::ascii_to_hexa($shipping)
			)
		);

		$ajax = json_encode($ajax);
		self::_add_detail(array('index' => $id),$ajax);

		// Update Meta
		sobad_db::_insert_table(''. base .'post-meta',array(
			'meta_key'	 => '_payment_method',
			'meta_value' => $args['payment'],
			'meta_id'	 => $id
		));

		sobad_db::_update_multiple("meta_id='$id' AND meta_key='_shipping_price'",''. base .'post-meta',array(
			'meta_value' => $shipping,
			'meta_id'	 => $id
		));

		if($q!==0){
			$pg = isset($_POST['page'])?$_POST['page']:1;
			return self::_get_table($pg);
		}
	}

	public static function _save_resi($_args=array()){
		$args = self::_schema($_args,false);
		$q = $args['data'];
		$src = $args['search'];

		$q = self::_add_detail($args,$_args);

		if($q!==0){
			$pg = isset($_POST['page'])?$_POST['page']:1;
			return self::_get_table($pg,$src);
		}
	}

	public static function _callback($args=array()){
		if(empty($args['_resi'])){
			die(_error::_alert_db('No Resi Tidak boleh kosong!!!'));
		}

		// Reset Saldo
		if($args['ID']>0){
			$idx = $args['ID'];
			$pays = sobad_post::get_all(array('ID','payment','id_join','price'),"AND `". base ."post`.var='pay' AND `". base ."post`.reff='$idx'",'pay');

			$check = array_filter($pays);
			if(!empty($check) && $pays[0]['payment']>0){
				$akun = sobad_account::get_id($pays[0]['payment'],array('balance'));
				$akun = $akun[0];

				$saldo = $akun['balance'] + $pays[0]['price'];
				sobad_db::_update_single($pays[0]['payment'],''. base .'account',array('ID' => $pays[0]['payment'], 'balance' => $saldo));
			}
		}

		// Pengurangan Saldo
		$akun = sobad_account::get_id($args['_payment_method'],array('balance'));
		$akun = $akun[0];

		$saldo = $akun['balance'] - $args['_shipping_price'];
		sobad_db::_update_single($args['_payment_method'],''. base .'account',array('ID' => $args['_payment_method'], 'balance' => $saldo));

		// Update Pembayaran
		if($args['ID']>0){
			$args['updated'] = date('Y-m-d H:i:s');

			if(!empty($check)){
				sobad_db::_update_single($pays[0]['ID'],''. base .'post',array('payment' => $args['_payment_method'], 'updated' => date('Y-m-d H:i:s')));
				sobad_db::_update_single($pays[0]['id_join'],''. base .'transaksi',array('ID' => $pays[0]['id_join'], 'price' => $args['_shipping_price']));
			}else{
				$ajax = array(
					array(
						'name'		=> '_payment_method',
						'value'		=> sobad_asset::ascii_to_hexa($args['_payment_method'])
					),
					array(
						'name'		=> '_shipping_price',
						'value'		=> sobad_asset::ascii_to_hexa($args['_shipping_price'])
					)
				);

				$ajax = json_encode($ajax);
				self::_add_detail(array('index' => $args['ID']),$ajax);
			}
		}else{
			$args['updated'] = '0000-00-00 00:00:00';
		}

		$args['status'] = 1;
		return $args;
	}

	protected static function _add_detail($data=array(),$args=array()){
		$idx = $data['index'];
		$args = sobad_asset::ajax_conv_json($args);

		// Insert Post Pembayaran
		$no = quotation_marketing::_get_max('pay');
		$p = sobad_db::_insert_table(''. base .'post',array(
			'title'			=> $no + 1,
			'user'			=> get_id_user(),
			'payment'		=> $args['_payment_method'],
			'post_date'		=> date('Y-m-d'),
			'updated'		=> '0000-00-00 00:00:00',
			'var'			=> 'pay',
			'status'		=> 1,
			'reff'			=> $idx
		));

		// Add Detail pembayaran
		sobad_db::_insert_table(''. base .'transaksi',array(
			'post'		=> $p,
			'qty'		=> 1,
			'price'		=> $args['_shipping_price'],
			'unit'		=> 'IDR'
		));

		return $p;
	}
}