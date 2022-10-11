<?php

class invoice_marketing extends _page
{

	protected static $admin = false;

	protected static $object = 'invoice_marketing';

	protected static $table = 'sobad_post';

	protected static $post = 'invoice';

	// ----------------------------------------------------------
	// Layout category  -----------------------------------------
	// ----------------------------------------------------------

	protected static function _array()
	{
		$args = array(
			'ID',
			'title',
			'reff',
			'company',
			'contact',
			'post_date',
			'inserted',
			'type',
			'_nominal',
			'_term_payment',
			'_due_date',
			'status',
			'_do_number',
			'user',
			'_no_faktur',
			'_note'
		);

		return $args;
	}

	public static function _array_table()
	{
		$args = array(
			'ID',
			'title',
			'reff',
			'company',
			'contact',
			'_note',
			'post_date',
			'inserted',
			'type',
			'_nominal',
			'status',
			'user',
			'_due_date'
		);

		return $args;
	}

	public static function _post_title($title = 0, $date = '')
	{
		$kode = _setting_nocetak();
		$kode = $kode[0];

		$no = sprintf("%04d", $title);
		$date = empty($date) ? date('Y-m-d') : $date;
		$date = strtotime($date);

		return $no . '/INV/' . $kode . '/' . date('m', $date) . '/' . date('y', $date);
	}


	public static function _filter_search($field = '', $search = '')
	{
		$kode = _setting_nocetak();
		$kode = $kode[0];

		if ($field == 'reff') {
			$table = '_reff.';
			return project_marketing::_query_filter_search($field, $search, 'reff', $table);
		}


		if ($field == 'title') {
			$table = '`' . base . 'post`.';
			$title = array('[0-9]{4}', 'INV', $kode, '[0-9]{2}', '^[a-zA-Z0-9]+$');
			return quotation_marketing::_query_filter_search($field, $search, 'title', $title, $table);
		}

		if ($field == 'company') {
			return "_company.name LIKE '%$search%'";
		}

		if ($field == 'contact') {
			return "_contact.name LIKE '%$search%'";
		}

		if (in_array($field, array('post_date', 'status', 'inserted', 'type', 'user'))) {
			return "`" . base . "post`." . $field . " LIKE '%$search%'";
		}
	}

	public static function table()
	{
		self::$admin = isset($_SESSION[_prefix . 'adminMarketing']) ? $_SESSION[_prefix . 'adminMarketing'] : false;

		$data = array();
		$args = self::_array_table();

		$start = intval(self::$page);
		$nLimit = intval(self::$limit);

		$type = str_replace('invoice_', '', self::$type);
		intval($type);

		if ($type != 9) {
			$where = $type == 0 ? "AND `" . base . "post`.status='0'" : "AND `" . base . "post`.type='$type' ";
			$where .= "AND `" . base . "post`.trash='0' ";
		} else {
			$where = "AND `" . base . "post`.trash='1'";
		}

		$kata = '';
		$_search = '';
		if (self::$search) {
			$src = self::like_search($args, $where);
			$cari = $src[0];
			$where = $src[0];
			$kata = $src[1];
			$_search = $src[2];
		} else {
			$cari = $where;
		}

		$limit = $type == 0 ? 'ORDER BY _due_date ' : 'ORDER BY `' . base . 'post`.inserted DESC LIMIT ' . intval(($start - 1) * $nLimit) . ',' . $nLimit;
		$where .= $limit;

		$object = self::$table;
		$sum_data = $type==0 ? 0 : $object::count("`" . base . "post`.var='invoice' " . $cari, $args, self::$post);
		$args = $object::get_invoices($args, $where);

		$data['class'] = '';
		$data['table'] = array();

		if($type!=0){
		// Searching
			$data['data'] = array('data' => $kata, 'value' => $_search, 'type' => self::$type);
			$data['search'] = array('Semua', 'no. Invoice', 'no. Project', 'Perusahaan', 'Customer', 'Note');

		// Pagination
			$data['page'] = array(
				'func'	=> '_pagination',
				'data'	=> array(
					'start'		=> $start,
					'qty'		=> $sum_data,
					'limit'		=> $nLimit,
					'type'		=> self::$type
				)
			);
		}

		$no = ($start - 1) * $nLimit;
		foreach ($args as $key => $val) {
			$no += 1;

			$stts = $val['type'] == 1 ? 'disabled' : '';
			$note = self::_conv_type($val['type']);

			$print = array(
				'ID'	=> 'preview_' . $val['ID'],
				'func'	=> '_preview',
				'color'	=> 'green',
				'icon'	=> 'fa fa-print',
				'label'	=> 'Invoice ID',
				'script' => 'sobad_button_pre(this)'
			);
			$print_en = array(
				'ID'	=> 'preview_' . $val['ID'],
				'func'	=> '_preview_en',
				'color'	=> 'green',
				'icon'	=> 'fa fa-print',
				'label'	=> 'Invoice EN',
				'script' => 'sobad_button_pre(this)'
			);

			$paid = array(
				'ID'	=> 'paid_' . $val['ID'],
				'func'	=> '_viewPaid',
				'color'	=> 'yellow',
				'icon'	=> 'fa fa-money',
				'label'	=> 'history',
				'type'	=> self::$type
			);

			$edit = array(
				'ID'	=> 'edit_' . $val['ID'],
				'func'	=> '_edit',
				'color'	=> 'blue',
				'icon'	=> 'fa fa-edit',
				'label'	=> 'edit',
				'type'	=> self::$type
			);

			$hapus = array(
				'ID'	=> 'trash_' . $val['ID'],
				'func'	=> '_trash',
				'color'	=> 'red',
				'icon'	=> 'fa fa-trash',
				'label'	=> 'hapus',
				'type'	=> self::$type
			);

			// $cetak = array(
			// 	'label'		=> 'Print',
			// 	'color'		=> 'default',
			// 	'button'	=> array(
			// 		print_button($print),
			// 		print_button($print_en),
			// 	)
			// );

			$button = array(
				'label'		=> 'action',
				'color'		=> 'default',
				'button'	=> array(
					print_button($print),
					print_button($print_en),
					_modal_button($paid),
					edit_button($edit),
					hapus_button($hapus),
				)
			);

			$button = self::$admin == true || $val['user'] == get_id_user() ? dropdown_button($button) : print_button($print) . print_button($print_en);

			$terima = array(
				'ID'	=> 'paid_' . $val['ID'],
				'func'	=> '_paid_form',
				'color'	=> 'green',
				'icon'	=> 'fa fa-money',
				'label'	=> 'bayar',
				'type'	=> self::$type
			);

			$terima = _modal_button($terima); //$val['user']==get_id_user()?_modal_button($terima):'';

			// Get name project
			$_project = sobad_post::get_id($val['reff'], array('_project'), '', 'project');
			$_project = isset($_project[0]) ? $_project[0]['_project'] : '';

			$_no_project = array(
				'ID'	=> 'view_' . $val['reff'],
				'func'	=> '_view',
				'color'	=> '',
				'icon'	=> '',
				'label'	=> project_marketing::_post_title($val['title_reff'],$val['inserted_reff'])
			);

			$bayar = self::_get_bayar($val['ID']);
			$tagihan = self::_get_tagihan($val);

			// Check Status
			$status = $val['status'] == 0 ? '#f94d53;' : '#26a69a;';
			$status = '<i class="fa fa-circle" style="color:' . $status . '"></i>';

			// Check type invoice dan get Pembayaran
			if (self::$type != 'invoice_0') {
				$idx = $val['ID'];
				// $payment = sobad_post::get_all(array('payment'), "AND reff='$idx' AND var='paid'", 'paid');
				$payment = sobad_post::get_all(array('payment'), "AND `" . base . "post`.reff='$idx' AND `" . base . "post`.var='paid'", 'paid');
				$check = array_filter($payment);

				$note = empty($check) ? '-' : account_purchase::_option_accounts($payment[0]['payment']);
			} else {
				$note = 'Invoice ' . $note;
			}

			$sales = '';
			if (self::$admin == true) {
				$_user = kmi_user::get_id($val['user'], array('name'));
				$check = array_filter($_user);
				if (!empty($check)) {
					$sales = $_user[0]['name'];
				}
			}

			$_no_po = sobad_post::get_id($val['reff'],array('ID','_po_number'),'','project');
			$_no_po = isset($_no_po[0]) ? $_no_po[0]['_po_number'] : '-';

			$data['table'][$key]['tr'] = array('');
			$data['table'][$key]['td'] = array(
				'Check'			=> array(
					'center',
					'5%',
					$val['ID'],
					false
				),
				'No'			=> array(
					'center',
					'5%',
					$no,
					true
				),
				'Company'		=> array(
					'left',
					'10%',
					!empty($val['name_comp']) ? $val['name_comp'] : $val['name_cont'],
					true
				),
				'No. Project'	=> array(
					'left',
					'10%',
					edit_button($_no_project),
					true
				),
				'Project'		=> array(
					'left',
					'10%',
					$_project,
					true
				),
				'No. PO'		=> array(
					'left',
					'10%',
					$_no_po,
					true
				),
				'No. Invoice'	=> array(
					'left',
					'auto',
					self::_post_title($val['title'], $val['inserted']),
					true
				),
				'Tagihan'	=> array(
					'right',
					'15%',
					'Rp. ' . format_nominal($tagihan),
					true
				),
				// 'Nominal'		=> array(
				// 	'right',
				// 	'15%',
				// 	'Rp. ' . format_nominal($nominal),
				// 	true
				// ),
				'Tanggal'		=> array(
					'left',
					'20%',
					format_date_id($val['post_date']),
					true
				),
				'Jatuh Tempo'		=> array(
					'left',
					'13%',
					format_date_id($val['_due_date']),
					true
				),
				// 'Keterangan'	=> array(
				// 	'left',
				// 	'13%',
				// 	'',
				// 	true
				// ),
				// 'Sales'		=> array(
				// 	'left',
				// 	'9%',
				// 	$sales,
				// 	true
				// ),
				'Status'		=> array(
					'center',
					'5%',
					$status,
					true
				),
				'Terima'			=> array(
					'center',
					'10%',
					$terima,
					false
				),
				// 'Print'			=> array(
				// 	'center',
				// 	'8%',
				// 	dropdown_button($cetak),
				// 	false
				// ),
				'Action'			=> array(
					'center',
					'8%',
					$button,
					false
				),
			);

			if (self::$admin == false) {
				unset($data['table'][$key]['td']['Sales']);
			}

			if (self::$type != 'invoice_0') {
				unset($data['table'][$key]['td']['Terima']);
			} else {
				unset($data['table'][$key]['td']['Status']);
			}

			if($type!=0){
				unset($data['table'][$key]['td']['Check']);
			}
		}

		return $data;
	}

