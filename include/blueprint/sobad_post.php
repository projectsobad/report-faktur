<?php

class sobad_post extends _class{
	public static $table = ''. base .'post';

	public static $tbl_join = ''. base .'transaksi';

	public static $tbl_meta = ''. base .'post-meta';

	protected static $join = "joined.post ";

	protected static $group = " GROUP BY `". base ."post-meta`.meta_id";

	protected static $list_meta = array();

	public static function set_listmeta(){
		$type = parent::$_type;
		
		switch ($type) {
			case 'non_order':
			case 'order':
				self::$list_meta = array(
					'_shipping_price','_discount','_resi','_expedition','_payment_method'
				);
				break;

			case 'purchase':
				self::$list_meta = array(
					'_total','_no_note','_project','_moneyG','_brand','_discount','_due_date','_no_faktur','_mode_ppn','_ppn','_shipping_price'
				);
				break;	

			case 'invoice_purchase':
				self::$list_meta = array(
					'_total','_no_note','_no_faktur','_due_date','_mode_ppn','_ppn'
				);
				break;		

			case 'inquiry':
				self::$list_meta = array(
					'_quotation','_estimate','_note','_discount','_due_date','_mode_ppn','_ppn','_shipping_price','_total'
				);
				break;

			case 'quotation':
			case 'change_quotation':
				self::$list_meta = array(
					'_shipping_price','_discount','_estimate','_payment_method','_note','_description','_ppn','_ppn_status','_type_discount','_currency','_no_faktur'
				);
				break;

			case 'project':
				self::$list_meta = array(
					'_po_number',
					'_note',
					'_project',
					'_due_date',
					'_mail_type',
					'_type_reff',
					'_reff',
					'_order_status_ppic'
				);
				break;

			case 'delivery_order':
				self::$list_meta = array(
					'_courier','_resi','_note'
				);
				break;

			case 'invoice':
				self::$list_meta = array(
					'_nominal','_due_date','_term_payment','_detail','_do_number','_no_faktur','_note'
				);
				break;

			case 'form':
				self::$list_meta = array(
					'_due_date',
					'_referensi',
					'_part_stock'
				);
				break;

			case 'moneyG':
				self::$list_meta = array(
					'_total','_saldo'
				);
				break;

			case 'custom_design':
				self::$list_meta = array(
					'_template','_style','_paper_size','_dpi','_design'
				);
				break;	
			
			default:
				self::$list_meta = array();
				break;
		}

	}