	private static function head_title()
	{
		$args = array(
			'title'	=> 'Invoice <small>data invoice</small>',
			'link'	=> array(
				0	=> array(
					'func'	=> self::$object,
					'label'	=> 'invoice'
				)
			),
			'date'	=> false
		);

		return $args;
	}

	protected static function get_box()
	{
		$type = self::$type;
		$data = self::table();

		$label = self::_conv_type($type);

		$box = array(
			'label'		=> 'Invoice ' . $label,
			'tool'		=> '',
			'action'	=> self::action(),
			'func'		=> 'sobad_table',
			'data'		=> $data
		);

		return $box;
	}

	protected static function layout()
	{
		self::$type = 'invoice_4';
		$box = self::get_box();

		$tabs = array();
		$data = array(4, 2, 1);

		$object = self::$table;
		$quotation = "var='invoice' AND trash='0'";
		foreach ($data as $key => $val) {
			$tabs[] = array(
				'key'	=> 'invoice_' . $val,
				'label'	=> self::_conv_type($val),
				'qty'	=> $val == 0 ? $object::count($quotation . "AND status='0'") : $object::count($quotation . "AND type='$val'")
			);
		}

		$tabs = array(
			'active'	=> self::$type,
			'tab'		=> $tabs,
			'func'		=> '_portlet',
			'data'		=> $box
		);

		$opt = array(
			'title'		=> self::head_title(),
			'style'		=> array('quotation_marketing', '_style'),
			'script'	=> array(self::$object, '_script')
		);

		return tabs_admin($opt, $tabs);
	}

	public static function _conv_type($type = '')
	{
		$type = str_replace("invoice_", '', $type);
		intval($type);

		$types = array('Piutang', 'DP', 'Termin', '', 'Lunas');
		$label = isset($types[$type]) ? $types[$type] : '';

		return $label;
	}

	public static function _script()
	{
?>
		<script type="text/javascript">
			function invoice_data(data, id) {
				$(id).html(data['product']);
				$('#company_invoice').val(data['company']);
				$('#company_invoice.bs-select').selectpicker('refresh');

				sobad_option_search(data['customer'], '#customer_invoice');
				$('#sales').val(data['sales']);
				$('#po_number').val(data['po_number']);
			}
		</script>
	<?php
	}

	public static function _get_nominal($id_quo = 0, $id_project = 0)
	{
		$value = faktur_accounting::_get_nominalInvoice($id_quo, $id_project);
		$ppn = $value['status_ppn'] == 1 ? $value['ppn'] : 0;
		return $value['nominal'] + $ppn + $value['ongkir'];
	}