	public static function blueprint($type='post'){
		self::set_listmeta();

		$args = array(
			'type'		=> $type,
			'table'		=> self::$table,
		);

		$detail = array(
			'company'	=> array(
				'key'		=> 'ID',
				'table'		=> ''. base .'company',
				'column'	=> array('name','phone_no')
			),
			'contact'	=> array(
				'key'		=> 'ID',
				'table'		=> ''. base .'company',
				'column'	=> array('name','phone_no')
			),
			'type'	=> array(
				'key'		=> 'ID',
				'table'		=> ''. base .'meta',
				'column'	=> array('meta_value','meta_note')
			)
		);

		$joined = array(
			'key'		=> 'post',
			'table'		=> self::$tbl_join,
			'detail'	=> array(
				'barang'	=> array(
					'key'		=> 'ID',
					'table'		=> ''. base .'item',
					'column'	=> array('name','product_code','picture','price','stock','var')
				)
			)
		);

		$meta = array(
			'key'		=> 'meta_id',
			'table'		=> self::$tbl_meta
		);

		switch ($type) {
			case 'non_order':
			case 'order':
				$args['detail'] = $detail;
				$args['detail']['payment'] = array(
					'key'		=> 'ID',
					'table'		=> ''. base .'account',
					'column'	=> array('name','bank','balance'),
				);

				$args['joined'] = $joined;
				$args['meta'] = $meta;

				break;

			case 'pay':
			case 'paid':
				$args['detail'] = array(
					'payment'	=> array(
						'key'		=> 'ID',
						'table'		=> ''. base .'account',
						'column'	=> array('name','bank','balance'),
					),
					'reff'	=> array(
						'key'		=> 'ID',
						'table'		=> ''. base .'post',
						'column'	=> array('title','company','contact','inserted','type','var'),
						'detail'	=> $detail
					)
				);

				$args['joined'] = $joined;

				unset($args['detail']['reff']['detail']['type']);
				break;

			case 'interest_bank':
			case 'cost_bank':
			case 'cash_in':
			case 'cash_out':
			case 'cashflow':
				$args['detail'] = array(
					'payment'	=> array(
						'key'		=> 'ID',
						'table'		=> ''. base .'account',
						'column'	=> array('name','bank')
					),
					'company'	=> array(
						'key'		=> 'ID',
						'table'		=> ''. base .'company',
						'column'	=> array('name','phone_no')
					),
					'reff'		=> array(
						'key'		=> 'ID',
						'table'		=> ''. base .'post',
						'column'	=> array('title','company','payment','type','post_date','inserted','var','reff'),
					)
				);
				$args['joined'] = $joined;
				break;

			case 'form':
				$args['meta'] = $meta;

			case 'inquiry_addstock':
			case 'inquiry_reducestock':
				$args['detail'] = $detail;
				$args['joined'] = $joined;

				unset($args['detail']['company']);
				unset($args['detail']['type']);
				break;

			case 'add_stock':
			case 'reduce_stock':
				$args['detail'] = $detail;
				$args['joined'] = $joined;

				$args['detail']['reff'] = array(
					'key'		=> 'ID',
					'table'		=> ''. base .'post',
					'column'	=> array('title','post_date','inserted','var','reff'),
				);

				unset($args['detail']['company']);
				unset($args['detail']['type']);
				break;

			case 'change_quotation':
			case 'quotation':
			case 'purchase':
				$args['detail'] = $detail;
				$args['joined'] = $joined;
				$args['meta'] = $meta;

				$args['detail']['payment'] = array(
					'key'		=> 'ID',
					'table'		=> ''. base .'account',
					'column'	=> array('name','bank')
				);

				unset($args['detail']['type']);
				break;

			case 'inquiry':
				$args['detail'] = array(
					'company'	=> array(
						'key'		=> 'ID',
						'table'		=> base .'company',
						'column'	=> array('name','phone_no')
					),
					'contact'	=> array(
						'key'		=> 'ID',
						'table'		=> base .'company',
						'column'	=> array('name','phone_no')
					),
					'reff'	=> array(
						'key'		=> 'ID',
						'table'		=> base.'post',
						'column'	=> array('title','contact','inserted','post_date','notes'),
					)
				);

				$args['joined'] = $joined;
				$args['meta'] = $meta;
				break;

			case 'invoice_purchase':
				$args['detail'] = array(
					'payment'	=> array(
						'key'		=> 'ID',
						'table'		=> ''. base .'account',
						'column'	=> array('name','bank','balance'),
					),
					'reff'	=> array(
						'key'		=> 'ID',
						'table'		=> base.'post',
						'column'	=> array('title','company','contact','inserted','post_date'),
						'detail'	=> array(
							'company'	=> array(
								'key'		=> 'ID',
								'table'		=> base .'company',
								'column'	=> array('name','phone_no')
							),
							'contact'	=> array(
								'key'		=> 'ID',
								'table'		=> base .'company',
								'column'	=> array('name','phone_no')
							)
						)
					)
				);

				$args['meta'] = $meta;
				break;

			case 'packing_stock':
				$args['detail'] = array(
					'reff'	=> array(
						'key'		=> 'ID',
						'table'		=> base.'post',
						'column'	=> array('title','contact','type','inserted','post_date'),
						'detail'	=> array(
							'contact'	=> array(
								'key'		=> 'ID',
								'table'		=> base .'company',
								'column'	=> array('name','phone_no')
							),
							'type'	=> array(
								'key'		=> 'ID',
								'table'		=> base .'meta',
								'column'	=> array('meta_value','meta_note')
							)
						)
					)
				);

				$args['meta'] = $meta;
				break;

			case 'project':
			case 'invoice':
			case 'delivery_order':
			case 'receipt_item':
				$args['detail'] = $detail;
				$args['joined'] = $joined;
				$args['meta'] = $meta;

				$args['detail']['reff'] = array(
					'key'		=> 'ID',
					'table'		=> ''. base .'post',
					'column'	=> array('title','user','company','contact','post_date','reff','type','inserted')
				);

				unset($args['detail']['type']);
				break;

			case 'transaction':
				$args['detail'] = array(
					'barang'	=> array(
						'key'		=> 'ID',
						'table'		=> ''. base .'item',
						'column'	=> array('name','part_id','product_code','picture','price','satuan','stock','type','var')
					),
					'post'		=> array(
						'key'		=> 'ID',
						'table'		=> ''. base .'post',
						'column'	=> array('company','contact','post_date','title','inserted','var')
					),
				);
				break;

			case 'moneyG':
				case 'paid':
				$args['detail'] = array(
					'payment'	=> array(
						'key'		=> 'ID',
						'table'		=> ''. base .'account',
						'column'	=> array('name','bank','balance'),
					)
				);

				$args['meta'] = $meta;
				break;

			case 'order_project':
				$args['detail'] = array(
					'notes'		=> array(
						'key'		=> 'ID',
						'table'		=> ''. base .'item',
						'column'	=> array('name','var')
					),
					'reff'		=> array(
						'key'		=> 'ID',
						'table'		=> ''. base .'post',
						'column'	=> array('title','post_date','reff','inserted','status')
					)
				);

				$args['joined'] = array(
					'key'		=> 'post',
					'table'		=> self::$tbl_join,
					'detail'	=> array(
						'barang'	=> array(
							'key'		=> 'ID',
							'table'		=> ''. base .'item',
							'column'	=> array('name','product_code','part_id','picture','price','stock','var')
						)
					)
				);

				break;

			case 'custom_design':
				$args['detail'] = array(
					'reff'	=> array(
						'key'		=> 'ID',
						'table'		=> ''. base .'item',
						'column'	=> array('name','product_code','type','var')
					)
				);

				$args['meta'] = $meta;
				break;	

			case 'envelope':
				$args['detail'] = $detail;
				break;

			case 'track_history':
				$args['detail'] = array(
					'reff'	=> array(
						'key'		=> 'ID',
						'table'		=> ''. base .'post',
						'column'	=> array('title','post_date','reff','inserted')
					)
				);
				break;	

			default:
				# code...
				break;
		}

		return $args;
	}

	public static function _check_referensi($reff=0,$var=''){
		$var = empty($var)?'':"AND `".base."post`.var='$var'";
		$where = "AND reff='$reff' $var";

		$check = self::get_all(array('ID'),$where);
		$check = array_filter($check);

		if(empty($check)){
			return false;
		}

		return true;
	}

	public static function get_max($column='ID',$limit=''){
		$args = array("MAX($column) as $column");
		$where = "WHERE 1=1 $limit";
		return parent::_get_data($where,$args);
	}
	
	public static function get_images($args=array(),$limit=''){
		$args = parent::_check_array($args);
		
		$where = "WHERE `". base ."post`.var='image' $limit";
		return parent::_get_data($where,$args,'image');
	}

	public static function get_cashIns($args=array(),$limit=''){
		$args = parent::_check_array($args);
		
		$where = "WHERE `". base ."post`.var IN ('paid','cash_in','interest_bank') AND `". base ."post`.trash='0' $limit";
		return parent::_check_join($where,$args,'cash_in');
	}

	public static function get_cashOuts($args=array(),$limit=''){
		$args = parent::_check_array($args);
		
		$where = "WHERE `". base ."post`.var IN ('pay','cash_out','cost_bank') AND `". base ."post`.trash='0' $limit";
		return parent::_check_join($where,$args,'cash_out');
	}