	public static function _get_bayar($reff=0){
		$bayar = 0;
		$paid = sobad_post::get_all(array('ID','price'),"AND reff='$reff' AND var='paid'",'paid');
		foreach ($paid as $key => $val) {
			$bayar += $val['price'];
		}

		return $bayar;
	}

	public static function _get_detail_tagihan($args=array()){

		//Nominal
		$value = faktur_accounting::_get_nominalInvoice($args['reff_reff'], $args['reff']);
		$nominal = $value['nominal'];
		$ppn = $value['ppn'];
		$discount = $value['discount'];
		$total = $value['total'];

		$tot_nom = $pajak = $tot_nom = $sts_ppn = $diskon = $ongkir = 0;

		// Get DP Invoice
		$dp = self::_get_dp((int) $args['_nominal'], $nominal, $args['type'], $args['reff']);

		// Get Termin Invoice
		$termin = self::_get_termin($dp ,$args['_nominal'], $total, $discount, $args['type'], $args['reff'],0, $args['ID']);

		if ($args['type'] == 4) {
			$tagihan = self::_get_lunas($dp,$termin['nominal'],$nominal);
			$diskon = $discount - $termin['discount'];
		} else if ($args['type'] == 2) {
			$tagihan = $termin['nominal'];
			$diskon = $termin['discount'];
		} else {
			$tagihan = $dp;
		}

		$tot_nom = $tagihan;
		if($ppn>0 && $value['status_ppn'] == 1){
			$ppn = ($tagihan / $nominal) * $ppn;
			$pajak = round($ppn,0);

			$tot_nom += $pajak;
		}

		if ($args['type'] == 4) {
			$ongkir = $value['ongkir'];
			$tot_nom += $ongkir;
		}

		return array(
			'nominal'		=> $tagihan,
			'discount'		=> $diskon,
			'status_pajak'	=> $value['status_ppn'],
			'pajak'			=> $pajak,
			'ongkir'		=> $ongkir,
			'total'			=> $tot_nom
		);
	}

	public static function _get_tagihan($args = array()){
		$tagihan = self::_get_detail_tagihan($args);
		return $tagihan['total'];
	}

	public static function _get_dp($nominal = 0, $total = 0, $type = 0, $reff = 0, $ppn = 0)
	{
		(int) $nominal; (int) ($total);

		// Get DP Invoice
		if ($type == 4 || $type == 2) {
			$nominal = 0;
			$dp_inv = sobad_post::get_all(array('ID', '_nominal'), "AND reff='$reff' AND type='1' AND var='invoice' AND trash='0'", 'invoice');

			foreach ($dp_inv as $key => $val) {
				$_dp = (int) $val['_nominal'];

				if ($_dp > 0) {
					$_dp = $_dp <= 100 ? $total * $_dp / 100 : $_dp;
					$nominal += round($_dp,0);

					if($ppn>0){
						$ppn = ($_dp / $total) * $ppn;
						$nominal += round($ppn,0);
					}
				}
			}
		} else {
			$nominal = $nominal <= 100 ? $total * $nominal / 100 : $nominal;

			if($ppn>0){
				$ppn = ($nominal / $total) * $ppn;
				$nominal += round($ppn,0);
			}
		}

		return (int) $nominal;
	}

	public static function _get_termin($dp = 0, $nominal = 0, $total = 0, $discount = 0,$type = 0, $reff = 0, $ppn = 0, $idx = 0)
	{
		(int) $dp; (int) $nominal; (int) $total; (int) $discount;

		// Get Termin Invoice
		$value = $down = $disc = 0;
		if ($type == 4) {
			$nominal = 0;
			$dp_inv = sobad_post::get_all(array('ID', 'reff', '_nominal'), "AND `" . base . "post`.reff='$reff' AND `" . base . "post`.type='2' AND `" . base . "post`.var='invoice' AND `" . base . "post`.trash='0'", 'invoice');

			foreach ($dp_inv as $key => $val) {
				$_dp = (int) $val['_nominal'];
				$value = 0;

				if ($_dp > 0) {
					$_dp = $_dp <= 100 ? $total * $_dp / 100 : $_dp;
					$value = $_dp;
				}else{
					$value = self::_get_barang_termin($val['ID'],$val['reff']);
				}

				$_disc = 0;
				if($discount>0){
					$_disc = $value / $total * $discount;
					$value -= round($_disc,0);

					$total -= $discount;
				}

				$_down = 0;
				if($dp>0){
					$_down = $value / $total * $dp;
					$value -= round($_down,0);
				}

				if($ppn>0){
					$ppn = ($value / $total) * $ppn;
					$value += round($ppn,0);
				}

				$nominal += $value;
				$down += $_down;
				$disc += $_disc;
			}
		} else {
			if($nominal>0){
				$nominal = $nominal <= 100 ? $total * $nominal / 100 : $nominal;
			}else{
				$nominal = self::_get_barang_termin($idx, $reff);
			}

			if($discount>0){
				$disc = $nominal / $total * $discount;
				$nominal -= round($disc,0);

				$total -= $discount;
			}

			if($dp>0){
				$down = $nominal / $total * $dp;
				$nominal -= round($down,0);
			}

			if($ppn>0){
				$ppn = ($nominal / $total) * $ppn;
				$nominal += round($ppn,0);
			}
		}

		return array(
			'nominal' 	=> (int) $nominal,
			'dp'		=> round($down,0),
			'discount'	=> round($disc,0)
		);
	}

	public static function _get_lunas($dp=0, $termin=0, $total=0, $ppn=0){
		$value = $total;
		if($ppn>0){
			$value += $ppn;
		}

		if($dp>0){
			$value -= $dp;
		}

		if($termin>0){
			$value -= $termin;
		}

		return $value;
	}

	public static function _get_barang_termin($idx=0,$reff=0){
		// Get project
		$pro = sobad_post::get_id($reff,array('ID','reff'));
		$quo = $pro[0]['reff'];

		// Get trans quotation
		$quot = array();
		$trans = sobad_post::get_transactions($quo,array('barang','price','discount'));
		foreach ($trans as $key => $val) {
			$quot[$val['barang']] = $val;
		}

		$nominal = 0;
		$items = sobad_post::get_transactions($idx,array('barang','qty'),"AND `" . base . "transaksi`.note='1'");
		foreach ($items as $key => $val) {
			$idb = $val['barang'];
			$nominal += ($quot[$idb]['price'] - $quot[$idb]['discount']) * $val['qty'];
		}

		return $nominal;
	}

	// ----------------------------------------------------------
	// Form data category ---------------------------------------
	// ----------------------------------------------------------