	public static function get_costBanks($args=array(),$limit=''){
		$args = parent::_check_array($args);
		
		$where = "WHERE `". base ."post`.var='cost_bank' AND `". base ."post`.trash='0' $limit";
		return parent::_check_join($where,$args,'cost_bank');
	}

	public static function get_interestBanks($args=array(),$limit=''){
		$args = parent::_check_array($args);
		
		$where = "WHERE `". base ."post`.var='interest_bank' AND `". base ."post`.trash='0' $limit";
		return parent::_check_join($where,$args,'interest_bank');
	}

	public static function get_cashflows($args=array(),$limit=''){
		$args = parent::_check_array($args);
		
		$where = "WHERE `". base ."post`.var IN ('paid','cash_in','interest_bank','pay','cash_out','cost_bank') AND `". base ."post`.trash='0' $limit";
		return parent::_check_join($where,$args,'cashflow');
	}
// ------------------------------------------------------------
// ---- Function Penawaran ------------------------------------
// ------------------------------------------------------------	
	
	public static function get_quotations($args=array(),$limit=''){		
		$where = "WHERE `". base ."post`.var='quotation' $limit";
		
		return parent::_check_join($where,$args,'quotation');
	}

// ------------------------------------------------------------
// ---- Function Project --------------------------------------
// ------------------------------------------------------------	
	
	public static function get_project($args=array(),$limit=''){		
		$where = "WHERE `". base ."post`.var='project' $limit";
		
		return parent::_check_join($where,$args,'project');
	}

	public static function get_allProject($args=array(),$limit=''){		
		$where = "WHERE `". base ."post`.var IN ('project','internal_project') $limit";
		
		return parent::_check_join($where,$args,'project');
	}

	public static function get_projects($args=array(),$limit=''){		
		$where = "WHERE `". base ."post`.var='project' $limit";
		
		return parent::_check_join($where,$args,'project');
	}	

	public static function get_internalProjects($args=array(),$limit=''){		
		$where = "WHERE `". base ."post`.var='internal_project' $limit";
		
		return parent::_check_join($where,$args,'project');
	}	

// ------------------------------------------------------------
// ---- Function Invoice --------------------------------------
// ------------------------------------------------------------	
	
	public static function get_invoices($args=array(),$limit=''){		
		$where = "WHERE `". base ."post`.var='invoice' $limit";
		
		return parent::_check_join($where,$args,'invoice');
	}

// ------------------------------------------------------------
// ---- Function Surat Jalan ----------------------------------
// ------------------------------------------------------------	
	
	public static function get_delivery_orders($args=array(),$limit=''){		
		$where = "WHERE `". base ."post`.var='delivery_order' $limit";
		
		return parent::_check_join($where,$args,'delivery_order');
	}

// ------------------------------------------------------------
// ---- Function Order Project --------------------------------
// ------------------------------------------------------------	
	
	public static function get_project_orders($args=array(),$limit=''){		
		$where = "WHERE `". base ."post`.var='order_project' $limit";
		
		return parent::_check_join($where,$args,'order_project');
	}	

// ------------------------------------------------------------
// ---- Function Inquiry Stock --------------------------------
// ------------------------------------------------------------	
	
	public static function get_inquiryAddStocks($args=array(),$limit=''){		
		$where = "WHERE `". base ."post`.var='inquiry_addstock' $limit";
		
		return parent::_check_join($where,$args,'inquiry_addstock');
	}	

	public static function get_formInquiryAdds($args=array(),$limit=''){		
		$where = "WHERE `". base ."post`.var='form_inquiry_add' $limit";
		
		return parent::_check_join($where,$args);
	}	