	public static function _view($id = 0)
	{
		$id = str_replace('view_', '', $id);
		intval($id);

		$args = project_marketing::_array();
		$q = sobad_post::get_id($id, $args, '', 'project');

		if ($q === 0) {
			return '';
		}

		return project_marketing::edit_form($q[0]);
	}

	public static function add_form()
	{
		$no = quotation_marketing::_get_max('invoice');
		$vals = array(0, $no + 1, 0, 0, 0, date('Y-m-d'), date('Y-m-d'), 0, 0, '', date('Y-m-d'), 0, array(), 0, '','');
		$vals = array_combine(self::_array(), $vals);

		$args = array(
			'title'		=> 'Tambah data project',
			'button'	=> '_btn_modal_save',
			'status'	=> array(
				'link'		=> '_add_db',
				'load'		=> 'sobad_portlet',
				'type'		=> $_POST['type']
			)
		);

		return self::_data_form($args, $vals);
	}

	protected static function edit_form($vals = array())
	{
		$check = array_filter($vals);
		if (empty($check)) {
			return '';
		}

		$vals['_do_number'] = explode(',', $vals['_do_number']);

		$args = array(
			'title'		=> 'Edit data project',
			'button'	=> '_btn_modal_save',
			'status'	=> array(
				'link'		=> '_update_db',
				'load'		=> 'sobad_portlet',
				'type'		=> $_POST['type']
			)
		);

		return self::_data_form($args, $vals);
	}

	private static function _data_form($args = array(), $vals = array())
	{
		$check = array_filter($args);
		if (empty($check)) {
			return '';
		}

		// Project	
		$project = array();
		$post = sobad_post::get_projects(array('ID', 'title', 'inserted', 'reff', '_project'), "AND `" . base . "post`.type IN ('0','1') AND `" . base . "post`.trash='0'");
		foreach ($post as $key => $val) {
			$project[$val['ID']] = project_marketing::_post_title($val['title'], $val['inserted']) . ' || ' . $val['_project'];
		}

		// Get Project Active
		if ($vals['ID'] > 0) {
			$_pro = sobad_post::get_id($vals['reff'], array('title', 'inserted', '_project'), "", "project");
			$_pro = $_pro[0];

			$project[$vals['reff']] = project_marketing::_post_title($_pro['title'], $_pro['inserted']) . ' || ' . $_pro['_project'];
		}

		// End Project	

		$check = array_filter($project);
		if (empty($check)) {
			$project[0] = 'Tidak Ada';
		}

		if ($vals['reff'] == 0) {
			reset($project);
			$vals['reff'] = key($project);
		}

		if (!isset($vals['reff_reff'])) {
			$vals['reff_reff'] = isset($post[0]) ? $post[0]['reff'] : 0;
		}

		$que = sobad_post::get_id($vals['reff_reff'], array('company', 'contact'), '', 'quotation');
		$check = array_filter($que);

		if (!empty($check)) {
			$vals['company'] = $que[0]['company'];
			$vals['contact'] = $que[0]['contact'];
		}

		// Company
		$comp = sobad_company::get_companies(array('ID', 'name'));
		$comp = convToOption($comp, 'ID', 'name');
		$comp[0] = 'Tidak Ada';
		ksort($comp);

		// Contact
		$reff = $vals['company'];
		$whr = empty($vals['ID']) ? "AND reff='$reff' LIMIT 0,25" : "AND ID='" . $vals['contact'] . "'";
		$cust = sobad_company::get_customers(array('ID', 'name'), $whr);
		$cust = convToOption($cust, 'ID', 'name');

		$cust[0] = 'Tidak Ada';
		$cust[-1] = 'Bag. Keuangan';

		// detail
		$_det = self::_get_detail_project($vals['reff']);

		// Do Number
		$do_number = array();
		$whr = $vals['ID'] == 0 ? "AND status='0'" : '';
		$do = sobad_post::get_delivery_orders(array('ID', 'title', 'inserted'), "AND reff='" . $vals['reff'] . "' " . $whr, 'delivery_order');
		foreach ($do as $key => $val) {
			$do_number[$val['ID']] = deliveryOrder_marketing::_post_title($val['title'], $val['inserted']);
		}

		$tab1 = array(
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
				'key'			=> 'post_code',
				'label'			=> 'No Invoive',
				'class'			=> 'input-circle',
				'value'			=> self::_post_title($vals['title'], $vals['inserted']),
				'data'			=> 'placeholder="No Invoice" readonly'
			),
			array(
				'func'			=> 'opt_select',
				'data'			=> $project,
				'key'			=> 'reff',
				'label'			=> 'Project',
				'class'			=> 'input-circle',
				'searching'		=> true,
				'select'		=> $vals['reff'],
				'status'		=> 'data-sobad="_load_project" data-load="invoice_product" data-attribute="invoice_data" '
			),
			array(
				'id'			=> 'company_invoice',
				'func'			=> 'opt_select',
				'data'			=> $comp,
				'key'			=> 'company',
				'label'			=> 'Company',
				'class'			=> 'input-circle',
				'searching'		=> true,
				'select'		=> $vals['company'],
				'status'		=> 'data-sobad="_load_customer" data-load="customer_invoice" data-attribute="sobad_option_search" '
			),
			array(
				'id'			=> 'customer_invoice',
				'func'			=> 'opt_select',
				'data'			=> $cust,
				'key'			=> 'contact',
				'label'			=> 'Kepada',
				'searching'		=> true,
				'class'			=> 'input-circle',
				'select'		=> $vals['contact'],
				'status'		=> ''
			),
			array(
				'func'			=> 'opt_input',
				'type'			=> 'date',
				'key'			=> 'post_date',
				'label'			=> 'Tanggal',
				'class'			=> 'input-circle',
				'value'			=> $vals['post_date'],
				'data'			=> 'placeholder="Tanggal"'
			),
			array(
				'id'			=> 'sales',
				'func'			=> 'opt_input',
				'type'			=> 'text',
				'key'			=> 'sales',
				'label'			=> 'Sales',
				'class'			=> 'input-circle',
				'value'			=> $_det['sales'],
				'data'			=> 'placeholder="Sales" readonly'
			),
			array(
				'id'			=> 'po_number',
				'func'			=> 'opt_input',
				'type'			=> 'text',
				'key'			=> 'po_number',
				'label'			=> 'PO Number',
				'class'			=> 'input-circle',
				'value'			=> $_det['po_number'],
				'data'			=> 'placeholder="PO Number" readonly'
			),
			// array(
			// 	'id'			=> 'do_number',
			// 	'func'			=> 'opt_select',
			// 	'data'			=> $do_number,
			// 	'key'			=> '_do_number',
			// 	'label'			=> 'DO Number',
			// 	'class'			=> 'input-circle',
			// 	'searching'		=> true,
			// 	'select'		=> $vals['_do_number'],
			// 	'status'		=> 'data-sobad="_load_product" data-load="invoice_product" data-attribute="html" multiple'
			// ),
		);