	public static function get_inquiryReduceStocks($args=array(),$limit=''){		
		$where = "WHERE `". base ."post`.var='inquiry_reducestock' $limit";
		
		return parent::_check_join($where,$args,'inquiry_reducestock');
	}	
	
// ------------------------------------------------------------
// ---- Function Selling atau Penjualan -----------------------
// ------------------------------------------------------------
	
	public static function get_orders($args=array(),$limit=''){
		$where = " WHERE `". base ."post`.var='order' $limit";
		
		return parent::_check_join($where,$args,'order');
	}

	public static function get_orders_by_category($id=0,$args=array()){
		$product = sobad_item::get_all(array('ID'),"AND category='$id'");

		$check = array_filter($product);
		if(empty($check)){
			return array();
		}

		$result = array();
		foreach ($product as $key => $val) {
			$result[] = $val['ID'];
		}

		$result = implode(',', $result);
		$result = empty($result)?0:$result;
		$where = "AND barang IN ($result)";

		//Get order by product
		if(!empty($check)){
			if(!in_array('barang',$args)){
				$args[] = 'barang';
			}

			if(!in_array('qty',$args)){
				$args[] = 'qty';
			}
		}

		return self::get_orders($args,$where);
	}

// ------------------------------------------------------------
// ---- Function Purchase -------------------------------------
// ------------------------------------------------------------	
	
	public static function get_purchases($args=array(),$limit=''){
		$where = "WHERE `". base ."post`.var='purchase' $limit";
		
		return parent::_check_join($where,$args,'purchase');
	}

	public static function get_detail_purchase($id=0,$args=array(),$limit=''){	
		self::$table = ''. base .'transaksi';	
		$where = "WHERE post='$id' $limit";

		$args = parent::_check_array($args);
		$data = parent::_get_data($where,$args);

		self::$table = ''. base .'post';
		return $data;
	}

// ------------------------------------------------------------
// ---- Function Transaction -------------------------------------
// ------------------------------------------------------------	

	public static function get_transaction($id=0,$args=array(),$limit=''){	
		self::$table = ''. base .'transaksi';	
		$where = "WHERE `". base ."transaksi`.ID='$id' $limit";
		$data = parent::_check_join($where,$args,'transaction');

		self::$table = ''. base .'post';
		return $data;
	}

	public static function get_transactions($id=0,$args=array(),$limit=''){	
		self::$table = ''. base .'transaksi';	
		$where = "WHERE `". base ."transaksi`.post='$id' $limit";
		$data = parent::_check_join($where,$args,'transaction');

		self::$table = ''. base .'post';
		return $data;
	}

	public static function get_transactions_key($id=0,$args=array(),$limit=''){	
		self::$table = ''. base .'transaksi';	
		$where = "WHERE `". base ."transaksi`.keyword='$id' $limit";
		$data = parent::_check_join($where,$args,'transaction');

		self::$table = ''. base .'post';
		return $data;
	}

// ------------------------------------------------------------
// ---- Function Post Meta -------------------------------------
// ------------------------------------------------------------	

	public static function get_meta($id=0,$key=''){	
		self::$table = ''. base .'post-meta';
		$where = "WHERE meta_id='$id' AND meta_key='$key'";
		$data = parent::_get_data($where,array('ID','meta_key','meta_value'));

		self::$table = ''. base .'post';
		return $data;
	}

	public static function get_metas($id=0){	
		self::$table = ''. base .'post-meta';
		$where = "WHERE meta_id IN ($id)";
		$data = parent::_get_data($where,array('meta_id','meta_key','meta_value'));

		self::$table = ''. base .'post';
		return $data;
	}

// ------------------------------------------------------------
// ---- Function Faktur ---------------------------------------
// ------------------------------------------------------------	
	public static function get_fakturPurchase($args=array(),$post='',$limit=''){
		$where = "WHERE `". base ."post`.var='$post' AND (meta_key='_no_faktur' AND meta_value!='') $limit";
		
		return parent::_check_join($where,$args,$post);
	}

// ------------------------------------------------------------
// ---- Function Care Building --------------------------------
// ------------------------------------------------------------	
	public static function get_careBuildings($args=array(),$limit=''){		
		$where = "WHERE `". base ."post`.var='care_building' $limit";
		
		return parent::_check_join($where,$args);
	}

// ------------------------------------------------------------
// ---- Function Dashboard Malika -----------------------------
// ------------------------------------------------------------	

	public static function _get_paid($limit=''){
		$args = array('COUNT(ID) as cnt');
		$where = "WHERE var='order' AND reff='1' $limit";
		return self::_get_data($where,$args);
	}

	public static function _wait_shipping($limit=''){
		$args = array('ID','title','inserted','type','contact','_expedition');
		$where = "AND `". base ."post`.var='order' AND `". base ."post`.status='3' $limit";
		$data = self::get_all($args,$where,'order');

		foreach ($data as $key => $val) {
			$courier = $val['_expedition'];
			$comp = sobad_company::get_id($courier,array('name'));
			
			$check = array_filter($comp);
			if(!empty($check)){
				$courier = $comp[0]['name'];
			}

			$data[$key]['channel'] = $val['meta_note_type'].$val['title'];
			$data[$key]['courier'] = $courier;
		}

		return $data; 
	}

	private static function _get_orders($type='daily',$args=array(),$limit='',$date=''){
		$date = empty($date)?date('Y-m-d'):$date;
		$date = strtotime($date);

		$now = date('Y-m-d',$date);
		$day = date('d',$date);
		$month = date('m',$date);
		$year = date('Y',$date);

		switch ($type) {
			case 'monthly':
				$time = "AND (YEAR(`". base ."post`.post_date)='$year' AND MONTH(`". base ."post`.post_date)='$month')";
				break;

			case 'yearly':
				$time = "AND YEAR(`". base ."post`.post_date)='$year'";
				break;
			
			default:
				$time = "AND `". base ."post`.post_date='$now'";
				break;
		}

		$where = "AND `". base ."post`.var='order' $time $limit";
		return self::get_all($args,$where,'order');
	}

	public static function _get_order_orders($type='daily',$limit='',$date=''){
		$args = array('ID','type');
		$data = self::_get_orders($type,$args,$limit,$date);

		$channel = array();$total = 0;
		foreach ($data as $key => $val) {
			if(!isset($channel[$val['type']])){
				$channel[$val['type']] = array(
					'name'		=> $val['meta_value_type'],
					'total'		=> 0
				);
			}

			$total += 1;
			$channel[$val['type']]['total'] += 1;
		}

		return array(
			'total'		=> $total,
			'data'		=> $channel
		);
	}

	public static function _get_product_orders($type='daily',$limit='',$date=''){
		$args = array('ID','type','unit','qty');
		$data = self::_get_orders($type,$args,$limit,$date);

		$channel = array();$total = 0;
		foreach ($data as $key => $val) {
			if(!isset($channel[$val['type']])){
				$channel[$val['type']] = array(
					'name'		=> $val['meta_value_type'],
					'total'		=> 0
				);
			}

			$total += $val['qty'];
			$channel[$val['type']]['total'] += $val['qty'];
		}

		return array(
			'total'		=> $total,
			'data'		=> $channel
		);
	}

	public static function _get_sale_orders($type='daily',$limit='',$date=''){
		$args = array('ID','type','unit','qty','price','discount');
		$data = self::_get_orders($type,$args,$limit,$date);

		$channel = array();$total = 0;
		foreach ($data as $key => $val) {
			if(!isset($channel[$val['type']])){
				$channel[$val['type']] = array(
					'name'		=> $val['meta_value_type'],
					'total'		=> 0
				);
			}

			$sub = $val['qty'] * ($val['price'] - $val['discount']);

			$total += $sub;
			$channel[$val['type']]['total'] += $sub;
		}

		return array(
			'total'		=> $total,
			'data'		=> $channel
		);
	}