		$tab2 = array(
			0 => array(
				'func'			=> 'opt_input',
				'type'			=> 'text',
				'key'			=> '_no_faktur',
				'label'			=> 'No Faktur',
				'class'			=> 'input-circle',
				'value'			=> $vals['_no_faktur'],
				'data'			=> 'placeholder="No Faktur"'
			),
			array(
				'func'			=> 'opt_select',
				'data'			=> array(1 => 'DP', 2 => 'Termin', 4 => 'Lunas'),
				'key'			=> 'type',
				'label'			=> 'Jenis Invoice',
				'class'			=> 'input-circle',
				'select'		=> $vals['type'],
				'status'		=> ''
			),
			array(
				'func'			=> 'opt_input',
				'type'			=> 'text',
				'key'			=> '_nominal',
				'label'			=> 'Pembayaran',
				'class'			=> 'input-circle money',
				'value'			=> format_nominal(intval($vals['_nominal'])),
				'data'			=> 'placeholder="Pembayaran" onkeydown="mask_money(\'.money\')"'
			),
		);

		$tab4 = array(
			0 => array(
				'func'			=> 'opt_input',
				'type'			=> 'text',
				'key'			=> '_term_payment',
				'label'			=> 'term of Payment',
				'class'			=> 'input-circle',
				'value'			=> $vals['_term_payment'],
				'data'			=> 'placeholder="Term of Payment"'
			),
			array(
				'func'			=> 'opt_datepicker',
				'type'			=> 'date',
				'key'			=> '_due_date',
				'label'			=> 'Due to',
				'class'			=> 'input-circle',
				'value'			=> $vals['_due_date'],
				'data'			=> 'placeholder="Due to"'
			),
			array(
				'func'			=> 'opt_textarea',
				'key'			=> '_note',
				'label'			=> 'Catatan',
				'class'			=> 'input-circle',
				'value'			=> $vals['_note'],
				'data'			=> 'placeholder="Catatan"',
				'rows'			=> 4
			),
		);

		$data = array(
			'menu'		=> array(
				0	=> array(
					'key'	=> '',
					'icon'	=> 'fa fa-bars',
					'label'	=> 'General'
				),
				1	=> array(
					'key'	=> '',
					'icon'	=> 'fa fa-money',
					'label'	=> 'Pembayaran'
				),
				2	=> array(
					'key'	=> '',
					'icon'	=> 'fa fa-shopping-cart',
					'label'	=> 'Produk'
				),
				3	=> array(
					'key'	=> '',
					'icon'	=> 'fa fa-book',
					'label'	=> 'Keterangan'
				),
			),
			'content'	=> array(
				0	=> array(
					'func'	=> 'sobad_form',
					'data'	=> $tab1
				),
				1	=> array(
					'func'	=> 'sobad_form',
					'data'	=> $tab2
				),
				2	=> array(
					'func'	=> '_invoice_detail',
					'object' => self::$object,
					'data'	=> array(
						'ID'	=> $vals['ID'],
						'reff'	=> $vals['reff']
					)
				),
				3	=> array(
					'func'	=> 'sobad_form',
					'data'	=> $tab4
				)
			)
		);

		$args['func'] = array('_inline_menu');
		$args['data'] = array($data);