	public static function _best_seller($type='monthly',$max=10){
		$args = array('ID','barang','qty');
		$data = self::_get_orders($type,$args);

		$total = array();$item = array();
		foreach ($data as $key => $val) {
			$idx = $val['barang'];
			if(!isset($total[$idx])){
				$total[$idx] = 0;
			}

			$total[$idx] += (int) $val['qty'];

			// data barang
			if(!isset($item[$idx])){
				$item[$idx] = array();
			}

			$item[$idx] = $val;
		}

		arsort($total);

		$_data = array();
		foreach ($total as $key => $val) {
			$max -= 1;
			$item[$key]['qty'] = $val;
			$_data[] = $item[$key];

			if($max<0) break;
		}

		return $_data;
	}

	public static function _best_market($type='monthly',$max=10){
		$args = array('ID','type');
		$data = self::_get_orders($type,$args);

		self::$table = ''. base .'transaksi';

		$total = array();$order = array();$market = array();
		foreach ($data as $key => $val) {
			$idx = $val['type'];
			$sub = 0;
			
			$q = self::get_transactions($val['ID'],array('ID','qty'));
			foreach ($q as $ky => $vl) {
				$sub += $vl['qty'];
			}

			if(!isset($total[$idx])) $total[$idx] = 0;

			$total[$idx] += $sub;

			// Get jumlah order
			if(!isset($order[$idx])) $order[$idx] = 0;

			$order[$idx] += 1;

			// get data market
			if(!isset($market[$idx])) $market[$idx] = array();

			$market[$idx] = $val;
			$market[$idx]['order'] = $order[$idx];
		}

		arsort($total);

		$_data = array();
		foreach ($total as $key => $val) {
			$max -= 1;
			$market[$key]['product'] = $val;
			$_data[] = $market[$key];

			if($max<0) break;
		}

		self::$table = ''. base .'post';
		return $_data;
	}

	public static function _product_unsell($type=61,$limit=''){
		$item = array();
		$q = sobad_item::get_products(array('ID','name','product_code','stock'),"AND type='$type'");
		foreach ($q as $key => $val) {
			$item[$val['ID']] = $val;
		}

		$order = self::_get_orders('yearly',array('ID','barang')," GROUP BY `". base ."transaksi`.barang");
		foreach ($order as $key => $val) {
			$idx = $val['barang'];
			if(isset($item[$idx])){
				unset($item[$idx]);
			}
		}

		return $item;
	}

// ------------------------------------------------------------
// ---- Function Dashboard KMI --------------------------------
// ------------------------------------------------------------	

	public static function _active_project($limit=''){
		$args = array('ID','title','inserted','company','contact','status');
		$where = "AND status IN (0,2) $limit";
		$data = self::get_projects($args,$where);

		$project = array();
		foreach ($data as $key => $val) {
			$project[$key] = array(
				'ID'			=> $val['ID'],
				'project'		=> project_marketing::_post_title($val['title'],$val['inserted']),
				'company'		=> $val['name_comp'],
				'customer'		=> $val['name_cont'],
				'status'		=> project_marketing::_conv_status($val['status'])
			);
		}

		return $project;
	}

	public static function _get_omset($type='monthly',$date='',$limit=''){
		$date = empty($date)?date('Y-m-d'):$date;
		$date = strtotime($date);

		$y = date('Y',$date);$m = date('m',$date);
		$where = "AND YEAR(`". base ."post`.post_date)='$y' ";
		if($type=='monthly'){
			$where .= "AND MONTH(`". base ."post`.post_date)='$m' ";
		}

		$args = array('ID','title','inserted','user','status','reff');
		$data = self::get_invoices($args,$where);

		$invoice = array();
		foreach ($data as $key => $val) {
			$project[$key] = array(
				'ID'			=> $val['ID'],
				'project'		=> project_marketing::_post_title($val['title'],$val['inserted']),
				'company'		=> $val['name_comp'],
				'customer'		=> $val['name_cont'],
				'status'		=> $val['status']==0?'':''
			);
		}

		return $invoice;
	}

}