		return modal_admin($args);
	}

	public static function _load_customer($id = 0, $id_cust = 0)
	{
		$search = isset($_POST['type']) ? $_POST['type'] : '';

		$q = sobad_company::get_contacts(array('ID', 'name'), "AND reff='$id' AND name LIKE '%$search%' LIMIT 0,25");

		$des = '<option value="0"> Tidak Ada</option>';
		$des .= '<option value="-1"> Bag. Keuangan</option>';
		$check = array_filter($q);
		if (!empty($check)) {
			foreach ($q as $key => $cust) {
				$select = $cust['ID'] == $id_cust ? 'selected' : '';
				$des .= '<option value="' . $cust['ID'] . '" '.$select.'> ' . $cust['name'] . ' </option>';
			}

			return $des;
		}

		return $des;
	}

	public static function _get_detail_project($id = 0)
	{
		if ($id > 0) {
			$pro = sobad_post::get_id($id, array('reff', '_po_number'), '', 'project');

			$que = sobad_post::get_id($pro[0]['reff'], array('user', 'company', 'contact'), '', 'quotation');
			$user = isset($que[0]['user']) ? $que[0]['user'] : 0;

			$user = kmi_user::get_id($user, array('name'));
			$user = isset($user[0]['name']) ? $user[0]['name'] : '';

			$cp = isset($que[0]) ? $que[0]['company'] : 0;
			$ct = self::_load_customer($cp, $que[0]['contact']);
			$po = $pro[0]['_po_number'];
			$sl = $user;
		} else {
			$cp = 0;
			$ct = self::_load_customer();
			$po = '';
			$sl = '';
		}

		return array(
			'company'		=> $cp,
			'customer'		=> $ct,
			'po_number'		=> $po,
			'sales'			=> $sl
		);
	}

	public static function _load_product($idx = 0, $post = 'delivery_order')
	{
		$data = self::_product_detail($idx, $post);

		ob_start();
		theme_layout('sobad_table', $data);
		return ob_get_clean();
	}

	public static function _load_project($idx = 0)
	{
		$detail = self::_get_detail_project($idx);
		$detail['product'] = self::_load_product($idx, 'project');

		return $detail;
	}

	public static function _invoice_detail($vals = array())
	{
		$idx = $vals['ID'];
		$reff = $vals['reff'];

		$data = self::_product_detail($idx,'invoice',$reff);

	?>
		<div id="invoice_detail" style="margin-left:1px;">
			<div class="portlet gren">
				<div class="portlet-title">
					<div class="caption">Data Project Produk</div>
					<div class="tools"></div>
					<div class="actions">

					</div>
				</div>
				<div class="portlet-body">
					<div id="invoice_product">
						<?php
						theme_layout('sobad_table', $data);
						?>
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			$('#customer_invoice').parent().children('.bs-select').children('div.dropdown-menu').children('.bs-searchbox').children().on('change', function() {
				sobad_loading('.bs-select ul.selectpicker');

				data = "ajax=_load_customer&object=" + object + "&type=" + this.value + "&data=" + $('select[name=company]').val();
				sobad_ajax('#customer_invoice', data, select_option_search, false);
			});

			function select_option_search(data, id) {
				$(id).html(data);
				$('.bs-select').selectpicker('refresh');

				$('div.bs-select:nth-child(2) ul.selectpicker .blockUI').remove();
			}
		</script>
<?php
	}

	public static function _product_detail($idx = 0, $post = 'invoice', $reff = 0)
	{
		$data = array();
		$asset = 'asset/img/upload/';

		$product = sobad_post::get_id($idx, array('ID', 'reff', 'barang', 'qty', 'note'), "", $post);

		$check = array_filter($product);
		if(empty($check)){
			$product = sobad_post::get_id($reff, array('ID', 'reff', 'barang', 'qty', 'note'), "AND `" . base . "transaksi`.note='1'", $post);
		}

		$data['class'] = '';
		$data['table'] = array();

		foreach ($product as $key => $val) {
			$_reff = $post == 'project' ? $val['reff'] : $val['reff_reff'];
			$que = sobad_post::get_id($_reff, array('ID', 'price', 'discount',), "AND `" . base . "transaksi`.barang='" . $val['barang'] . "'", 'quotation');
			$que = isset($que[0]) ? $que[0] : 0;

			$harga = $que['price'];
			$discount = $que['discount'] <= 100 ? $harga * ($que['discount'] / 100) : $que['discount'];
			$total = ($harga - $discount) * $val['qty'];
			$total = round($total,0);

			$arr_sku = array(
				'type'		=> 'hidden',
				'key'		=> 'product_code',
				'value'		=> $val['barang'],
				'status'	=> ''
			);

			$arr_price = array(
				'type'		=> 'hidden',
				'key'		=> 'product_price',
				'value'		=> $que['price'],
				'status'	=> ''
			);

			// $arr_qty = array(
			// 	'type'		=> 'number',
			// 	'key'		=> 'product_qty',
			// 	'value'		=> $val['qty'],
			// 	'status'	=> 'min="1"'
			// );

			$arr_disc = array(
				'type'		=> 'hidden',
				'key'		=> 'product_discount',
				'value'		=> $discount,
				'status'	=> ''
			);

			$edit_sku = editable_value($arr_sku);
			// $edit_price = editable_value($arr_price);
			// $edit_disc = editable_value($arr_disc);
			// $edit_qty = editable_value($arr_qty);

			$edit_qty = '<input type="text" class="form-control  money" name="product_qty" value="' . format_nominal($val['qty']) . '" style="width: 100%;">';

			if (empty($val['image'])) {
				$lokasi = $asset . 'no-image.png';
			} else {
				$lokasi = $asset . $val['image'];
			}

			$check = $val['note'] == '1' ? 'checked' : '';

			$data['table'][$key]['tr'] = array();
			$data['table'][$key]['td'] = array(
				'Image'	=> array(
					'left',
					'10%',
					'<img src="' . $lokasi . '" style="width:100%;margin:auto;">',
					true
				),
				'Name'		=> array(
					'left',
					'15%',
					$val['name_bara'],
					true
				),
				'Product Code'		=> array(
					'left',
					'15%',
					$val['product_code_bara'] . $edit_sku,
					true
				),
				'Qty'		=> array(
					'right',
					'8%',
					$edit_qty,
					true
				),
				'Harga'		=> array(
					'left',
					'12%',
					'Rp. ' . format_nominal($harga),
					true
				),
				'Diskon'	=> array(
					'left',
					'10%',
					'Rp. ' . format_nominal($discount),
					true
				),
				'Total'	=> array(
					'right',
					'10%',
					'Rp. ' . format_nominal($total),
					true
				),
				'Checklist'			=> array(
					'center',
					'10%',
					'<input type="checkbox" name="product_check" value="check_' . $val['barang'] . '" ' . $check . '>',
					false
				)
			);
		}

		return $data;
	}

	// ------------------------------------------------------------
	// ----- View History Pembayaran ------------------------------
	// ------------------------------------------------------------	

	public static function _viewPaid($id = 0)
	{
		$id = str_replace('paid_', '', $id);
		$table = kredit_purchase::_table_pay($id,'paid');

		$args = array(
			'title'		=> 'History Pembayaran',
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

	// ------------------------------------------------------------
	// ----- View Table Item --------------------------------------
	// ------------------------------------------------------------	

	public static function _viewInvoice($id = 0)
	{
		$id = str_replace('view_', '', $id);
		$inv = sobad_post::get_id($id, array('reff'));
		$inv = $inv[0]['reff'];

		$table = self::_product_detail($inv);

		$args = array(
			'title'		=> 'View data Invoice',
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

	// ----------------------------------------------------------
	// Function invoice to database 2 ---------------------------
	// ----------------------------------------------------------

	public static function _paid_form($id = 0, $var = 'paid')
	{
		$id = str_replace('paid_', '', $id);
		intval($id);

		// GET payment method
		$payment = account_purchase::_option_accounts();

		$post = sobad_post::get_id($id, array('ID', '_nominal', 'type', 'reff'), '', 'invoice');
		$val = $post[0];

		$bayar = self::_get_bayar($id);
		$tagihan = self::_get_tagihan($val);

		$tagihan -= $bayar;
		$data = array(
			0 => array(
				'func'			=> 'opt_hidden',
				'type'			=> 'hidden',
				'key'			=> 'ID',
				'value'			=> $id
			),
			array(
				'func'			=> 'opt_hidden',
				'type'			=> 'hidden',
				'key'			=> 'tagihan',
				'value'			=> $tagihan
			),
			array(
				'func'			=> 'opt_select',
				'data'			=> $payment,
				'key'			=> 'payment',
				'label'			=> 'Bank',
				'class'			=> 'input-circle',
				'select'		=> 0,
				'status'		=> ''
			),
			array(
				'func'			=> 'opt_datepicker',
				'key'			=> 'post_date',
				'label'			=> 'Tanggal',
				'class'			=> 'input-circle',
				'value'			=> date('Y-m-d'),
				'data'			=> 'placeholder="bayar"'
			),
			array(
				'func'			=> 'opt_input',
				'type'			=> 'price',
				'key'			=> 'balance',
				'label'			=> 'Nominal',
				'class'			=> 'input-circle',
				'value'			=> format_nominal($tagihan),
				'data'			=> 'placeholder="bayar"'
			),
		);

		$args = array(
			'title'		=> 'Bayar',
			'button'	=> '_btn_modal_save',
			'status'	=> array(
				'link'		=> '_paid',
				'load'		=> 'sobad_portlet',
				'type'		=> $_POST['type']
			)
		);

		$args['func'] = array('sobad_form');
		$args['data'] = array($data);

		return modal_admin($args);
	}

	public static function _paid($args = array(), $var = 'paid', $role = true)
	{
		$args = sobad_asset::ajax_conv_json($args);
		$id = $args['ID'];

		if ($args['balance'] <= 0) {
			die(_error::_alert_db('Nominal Tidak boleh kosong!!!'));
		}

		// Check Pembayaran
		$bayar = 0;
		$paids = sobad_post::get_all(array('ID', 'price'), "AND var='$var' AND reff='$id'", $var);
		foreach ($paids as $key => $val) {
			$bayar += $val['price'];
		}

		$nominal = $args['tagihan'];

		$paid = $args['balance'];
		$status = 0;
		if ($args['balance'] >= $nominal) {
			$paid = $nominal;
			$status = 1;
		}

		// Update status bayar
		$q = sobad_db::_update_single($id, '' . base . 'post', array('status' => $status, 'updated' => date('Y-m-d H:i:s')));

		// Insert data paid
		$no = quotation_marketing::_get_max('paid');
		$data = array(
			'title'		=> $no+1,
			'payment'	=> $args['payment'],
			'inserted'	=> date('Y-m-d H:i:s'),
			'post_date'	=> empty($args['post_date']) ? date('Y-m-d') : $args['post_date'],
			'user'		=> get_id_user(),
			'status'	=> 1,
			'reff'		=> $id,
			'var'		=> $var
		);

		$q = sobad_db::_insert_table('' . base . 'post', $data);

		// --- meta 

		$data = array(
			'post'		=> $q,
			'qty'		=> 1,
			'price'		=> $paid,
			'unit'		=> 'IDR'
		);

		$q = sobad_db::_insert_table('' . base . 'transaksi', $data);

		// End Insert

		// Tambah dana account
		$akun = sobad_account::get_id($args['payment'], array('ID', 'balance'));
		$saldo = $akun[0]['balance'];

		$saldo += $paid;

		$q = sobad_db::_update_single($args['payment'], '' . base . 'account', array('ID' => $args['payment'], 'balance' => $saldo));

		if ($q !== 0 && $role == true) {
			$pg = isset($_POST['page']) ? $_POST['page'] : 1;
			return self::_get_table($pg);
		}

		return $q;
	}

	// ----------------------------------------------------------
	// Function invoice to database -----------------------------
	// ----------------------------------------------------------

	public static function _callback($args = array(), $_args = array())
	{
		$args['var'] = 'invoice';
		$args['user'] = get_id_user();
		$args['_nominal'] = clear_format($args['_nominal']);
		$args['_due_date'] = $args['_due_date'] == '1970-01-01' ? '' : $args['_due_date'];

		// Reset Project Active
		if ($args['ID'] > 0) {
			$inv = sobad_post::get_id($args['ID'], array('reff'), '', 'invoice');
			if ($args['reff'] != $inv[0]['reff']) {
				// Reset Lock Project

				$inv = $inv[0];
				if (in_array($inv['type'], array(1, 3))) {
					$_type = 1;
				} else {
					$_type = 0;
				}

				sobad_db::_update_single($inv['reff'], '' . base . 'post', array('ID' => $inv['reff'], 'type' => $_type));
			}
		}

		// Check type project --> sebelum
		// Reset Surat Jalan ---> referensi
		$po = sobad_post::get_id($args['reff'], array('type'), '', 'project');

		if ($args['type'] == 4) {
			if (in_array($po[0]['type'], array(1, 3))) {
				$_type = 3;
			} else {
				$_type = 2;
			}
		} else {
			if (in_array($po[0]['type'], array(1, 3))) {
				$_type = 1;
			} else {
				$_type = 0;
			}
		}

		sobad_db::_update_single($args['reff'], '' . base . 'post', array('ID' => $args['reff'], 'type' => $_type));

		return $args;
	}

	public static function _addDetail($args = array(), $_args = array())
	{
		if($args['value']['type']==1){
			return 0;
		}

		$idx = $args['index'];
		return self::_update_detail($idx, $_args, true);
	}

	public static function _updateDetail($args = array(), $_args = array())
	{
		if($args['value']['type']==1){
			return 0;
		}

		$idx = $args['index'];
		return self::_update_detail($idx, $_args, false);
	}

	private static function _update_detail($reff = 0, $args = array(), $status = true)
	{
		$args = sobad_asset::ajax_conv_array_json($args);

		$total = 0;
		$count = count($args['product_code']);
		foreach ($args['product_code'] as $ky => $val) {
			$price = $args['product_price'][$ky];
			$discount = $args['product_discount'][$ky];

			$qty = clear_format($args['product_qty'][$ky]);
			$note = in_array("check_" . $val, $args['product_check']) ? 1 : 0;

			$data = array(
				'post'			=> $reff,
				'barang'		=> $val,
				'qty'			=> $qty,
				'note'			=> $note
			);

			if ($status) {
				$q = sobad_db::_insert_table(base . 'transaksi', $data);
			} else {
				$q = sobad_db::_update_multiple("post='$reff' AND barang='$val'", '' . base . 'transaksi', $data);
			}
		}

		//sobad_db::_update_single($args['reff'][0],''. base .'post',array('type' => $type));
	}

	// ----------------------------------------------------------
	// Print data Invoice ---------------------------------------
	// ----------------------------------------------------------

	public static function _preview($id)
	{
		return self::_preview_report($id, '_html');
	}

	public static function _preview_en($id)
	{
		return self::_preview_report($id,  '_html_en');
	}

	public static function _preview_report($id = 0, $html = '')
	{
		$_SESSION[_prefix . 'development'] = 0;
		$id = str_replace('preview_', '', $id);
		intval($id);

		$data = self::_get_data_invoice($id);

		$postcode = self::_post_title($data['title'], $data['inserted']);
		$postcode = str_replace('/', '-', $postcode);

		$sales = kmi_user::get_id($data['user'], array('name'));
		$sales = isset($sales[0]['name']) ? $sales[0]['name'] : '';

		$data['title'] = 'INVOICE';
		$data['date'] = format_date_id($data['post_date']);
		$data['sales'] = $sales;

		$args = array(
			'data'			=> $data,
			'header'		=> 'address_invoice',
			'data_header'	=> $data,
			'footer'		=> '',
			'html'			=> $html,
			'object'		=> self::$object,
			'title'			=> 'Invoice ' . str_replace('/', '-', $postcode),
		);

		$args = pdf_setting_invoice($args);
		return sobad_convToPdf($args);
	}

	public static function _get_data_invoice($id = 0)
	{
		$quo = array(
			'ID',
			'company',
			'contact',
			'title',
			'post_date',
			'inserted',
			'type',
			'reff',
			'user',
			'_nominal',
			'_due_date',
			'_term_payment',
			'_detail',
			'_do_number',
			'_note'
		);

		$data = sobad_post::get_id($id, $quo, '', 'invoice');

		$check = array_filter($data);
		if (empty($check)) {
			return '';
		}

		$data[0] = quotation_marketing::_conv_address($data[0]);
		$data[0]['post_code'] = self::_post_title($data[0]['title'], $data[0]['inserted']);

		// Get Sales
		$que = sobad_post::get_id($data[0]['reff_reff'], array('ID', 'user'));
		$data[0]['user'] = $que[0]['user'];

		// Get project no
		$data[0]['project_no'] = project_marketing::_post_title($data[0]['title_reff'], $data[0]['inserted_reff']);

		// Get po number
		$project = sobad_post::get_id($data[0]['reff'], array('_po_number'), '', 'project');
		$data[0]['_po_number'] = $project[0]['_po_number'];

		// Get do number
		$_do = array();
		$reff = $data[0]['reff'];

		$do = sobad_post::get_all(array('ID', 'title', 'inserted'), "AND reff='$reff' AND var='delivery_order'", 'delivery_order');
		foreach ($do as $key => $val) {
			$_do[$val['ID']] = deliveryOrder_marketing::_post_title($val['title'], $val['inserted']);
		}

		$data[0]['do_number'] = implode(', ', $_do);

		// Nominal
		$value = faktur_accounting::_get_nominalInvoice($data[0]['reff_reff'], $data[0]['reff']);
		$nominal = $value['nominal'];
		$discount = $value['discount'];
		$total = $value['total'];

		// Get DP Invoice
		$dp = self::_get_dp($data[0]['_nominal'], $nominal, $data[0]['type'], $data[0]['reff']);

		// Get Termin Invoice
		$termin = self::_get_termin($dp, $data[0]['_nominal'], $total, $discount, $data[0]['type'], $data[0]['reff'],0,$data[0]['ID']);

		// Get Lunas Invoice
		$lunas = self::_get_lunas($dp, $termin['nominal'], $nominal);

		// Get po number
		$project = sobad_post::get_id($data[0]['reff'], array('_po_number'), '', 'project');
		$data[0]['_po_number'] = $project[0]['_po_number'];

		// PPN
		$tanggal1 = "2022-04-01";
		$tanggal2 = $data[0]['post_date'];
		
		$sts_ppn = sobad_company::get_profile(array('ID', '_ppn'), '');
		if ($tanggal2 > $tanggal1) {
			$ppn = intval($sts_ppn[0]['_ppn']);
		} else {
			$ppn = 10;
		}

		$data[0]['_ppn'] = $ppn;
		$data[0]['_shipping_price'] = 0;

		if ($data[0]['type'] == 4) {
			$tagihan = $lunas;
			$total = $tagihan + $value['ongkir'];

			$data[0]['_discount'] = $discount - $termin['discount'];
			$data[0]['_shipping_price'] = $value['ongkir'];
			$data[0]['_dp'] = $dp - $termin['dp'];
		} else if ($data[0]['type'] == 2) {
			$tagihan = $termin['nominal'];
			$total = $tagihan;

			$data[0]['_discount'] = $termin['discount'];
			$data[0]['_dp'] = $termin['dp'];
		} else {
			$tagihan = $dp;
			$total = $dp;

			$data[0]['dp_nom'] = $data[0]['_nominal'];
			$data[0]['_discount'] = $discount;
			$data[0]['_dp'] = $dp;
		}

		if($ppn>0 && $value['status_ppn'] == 1){
			$result_ppn = round(($tagihan / $nominal) * $value['ppn'],0);
			$data[0]['_result_ppn'] = $result_ppn;
		}

		$data[0]['status_ppn'] = $value['status_ppn'];
		$data[0]['_total'] = $total + $result_ppn;
		return $data[0];
	}

	public static function _html($post = array())
	{
		$check = array_filter($post);
		if (empty($check)) {
			return '';
		}

		$invoice = self::_info_payment($post, 'IDR');
		$product = sobad_post::get_transactions($post['ID'], array(), "AND `" . base . "transaksi`.note='1'");

		$check = array_filter($product);
		if(empty($check)){
			$product = sobad_post::get_transactions($post['reff'], array(),"AND `" . base . "transaksi`.note='1'");
		}

		$post['product'] = $product;
		$post['include'] = 'info_content';
		$post['curency'] = 'Rp.';
		$post['status_ppn'] = $post['status_ppn'] == 2 ? '(include PPn)' : '';

		report::view('Marketing/invoice/content', $post);
		report::view('Marketing/invoice/payment_info', $invoice);
	}
	public static function _html_en($post = array())
	{
		$check = array_filter($post);
		if (empty($check)) {
			return '';
		}

		$invoice = self::_info_payment($post, 'USD');
		$product = sobad_post::get_transactions($post['ID'], array(), "AND `" . base . "transaksi`.note='1'");

		$check = array_filter($product);
		if(empty($check)){
			$product = sobad_post::get_transactions($post['reff'], array(), "AND `" . base . "transaksi`.note='1'");
		}

		$post['product'] = $product;
		$post['include'] = 'info_content_en';
		$post['curency'] = 'USD';
		$post['status_ppn'] = $post['status_ppn'] == 2 ? '(include VAT)' : '';

		report::view('Marketing/invoice/content', $post);
		report::view('Marketing/invoice/payment_info_en', $invoice);
	}

	public static function _info_payment($data = array(), $currency = 'IDR')
	{
		$info = array(
			0	=> array(
				'title'	=> 'Term of Payment',
				'text'	=> $data['_term_payment'],
			),
			1	=> array(
				'title'	=> 'Due To',
				'text'	=> $data['_due_date'],
			),
			2	=> array(
				'title'	=> 'Payment Detail',
				'text'	=> $data['_detail'],
			),
		);

		$bank = self::_bank_payment($currency);
		$user = kmi_user::get_id($data['user'], array('name', 'no_induk', 'phone_no'));
		$user = isset($user[0]) ? $user[0] : array('no_induk' => '', 'name' => '', 'phone_no' => '');

		$invoice = array(
			'info'			=> $info,
			'bank'			=> $bank,
			'no_induk'		=> isset($user['no_induk']) ? $user['no_induk'] : '',
			'sales'			=> isset($user['name']) ? $user['name'] : '',
			'sales_phone'	=> isset($user['phone_no']) ? $user['phone_no'] : ''
		);

		return $invoice;
	}

	public static function _bank_payment($currency = 'IDR')
	{
		$bank = array();
		$unit = unit::_get(array('bank'));
		$unit = $unit['bank']['unit'];

		$where = "AND currency='$currency' AND bank!='0'";
		$account = sobad_account::get_all(array('name', 'no_rek', 'bank', 'address'), $where);
		foreach ($account as $key => $val) {
			$_bank =  $unit[$val['bank']]['name'] . ' ' . $val['address'];
			$_bank = strtoupper($_bank);

			$bank[] = array(
				'bank'		=> $_bank,
				'no_rek'	=> $val['no_rek'],
				'name'		=> $val['name']
			);
		}

		return $bank;
	}
}
