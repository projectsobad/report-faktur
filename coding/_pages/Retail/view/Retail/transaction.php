<?php

class transaksi_retail extends form_product
{

	protected static $object = 'transaksi_retail';

	protected static $table = 'sobad_post';

	protected static $post = 'order';

	protected static $filter = array();

	// ----------------------------------------------------------
	// Layout Transaksi  ------------------------------------------
	// ----------------------------------------------------------

	public function __construct()
	{
		$setting = _setting_retail();

		$user = get_id_user();
		$meta = sobad_meta::_get_retail($user);

		$check = array_filter($meta);
		if (!empty($check)) {
			$setting = unserialize($meta[0]['meta_note']);
			$setting = $setting['brand'];
		}

		$setting = implode(',', $setting);
		$setting = empty($setting) ? 1 : $setting;

		self::$filter = array(
			'load'		=> 'here_modal3',
			'script'	=> 'set_apply_product(this)',
			'modal'		=> 3,
			'search'	=> '_malika',
			'where'		=> "AND type IN ($setting) AND var IN ('4','5')"
		);
	}

	protected static function _array()
	{
		$args = array(
			'ID',
			'title',
			'contact',
			'_expedition',
			'_resi',
			'post_date',
			'type',
			'status',
			'payment',
			'_discount',
			'_shipping_price',
			'inserted',
			'reff'
		);

		return $args;
	}

	public static function _post_title($no = 0, $kode = '', $date = '')
	{
		$no = sprintf("%04d", $no);
		$date = empty($date) ? date('Y-m-d') : $date;
		$date = strtotime($date);

		$kode = empty($kode) ? 'PO' : $kode;
		return $kode . '/' . $no . '/' . date('m', $date) . '/' . date('y', $date);
	}

	public static function _invoice_title($no = 0, $date = '')
	{
		$no = sprintf("%04d", $no);
		$date = empty($date) ? date('Y-m-d') : $date;
		$date = strtotime($date);

		return 'INV/' . $no . '/' . date('m', $date) . '/' . date('y', $date);
	}

	public static function _filter_search($field = '', $search = '')
	{
		if ($field == 'title') {
			$table = '`' . base . 'post`.';
			return self::_query_filter_search($field, $search, 'title', $table);
		}

		if ($field == 'contact') {
			return "_contact.name LIKE '%$search%'";
		}

	}

	public static function _query_filter_search($field = '', $search = '', $arr_field = 'title', $table = '')
	{
		if ($field == $arr_field) {
			$args = array();
			$data = explode('/', $search);
			$title = array('[A-Z]{2}', '[0-9]{4}', '[0-9]{2}', '^[a-zA-Z0-9]+$');

			$channel = sobad_meta::_gets('channel', array('ID', 'meta_note'));
			$channel = convToOption($channel, 'ID', 'meta_note');

			foreach ($data as $key => $val) {
				foreach ($title as $ky => $vl) {
					if (empty($val)) {
						$args[] = 0;
						break;
					}

					$regx = "/$vl/i";
					if (preg_match($regx, $val)) {
						$args[] = $ky + 1;
						break;
					}
				}
			}

			if (count($args) > 0) {
				$sort = implode('', $args);
				$sort = strval($sort);

				// Search By Type
				if ($sort === '1' || $sort === '10') {
					$type = array_search($data[0], $channel) ? array_search($data[0], $channel) : 0;
					return "type='$type'";
				}

				// Search By Type AND title
				if (in_array($sort, array('12', '120'))) {
					$type = array_search($data[0], $channel) ? array_search($data[0], $channel) : 0;
					$title = $data[1] * 1;
					return "(" . $table . "type='$type' AND " . $table . "title='$title')";
				}

				// Search By Type AND Title AND Bulan
				if (in_array($sort, array('12', '123', '1230'))) {
					$type = array_search($data[0], $channel) ? array_search($data[0], $channel) : 0;
					$title = $data[1] * 1;
					$month = $data[2];
					return "" . $table . "type='$type' AND " . $table . "title='$title' AND MONTH(" . $table . "inserted) = '" . $month . "')";
				}

				// Search By Type AND Title AND Bulan AND YEAR
				if (in_array($sort, array('12', '123', '1232'))) {
					$type = array_search($data[0], $channel) ? array_search($data[0], $channel) : 0;
					$title = $data[1] * 1;
					$month = $data[2];
					$year = $data[3];
					return "(" . $table . "type='$type' AND " . $table . "title='$title' AND MONTH(" . $table . "inserted) = '" . $month . "' AND YEAR(" . $table . "inserted) = '" . $year . "')";
				}

				// Search By title OR Year
				if ($sort == '2' || $sort == '02') {
					$title = $data[count($args) - 1] * 1;
					$year = $data[count($args) - 1];
					return "(YEAR(" . $table . "inserted) = '" . $year . "' OR " . $table . "title='$title')";
				}

				// Search By title
				if ($sort == '20') {
					$title = $data[count($args) - 1] * 1;
					return "(" . $table . "title='$title')";
				}

				// Search By title AND Month
				if (in_array($sort, array('23', '023', '230', '0230'))) {
					$title = $data[0] * 1;
					$month = $data[1];
					return "(" . $table . "title='$title' AND MONTH(" . $table . "inserted) = '" . $month . "')";
				}

				// Search By title AND Month AND Year
				if (in_array($sort, array('232', '0232'))) {
					$title = $data[count($args) - 3] * 1;
					$month = $data[count($args) - 2];
					$year = $data[count($args) - 1];
					return "(" . $table . "title='$title' AND MONTH(" . $table . "inserted) = '" . $month . "' AND YEAR(" . $table . "inserted) = '" . $year . "')";
				}

				// Search By title OR Month OR Year
				if ($sort == '3') {
					$title = $data[0] * 1;
					$month = $data[0];
					$year = $data[0];
					return "(" . $table . "title='$title' OR MONTH(" . $table . "inserted) = '" . $month . "' OR YEAR(" . $table . "inserted) = '" . $year . "')";
				}

				// Search By Month
				if (in_array($sort, array('03', '30', '030'))) {
					$month = $sort == '30' ? $data[0] : $data[1];
					return "(MONTH(" . $table . "inserted) = '" . $month . "')";
				}

				// Search By Month AND Year
				if (in_array($sort, array('32', '032'))) {
					$month = $data[count($args) - 2];
					$year = $data[count($args) - 1];
					return "(MONTH(" . $table . "inserted) = '" . $month . "' AND YEAR(" . $table . "inserted) = '" . $year . "')";
				}

				// Search By Year
				if ($sort == '04') {
					$year = $data[count($args) - 1];
					return "(YEAR(" . $table . "inserted) = '" . $year . "')";
				}

				return "(" . $table . "title='" . $data[0] . "')";
			}
		}

		if ($field == 'inserted') {
			return "" . $table . "inserted LIKE '%$search%'";
		}
	}

	protected static function table()
	{
		$_limitx = 300;
		$data = array();
		$_index = array();
		$args = self::_array();

		$start = intval(parent::$page);
		$nLimit = intval(parent::$limit);

		$type = str_replace("transaction_", "", self::$type);

		$_sum = sobad_post::count("var='$type' AND trash='0'");
		if ($_sum > $_limitx) {
			$_lmt = ($_sum - $_limitx);

			$_pst = sobad_post::get_all(array('ID'), "AND var='$type' AND trash='0' LIMIT $_lmt,$_limitx");
			foreach ($_pst as $key => $val) {
				$_index[] = $val['ID'];
			}
		}

		$_search = '';
		$_index = implode(',', $_index);
		$kata = '';
		$where = empty($_index) ? "AND var='$type' AND `" . base . "post`.trash='0'" : "AND `" . base . "post`.ID IN ($_index)";
		if (parent::$search) {
			$src = parent::like_search($args, $where);
			$cari = $src[0];
			$where = $src[0];
			$kata = $src[1];
			$_search = $src[2];
		} else {
			$cari = $where;
		}

		$limit = ' ORDER BY post_date DESC LIMIT ' . intval(($start - 1) * $nLimit) . ',' . $nLimit;
		$where .= $limit;

		$object = self::$table;
		$sum_data = $object::count("1=1 " . $cari, $args, self::$post);
		$args = $object::get_all($args, $where, 'order');

		$data['data'] = array('data' => $kata, 'value' => $_search, 'type' => self::$type);
		$data['search'] = array('Semua', 'Order No.', 'Pelanggan', 'Kurir', 'No. Resi');
		$data['class'] = '';
		$data['table'] = array();
		$data['page'] = array(
			'func'	=> '_pagination',
			'data'	=> array(
				'start'		=> $start,
				'qty'		=> $sum_data,
				'limit'		=> $nLimit,
				'type' 		=> self::$type
			)
		);

		$no = ($start - 1) * $nLimit;
		foreach ($args as $key => $val) {
			$no += 1;
			$id = $val['ID'];

			$print = array(
				'ID'	=> 'view_' . $id,
				'func'	=> '_invoice',
				'color'	=> 'yellow',
				'icon'	=> 'fa fa-print',
				'label'	=> 'invoice',
				'script' => 'sobad_button_pre(this)',
				'type' 	=> self::$type
			);

			$edit = array(
				'ID'	=> 'edit_' . $id,
				'func'	=> '_edit',
				'color'	=> 'blue',
				'icon'	=> 'fa fa-edit',
				'label'	=> 'edit',
				'type' 	=> self::$type
			);

			$_idx = $val['status'] == 0 ? 'del_' : 'trash_';
			$_func = $val['status'] == 0 ? '_delete' : '_trash';

			$hapus = array(
				'ID'	=> $_idx . $id,
				'func'	=> $_func,
				'color'	=> 'red',
				'icon'	=> 'fa fa-trash',
				'label'	=> 'hapus',
				'type' 	=> self::$type
			);

			$button = array(
				'label'		=> 'Button',
				'color'		=> 'default',
				'button'	=> array(
					print_button($print),
					edit_button($edit),
					hapus_button($hapus),
				)
			);

			$status = '';
			switch ($val['status']) {
				case 0:
					$status = '#f94d53;'; // Merah
					break;

				case 1:
					$status = '#26a69a;'; // Hijau
					break;

				case 2:
					$status = '#666;'; // Abu
					break;

				case 3:
					$status = '#f5b724;'; // Orange
					break;

				default:
					$status = '#fff;'; // Putih
					break;
			}

			$status = '<i class="fa fa-circle" style="color:' . $status . '"></i>';
			if ($val['status'] > 0 && $val['reff'] == 1) {
				$status .= '&nbsp;<i class="fa fa-circle" style="color:#f94d53;"></i>';
			}

			$courier = $val['_expedition'];
			$comp = sobad_company::get_id($courier, array('name'));

			$check = array_filter($comp);
			if (!empty($check)) {
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
					'10%',
					self::_post_title($val['title'], $val['meta_note_type'], $val['inserted']),
					true
				),
				'Customer'	=> array(
					'left',
					'auto',
					$val['name_cont'],
					true
				),
				'Tanggal'	=> array(
					'left',
					'12%',
					format_date_id($val['post_date']),
					true
				),
				'Kurir'		=> array(
					'left',
					'15%',
					$courier,
					true
				),
				'No. Resi'	=> array(
					'left',
					'12%',
					$val['_resi'],
					true
				),
				'Status'	=> array(
					'center',
					'7%',
					$status,
					true
				),
				'Button'	=> array(
					'center',
					'10%',
					dropdown_button($button),
					false
				)
			);
		}

		return $data;
	}

	private static function head_title()
	{
		$args = array(
			'title'	=> 'Transaksi <small>data transaksi</small>',
			'link'	=> array(
				0	=> array(
					'func'	=> self::$object,
					'label'	=> 'transaksi'
				)
			),
			'date'	=> false,
			'modal'	=> 4
		);

		return $args;
	}

	protected static function get_box()
	{
		$data = self::table();

		$box = array(
			'label'		=> 'Data transaksi',
			'tool'		=> '',
			'action'	=> parent::action(),
			'func'		=> 'sobad_table',
			'data'		=> $data
		);

		return $box;
	}

	protected static function layout()
	{
		self::$type = 'transaction_order';
		$box = self::get_box();

		$object = self::$table;
		$tabs = array(
			'tab'	=> array(
				0		=> array(
					'key'	=> 'transaction_order',
					'label'	=> 'Order',
					'qty'	=> $object::count("var='order'")
				),
				array(
					'key'	=> 'transaction_non_order',
					'label'	=> 'Non Order',
					'qty'	=> $object::count("var='non_order'")
				),
			),
			'func'	=> '_portlet',
			'data'	=> $box
		);

		$opt = array(
			'title'		=> self::head_title(),
			'style'		=> array('quotation_marketing', '_style'),
			'script'	=> array('')
		);

		return tabs_admin($opt, $tabs);
	}

	public static function _conv_type($type = '')
	{
		$type = str_replace("retail_", '', $type);
		intval($type);

		$types = array(0 => 'Prepare', 'Dikirim', 'Process', 'Tunggu Kirim');
		$label = isset($types[$type]) ? $types[$type] : $types;

		return $label;
	}

	// ----------------------------------------------------------
	// Form data category -----------------------------------
	// ----------------------------------------------------------
	public static function add_form($func = '', $load = 'sobad_portlet')
	{
		$type = isset($_POST['type']) ? $_POST['type'] : '';
		$max = str_replace('transaction_', '', $type);

		$no = quotation_marketing::_get_max($max);
		$vals = array(0, $no + 1, 0, '', '', date('Y-m-d'), 0, 0, 0, 0, 0, date('Y-m-d'), 0);
		$vals = array_combine(self::_array(), $vals);

		if ($func == 'add_0') {
			$func = '_add_db';
		}

		$vals['meta_note_type'] = '';

		$args = array(
			'title'		=> 'Tambah data kategori',
			'button'	=> '_btn_modal_save',
			'status'	=> array(
				'link'		=> $func,
				'load'		=> $load,
				'type'		=> $type
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

		$type = isset($_POST['type']) ? $_POST['type'] : '';
		$button = in_array($vals['status'], array(0, 2)) ? '_btn_modal_save' : '';

		$args = array(
			'title'		=> 'Edit data kategori',
			'button'	=> $button,
			'status'	=> array(
				'link'		=> '_update_db',
				'load'		=> 'sobad_portlet',
				'type'		=> $type
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

		// GET courier ------
		$courier = sobad_company::get_expeditions(array('ID', 'name'));
		$courier = convToOption($courier, 'ID', 'name');

		// GET channel ------
		$channel = sobad_meta::_gets('channel', array('ID', 'meta_value'));
		$channel = convToOption($channel, 'ID', 'meta_value');

		// GET suplier ------
		$whr = empty($vals['ID']) ? "" : "AND ID='" . $vals['contact'] . "'";
		$customer = sobad_company::get_customers(array('ID', 'name'), $whr . " LIMIT 0,20");
		$customer = convToOption($customer, 'ID', 'name');

		// GET payment method
		$payment = array();
		$account = sobad_account::get_all(array('ID', 'name', 'bank'), "AND trash='0'");
		foreach ($account as $key => $val) {
			$name = $val['name'];
			if ($val['bank'] > 0) {
				$bank = account_purchase::_get_bank($val['bank']);
				$name = $bank['bank'] . ' a.n. <span>' . $name . '</span>';
			}
			$payment[$val['ID']] = $name;
		}

		// GET button add supplier
		$add_cust = array(
			'ID'	=> 'add_0',
			'func'	=> '_form_customer',
			'class'	=> '',
			'color'	=> 'green',
			'icon'	=> 'fa fa-plus',
			'label'	=> 'Add',
			'type'	=> 'supplier',
			'spin'	=> false
		);

		// GET button add channel
		$add_channel = array(
			'ID'	=> 'add_0',
			'func'	=> '_form_channel',
			'class'	=> '',
			'color'	=> 'green',
			'icon'	=> 'fa fa-plus',
			'label'	=> 'Add',
			'type'	=> 'channel',
			'spin'	=> false
		);

		// GET button add channel
		$add_courier = array(
			'ID'	=> 'add_0',
			'func'	=> '_form_courier',
			'class'	=> '',
			'color'	=> 'green',
			'icon'	=> 'fa fa-plus',
			'label'	=> 'Add',
			'type'	=> 'expedition',
			'spin'	=> false
		);

		// GET saldo akun
		if ($vals['payment'] == 0) {
			reset($payment);
			$vals['payment'] = key($payment);
		}

		$saldo = sobad_account::get_id($vals['payment'], array('balance'));
		$saldo = $saldo[0]['balance'];

		// Pay
		$pay = sobad_post::get_all(array('price'), "AND `" . base . "post`.var='paid' AND `" . base . "post`.reff='" . $vals['ID'] . "'", 'paid');
		$check = array_filter($pay);
		if (empty($check)) {
			$pay = 0;
		} else {
			$pay = $pay[0]['price'];
		}

		$_type = isset($_POST['type']) ? $_POST['type'] : '';
		if ($_type == 'transaction_non_order') {
			$_func = 'set_order_sell';
		} else if ($_type == 'transaction_order') {
			$_func = 'set_nonOrder_sell';
		} else {
			$_func = '';
		}

		$read = $vals['ID'] == 0 ? '' : 'readonly';
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
				'key'			=> 'status',
				'value'			=> $vals['status']
			),
			array(
				'id'			=> 'total_purchase',
				'func'			=> 'opt_hidden',
				'type'			=> 'hidden',
				'key'			=> '_total',
				'value'			=> 0
			),
			array(
				'func'			=> 'opt_hidden',
				'type'			=> 'hidden',
				'key'			=> 'title',
				'value'			=> $vals['title']
			),
			array(
				'func'			=> 'opt_select',
				'data'			=> $channel,
				'key'			=> 'type',
				'label'			=> 'Channel',
				'button'		=> apply_button($add_channel),
				'class'			=> 'input-circle',
				'select'		=> $vals['type'],
				'status'		=> 'data-sobad="' . $_func . '" data-load="order_sell" data-attribute="val" ' . $read
			),
			array(
				'id'			=> 'order_sell',
				'func'			=> 'opt_input',
				'type'			=> 'text',
				'key'			=> 'name',
				'label'			=> 'No Order',
				'class'			=> 'input-circle',
				'value'			=> self::_post_title($vals['title'], $vals['meta_note_type'], $vals['inserted']),
				'data'			=> 'placeholder="No Order" readonly'
			),
			array(
				'id'			=> 'customer',
				'func'			=> 'opt_select',
				'data'			=> $customer,
				'key'			=> 'contact',
				'label'			=> 'Customer',
				'button'		=> apply_button($add_cust),
				'class'			=> 'input-circle',
				'select'		=> $vals['contact'],
				'searching'		=> true
			),
			array(
				'func'			=> 'opt_input',
				'type'			=> 'date',
				'key'			=> 'post_date',
				'label'			=> 'Tanggal',
				'class'			=> 'input-circle',
				'value'			=> $vals['post_date'],
				'data'			=> 'placeholder="Tanggal Nota"'
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
				'id'			=> 'expedition',
				'func'			=> 'opt_select',
				'data'			=> $courier,
				'key'			=> '_expedition',
				'label'			=> 'Kurir',
				'button'		=> apply_button($add_courier),
				'class'			=> 'input-circle',
				'select'		=> $vals['_expedition'],
				'searching'		=> true
			),
			array(
				'func'			=> 'opt_select',
				'data'			=> $payment,
				'key'			=> 'payment',
				'label'			=> 'Akun',
				'class'			=> 'input-circle',
				'select'		=> $vals['payment'],
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
				'id'			=> 'pay_purchase',
				'func'			=> 'opt_input',
				'type'			=> 'price',
				'key'			=> 'pay',
				'label'			=> 'Pembayaran',
				'class'			=> 'input-circle',
				'value'			=> format_nominal($pay),
				'data'			=> 'placeholder="Harga"'
			)
		);

		$args['func'] = array('sobad_form', '_portlet_item');
		$args['data'] = array($data, $vals['ID']);

		return modal_admin($args);
	}

	public static function _portlet_item($id = 0)
	{
		$data = self::_table_item($id);

		$box = array(
			'ID'		=> 'portlet_item',
			'label'		=> 'Data barang',
			'tool'		=> '',
			'action'	=> self::_item_action($id),
			'func'		=> 'sobad_table',
			'data'		=> $data['data']
		);

		theme_layout('_portlet', $box);

?>
		<script type="text/javascript">
			$('#total_purchase').val('<?php print($data['paid']); ?>');

			$('#customer').parent().children('.bs-select').children('div.dropdown-menu').children('.bs-searchbox').children().on('change', function() {
				sobad_loading('.bs-select ul.selectpicker');

				data = "ajax=_load_customer_retail&object=" + object + "&data=0&type=" + this.value;
				sobad_ajax('#customer', data, select_option_search, false);
			});

			function select_option_search(data, id) {
				$(id).html(data);
				$('.bs-select').selectpicker('refresh');

				$('.bs-select ul.selectpicker .blockUI').remove();
			}

			function update_payment(value) {
				//$('#pay_purchase').val(number_format(value));
				$('#total_purchase').val(number_format(value));
			}

			function set_purchase(args, id) {
				$(id).attr(args[0], args[1]);
				$('a.list_purchase').attr('data-type', args[1]);
			}

			function add_purchase(val) {
				var id = $(val).attr('data-load');

				var ajx = $(val).attr("data-sobad");
				var lbl = $(val).attr('data-purchase');
				var msg = $(val).attr('data-alert');

				sobad_load_togle($(val).attr('href'));

				data = "ajax=" + ajx + "&object=" + object + "&data=" + lbl;
				sobad_ajax('#' + id, data, 'html', msg);
			}
		</script>

		<script type="text/javascript">
			function set_apply_product(val) {
				var ajx = '_get_itemProduct';
				var id = '#name_selling';
				var data = $(val).attr('id');

				sobad_load('box-product');

				data = "ajax=" + ajx + "&object=" + object + "&data=" + data;
				sobad_ajax(id, data, set_data_input, false);
			}

			function set_data_input(data, id) {
				$(id).val(data['name']);
				$('#item_selling').val(data['ID']);
				$('#sku_selling').val(data['product_code']);
				$('#price_selling').val(data['price']);
				$('#img-product').attr('src', 'asset/img/upload/' + data['image']);

				$('#unit_selling').val(data['unit']);
				$('.bs-select').selectpicker('refresh');

				$('#box-product .blockUI').remove();
			}
		</script>
	<?php
	}

	protected static function _table_item($id = 0)
	{
		$whr = empty($id) ? "AND temporary='selling_" . get_id_user() . "'" : '';
		$args = sobad_post::get_transactions($id, array('ID', 'barang', 'qty', 'price', 'discount', 'unit', 'note','extends'), $whr);

		$data['class'] = 'purchase';
		$data['table'] = array();

		$no = 0;
		$paid = 0;
		foreach ($args as $key => $val) {
			$no += 1;
			$edit = array(
				'ID'	=> 'edit_' . $val['ID'],
				'func'	=> '_editItem',
				'color'	=> 'blue',
				'icon'	=> 'fa fa-edit',
				'label'	=> 'edit',
				'type'	=> $id,
				'spin'	=> false
			);

			$hapus = array(
				'ID'	=> 'del_' . $val['ID'],
				'func'	=> '_deleteItem',
				'color'	=> 'red',
				'icon'	=> 'fa fa-trash',
				'load'	=> 'portlet_item',
				'label'	=> 'hapus',
				'type'	=> $id
			);

			$nominal = ($val['price'] - $val['discount']);
			$total = $nominal * $val['qty'];

			$ppn = $val['note']==1 ? $val['extends'] : 0;
			$total += $ppn;

			$paid += $total;

			$picture = sobad_item::get_image($val['picture_bara']);

			$data['table'][$key]['tr'] = array('');
			$data['table'][$key]['td'] = array(
				'no'			=> array(
					'center',
					'5%',
					$no,
					true
				),
				'Image'			=> array(
					'center',
					'10%',
					'<img src="asset/img/upload/' . $picture[0] . '" style="width:100%;height:auto;">',
					true
				),
				'Nama'			=> array(
					'left',
					'auto',
					'<strong>' . $val['name_bara'] . '</strong><br>' . $val['product_code_bara'],
					true
				),
				'Qty'			=> array(
					'left',
					'10%',
					$val['qty'] . ' ' . $val['unit'],
					true
				),
				'Harga'			=> array(
					'right',
					'10%',
					format_nominal($nominal) . '/' . $val['unit'],
					true
				),
				'Pajak'			=> array(
					'right',
					'10%',
					format_nominal($ppn),
					true
				),
				'Total'			=> array(
					'right',
					'10%',
					'Rp. ' . format_nominal($total),
					true
				),
				'Edit'			=> array(
					'center',
					'10%',
					_modal_button($edit, 2),
					false
				),
				'Hapus'			=> array(
					'center',
					'10%',
					hapus_button($hapus),
					false
				)
			);
		}

		$response = array(
			'data'	=> $data,
			'paid'	=> $paid
		);

		return $response;
	}

	private static function _item_action($id = 0)
	{
		$add = array(
			'ID'	=> 'add_purchase',
			'func'	=> '_form_add',
			'color'	=> 'btn-default',
			'icon'	=> 'fa fa-plus',
			'label'	=> 'Tambah',
			'script' => 'add_purchase(this)',
			'status' => 'data-purchase="' . $id . '"'
		);

		return apply_button($add);
	}

	public static function set_balance_akun($id = 0)
	{
		$akun = sobad_account::get_id($id, array('balance'));
		$akun = format_nominal($akun[0]['balance']);

		return empty($akun) ? $akun . ' ' : $akun;
	}

	public static function set_order_sell($id = 0)
	{
		return self::_set_noOrder($id, 'order');
	}

	public static function set_nonOrder_sell($id = 0)
	{
		return self::_set_noOrder($id, 'non_order');
	}

	private static function _set_noOrder($id = 0, $type = '')
	{
		$no = quotation_marketing::_get_max($type);
		$no += 1;

		$meta = sobad_meta::get_id($id, array('meta_note'));
		$meta = $meta[0];

		return self::_post_title($no, $meta['meta_note']);
	}

	// ------------------------------------------------------------
	// ----- Supplier Add Detail --------------------------------
	// ------------------------------------------------------------

	public static function _form_courier()
	{
		return company_marketing::add_form('_add_courier', 'expedition', 4);
	}

	public static function _add_courier($args = array())
	{
		$_POST['type'] = 'company_4';
		return company_marketing::_add_db($args, '_load_courier', 'transaksi_retail');
	}

	public static function _load_courier($search = '')
	{
		$supply = sobad_company::get_expeditions(array('ID', 'name'));

		$opt = '';
		foreach ($supply as $ky => $val) {
			$opt .= '<option value="' . $val['ID'] . '"> ' . $val['name'] . ' </option>';
		}

		return empty($opt) ? '&nbsp;' : $opt;
	}

	public static function _form_customer()
	{
		return company_marketing::add_form('_add_customer', 'customer', 1);
	}

	public static function _add_customer($args = array())
	{
		$_POST['type'] = 'company_1';
		return company_marketing::_add_db($args, '_load_customer', 'transaksi_retail');
	}

	public static function _load_customer($data = array())
	{
		$index = $data['index'];
		$supply = sobad_company::get_customers(array('ID', 'name'), "AND ID='$index' LIMIT 0,20");

		$opt = '';
		foreach ($supply as $ky => $val) {
			$opt .= '<option value="' . $val['ID'] . '"> ' . $val['name'] . ' </option>';
		}

		return empty($opt) ? '&nbsp;' : $opt;
	}

	public static function _load_customer_retail($id = 0)
	{
		return quotation_marketing::_set_customer($id);
	}

	public static function _form_channel()
	{
		return channel_retail::add_form('_add_channel', 'order_sell', 1);
	}

	public static function _add_channel($args = array())
	{
		return channel_retail::_add_db($args, '_load_channel', 'transaksi_retail');
	}

	public static function _load_channel()
	{
		$supply = sobad_meta::_gets('channel', array('ID', 'meta_value'));

		$opt = '';
		foreach ($supply as $ky => $val) {
			$opt .= '<option value="' . $val['ID'] . '"> ' . $val['meta_value'] . ' </option>';
		}

		return $opt;
	}

	// ----------------------------------------------------------
	// Form2 data purchase --------------------------------------
	// ----------------------------------------------------------

	public static function _form_add($id = '')
	{
		$vals = array(
			'ID'				=> 0,
			'post'				=> $id,
			'barang'			=> 0,
			'price'				=> 0,
			'qty'				=> 1,
			'discount'			=> 0,
			'unit'				=> 'pcs',
			'note'				=> 0,
			'name_bara'			=> '',
			'var_bara'			=> 2,
			'product_code_bara'	=> '',
			'picture_bara'		=> ''
		);

		$args = array(
			'title'		=> 'Tambah data barang',
			'button'	=> '_btn_modal_save',
			'status'	=> array(
				'link'		=> '_addItem',
				'load'		=> 'portlet_item'
			)
		);

		return self::_form_item($args, $vals);
	}

	private static function _form_edit($vals = array(), $type = 0)
	{
		$check = array_filter($vals);
		if (empty($check)) {
			return '';
		}

		$vals['note'] = empty($vals['note']) ? 0 : $vals['note'];

		$args = array(
			'title'		=> 'Edit data barang',
			'button'	=> '_btn_modal_save',
			'status'	=> array(
				'link'		=> '_updateItem',
				'load'		=> 'portlet_item'
			)
		);

		return self::_form_item($args, $vals);
	}

	protected static function _form_item($args = array(), $vals = array())
	{
		$check = array_filter($args);
		if (empty($check)) {
			return '';
		}

		// Button Get Item
		$add_item = array(
			'ID'	=> 'add_0',
			'func'	=> '_add_product',
			'class'	=> '',
			'color'	=> 'green',
			'icon'	=> 'fa fa-plus',
			'label'	=> 'Add',
			'type'	=> 'supplier',
			'spin'	=> false
		);

		// GET
		$units = unit::_get(array('volume', 'capacity', 'weight', 'custom'));
		$units = unit::conv_to_option($units);

		$sts = $vals['barang'] == 0 ? '' : 'disabled';

		$data = array(
			'cols'	=> array(3, 8),
			0 => array(
				'func'			=> 'opt_hidden',
				'type'			=> 'hidden',
				'key'			=> '_id',
				'value'			=> $vals['ID']
			),
			array(
				'func'			=> 'opt_hidden',
				'type'			=> 'hidden',
				'key'			=> 'reff',
				'value'			=> $vals['post']
			),
			array(
				'id'			=> 'item_selling',
				'func'			=> 'opt_hidden',
				'type'			=> 'hidden',
				'key'			=> 'barang',
				'value'			=> $vals['barang']
			),
			array(
				'id'			=> 'name_selling',
				'func'			=> 'opt_input',
				'type'			=> 'text',
				'key'			=> 'name',
				'label'			=> 'Nama',
				'class'			=> 'input-circle',
				'value'			=> $vals['name_bara'],
				'data'			=> 'placeholder="Nama" readonly',
				'button'		=> _modal_button($add_item, 3)
			),
			array(
				'id'			=> 'sku_selling',
				'func'			=> 'opt_input',
				'type'			=> 'text',
				'key'			=> 'product_code',
				'label'			=> 'SKU',
				'class'			=> 'input-circle',
				'value'			=> $vals['product_code_bara'],
				'data'			=> 'placeholder="Nama" readonly',
			),
			array(
				'func'			=> 'opt_input',
				'id'			=> 'price_selling',
				'type'			=> 'price',
				'key'			=> 'price',
				'label'			=> 'Harga',
				'class'			=> 'input-circle',
				'value'			=> format_nominal($vals['price']),
				'data'			=> 'placeholder="Harga"'
			),
			array(
				'func'			=> 'opt_input',
				'id'			=> 'qty_selling',
				'type'			=> 'price',
				'key'			=> 'qty',
				'label'			=> 'Jumlah',
				'class'			=> 'input-circle',
				'value'			=> format_nominal($vals['qty']),
				'data'			=> 'placeholder="Quantity"'
			),
			array(
				'func'			=> 'opt_select',
				'id'			=> 'unit_selling',
				'group'			=> true,
				'data'			=> $units,
				'key'			=> 'unit',
				'label'			=> 'Unit',
				'class'			=> 'input-circle',
				'select'		=> $vals['unit'],
				'searching'		=> true,
				'status'		=> ''
			),
			array(
				'func'			=> 'opt_input',
				'type'			=> 'price',
				'key'			=> 'discount',
				'label'			=> 'Diskon',
				'class'			=> 'input-circle',
				'value'			=> format_nominal($vals['discount']),
				'data'			=> 'placeholder="Discount" '
			),
			array(
				'func'			=> 'opt_box',
				'type'			=> 'radio',
				'key'			=> 'note',
				'label'			=> 'PPN',
				'inline'		=> true,
				'value'			=> @$vals['note'],
				'data'			=> array(
					0	=> array(
						'title'		=> 'Non PPN',
						'value'		=> '0'
					),
					1	=> array(
						'title'		=> 'Exclude PPN',
						'value'		=> '1'
					),
					2	=> array(
						'title'		=> 'Include PPN',
						'value'		=> '2'
					),
				)
			),
		);

		$picture = sobad_item::get_image($vals['picture_bara']);

		$data = array(
			'image' => $picture[0],
			'data'	=> $data,
		);

		$args['func'] = array('_formItem_layout');
		$args['data'] = array($data);

		return modal_admin($args);
	}

	public static function _formItem_layout($args = array())
	{
		$image = empty($args['image']) ? 'no-image.png' : $args['image'];
		$data = $args['data'];

	?>
		<style type="text/css">
			.box-image-show>img {
				margin-left: 5%;
			}
		</style>

		<div id="box-product" class="row">
			<div class="col-md-3 box-image-show">
				<img src="asset/img/upload/<?php print($image); ?>" style="width:100%" id="img-product">
			</div>
			<div class="col-md-9">
				<?php theme_layout('sobad_form', $data); ?>
			</div>
		</div>
<?php
	}

	public static function _add_product($id = 0)
	{
		$id = str_replace('add_', '', $id);
		intval($id);
		return parent::insert_to($id);
	}

	public static function _get_itemProduct($id = 0)
	{
		$id = str_replace('apply_', '', $id);
		intval($id);

		$item = sobad_item::get_id($id, array('ID', 'product_code', 'name', 'price', 'picture'));
		$item = $item[0];

		$item['price'] = format_nominal($item['price']);
		$item['unit'] = 'pcs';

		$image = sobad_item::get_image($item['picture']);
		$item['image'] = $image[0];
		return $item;
	}

	// ------------------------------------------------------------
	// ----- View Table Item --------------------------------------
	// ------------------------------------------------------------	

	public static function _view($id = 0)
	{
		$id = str_replace('view_', '', $id);
		$table = self::_table_order($id);

		$args = array(
			'title'		=> 'View data Order',
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

	public static function _table_order($id = 0)
	{
		$data = array();
		$data['class'] = '';
		$data['table'] = array();

		$args = sobad_post::get_transactions($id, array('barang', 'qty', 'price', 'discount','note','extends'));

		$no = 0;
		foreach ($args as $key => $val) {
			$no += 1;

			$harga = $val['price'];
			$discount = $val['discount'] <= 100 ? $harga * ($val['discount'] / 100) : $val['discount'];
			$total = ($harga - $discount) * $val['qty'];

			$ppn = $val['note']=="1" ? $val['extends'] : 0;
			$total += $ppn;

			$data['table'][$key]['tr'] = array('');
			$data['table'][$key]['td'] = array(
				'No'		=> array(
					'center',
					'5%',
					$no,
					true
				),
				'Item'	=> array(
					'left',
					'auto',
					$val['name_bara'],
					true
				),
				'Jumlah'		=> array(
					'right',
					'10%',
					format_nominal($val['qty']) . ' pcs',
					true
				),
				'Harga'		=> array(
					'left',
					'15%',
					'Rp. ' . format_nominal($harga),
					true
				),
				'Diskon'		=> array(
					'left',
					'15%',
					'Rp. ' . format_nominal($discount),
					true
				),
				'Pajak'		=> array(
					'left',
					'10%',
					'Rp. ' . format_nominal($ppn),
					true
				),
				'Total'		=> array(
					'right',
					'15%',
					'Rp. ' . format_nominal($total),
					true
				),
			);

			if(!$persen['status']){
				unset($data['table'][$key]['td']['Pajak']);
			}
		}

		return $data;
	}

	// -------------------------------------------------------------
	// Database ----------------------------------------------------
	// -------------------------------------------------------------

	public static function _deleteItem($id = 0)
	{
		$id = str_replace('del_', '', $id);
		intval($id);

		// hapus transaksi
		$q = sobad_db::_delete_single($id, '' . base . 'transaksi');
		$type = $_POST['type'];

		if ($q !== 0) {
			$data = self::_table_item($type);
			$table = table_admin($data['data']);
			$table .= '<script>update_payment(' . $data['paid'] . ');</script>';
			return $table;
		}
	}

	public static function _editItem($id)
	{
		$id = str_replace('edit_', '', $id);
		intval($id);

		$args = array('ID', 'post', 'barang', 'price', 'qty', 'discount', 'unit', 'note');
		$q = sobad_post::get_transaction($id, $args);

		if ($q === 0) {
			return '';
		}

		return self::_form_edit($q[0], $_POST['type']);
	}

	public static function _updateItem($args = array())
	{
		$args = sobad_asset::ajax_conv_json($args);

		$id = $args['_id'];
		$reff = $args['reff'];
		unset($args['_id']);
		unset($args['reff']);

		if (isset($args['search'])) {
			$src = array(
				'search'	=> $args['search'],
				'words'		=> $args['words']
			);

			unset($args['search']);
			unset($args['words']);
		}

		$qty = $args['qty'];
		$price = $args['price'];
		$discount = $args['discount'];

		$total = ($price - $discount) * $qty;

		$ppn = 0;
		$persen = sobad_company::_check_profile();
		if($persen['status']){
			$persen = $persen['_ppn'];

			if($args['note']==1){
				$ppn = $total * $persen / 100;	
			}else if($args['note']==2){
				$ppn = (100 * $total) / (100 + $persen);
				$ppn = round($total - $ppn,0);
			}
		}

		// Update Transaksi
		$_args = array(
			'barang'	=> $args['barang'],
			'qty'		=> $qty,
			'unit'		=> $args['unit'],
			'price'		=> $price,
			'discount'	=> $discount,
			'note'		=> $args['note'],
			'extends'	=> $ppn
		);

		$q = sobad_db::_update_single($id, '' . base . 'transaksi', $_args);

		if ($q !== 0) {
			$data = self::_table_item($reff);
			$table = table_admin($data['data']);
			$table .= '<script>update_payment(' . $data['paid'] . ');</script>';

			return $table;
		}
	}

	public static function _addItem($args = array())
	{
		$args = sobad_asset::ajax_conv_json($args);

		$id = $args['_id'];
		$reff = $args['reff'];
		unset($args['_id']);
		unset($args['reff']);

		if (isset($args['search'])) {
			$src = array(
				'search'	=> $args['search'],
				'words'		=> $args['words']
			);

			unset($args['search']);
			unset($args['words']);
		}

		$qty = $args['qty'];
		$price = $args['price'];
		$discount = $args['discount'];

		$total = ($price - $discount) * $qty;

		$ppn = 0;
		$persen = sobad_company::_check_profile();
		if($persen['status']){
			$persen = $persen['_ppn'];

			if($args['note']==1){
				$ppn = $total * $persen / 100;	
			}else if($args['note']==2){
				$ppn = (100 * $total) / (100 + $persen);
				$ppn = round($total - $ppn,0);
			}
		}

		$note = empty($reff) ? 'selling_' . get_id_user() : '';
		$_args = array(
			'post'		=> $reff,
			'barang'	=> $args['barang'],
			'qty'		=> $qty,
			'unit'		=> $args['unit'],
			'price'		=> $price,
			'discount'	=> $discount,
			'note'		=> $args['note'],
			'temporary'	=> $note,
			'extends'	=> $ppn
		);

		$q = sobad_db::_insert_table('' . base . 'transaksi', $_args);

		if ($q !== 0) {
			$data = self::_table_item($reff);
			$table = table_admin($data['data']);
			$table .= '<script>update_payment(' . $data['paid'] . ');</script>';

			return $table;
		}
	}

	// ----------------------------------------------------------
	// Function retail to database ------------------------------
	// ----------------------------------------------------------

	public static function _callback($args = array(), $_args = array())
	{
		$args['_total'] = clear_format($args['_total']);
		$args['_shipping_price'] = 0;

		$_var = isset($_POST['type']) ? $_POST['type'] : 'xxx_order';
		$_var = str_replace('transaction_', '', $_var);

		$args['var'] = $_var;
		$args['user'] = get_id_user();

		$args['reff'] = 1;
		if ($args['pay'] >= $args['_total']) {
			$args['reff'] = 0;
		}

		if (!empty($args['_resi'])) {
			$_tot_belanja = 0;

			if ($args['ID'] <= 0) {
				$keyword = 'selling_' . get_id_user();
				$belanja = sobad_post::get_transactions(0, array('price', 'discount', 'qty'), "AND temporary='$keyword'");
			} else {
				$belanja = sobad_post::get_transactions($args['ID'], array('price', 'discount', 'qty'));
			}

			foreach ($belanja as $_key => $_val) {
				$_tot_belanja += (($_val['price'] - $_val['discount']) * $_val['qty']);
			}

			$args['_shipping_price'] = $args['_total'] - $_tot_belanja;
		}

		// Reset Saldo
		if ($args['ID'] > 0) {
			$idx = $args['ID'];
			$pays = sobad_post::get_all(array('ID', 'payment', 'id_join', 'price'), "AND `" . base . "post`.var='paid' AND `" . base . "post`.reff='$idx'", 'paid');

			$akun = sobad_account::get_id($pays[0]['payment'], array('balance'));
			$akun = $akun[0];

			$saldo = $akun['balance'] - $pays[0]['price'];
			sobad_db::_update_single($pays[0]['payment'], '' . base . 'account', array('ID' => $pays[0]['payment'], 'balance' => $saldo));
		}

		// Penambahan Saldo
		$akun = sobad_account::get_id($args['payment'], array('balance'));
		$akun = $akun[0];

		$saldo = $akun['balance'] + $args['pay'];
		sobad_db::_update_single($args['payment'], '' . base . 'account', array('ID' => $args['payment'], 'balance' => $saldo));

		// Update Pembayaran
		if ($args['ID'] > 0) {
			sobad_db::_update_single($pays[0]['ID'], '' . base . 'post', array('payment' => $args['payment'], 'updated' => date('Y-m-d H:i:s')));
			sobad_db::_update_single($pays[0]['id_join'], '' . base . 'transaksi', array('ID' => $pays[0]['id_join'], 'price' => $args['pay']));
		} else {
			$args['updated'] = '0000-00-00 00:00:00';
		}

		return $args;
	}

	protected static function _addDetail($data = array(), $args = array())
	{
		$idx = $data['index'];
		$args = sobad_asset::ajax_conv_json($args);
		// Update Detail Transaksi
		$keyword = 'selling_' . get_id_user();
		$q = sobad_db::_update_multiple("post='0' AND temporary='$keyword'", '' . base . 'transaksi', array('post' => $idx, 'temporary' => ''));

		// Insert Post Pembayaran
		$no = quotation_marketing::_get_max('paid');
		$p = sobad_db::_insert_table('' . base . 'post', array(
			'title'			=> $no + 1,
			'user'			=> get_id_user(),
			'payment'		=> $args['payment'],
			'post_date'		=> $args['post_date'],
			'updated'		=> '0000-00-00 00:00:00',
			'var'			=> 'paid',
			'status'		=> 1,
			'reff'			=> $idx
		));

		// Add Detail pembayaran
		sobad_db::_insert_table('' . base . 'transaksi', array(
			'post'		=> $p,
			'qty'		=> 1,
			'price'		=> $args['pay'],
			'unit'		=> 'IDR'
		));

		return $p;
	}

	// ----------------------------------------------------------
	// Print data Invoice ---------------------------------------
	// ----------------------------------------------------------

	public static function _invoice($id)
	{
		$_SESSION[_prefix . 'development'] = 0;
		$id = str_replace('view_', '', $id);
		intval($id);

		$quo = array(
			'ID',
			'company',
			'contact',
			'type',
			'title',
			'post_date',
			'inserted',
			'type',
			'reff',
			'user',
			'_resi',
			'_shipping_price',
			'_discount',
			'_expedition',
		);

		$data = sobad_post::get_id($id, $quo, '', 'order');

		$title = self::_post_title($data[0]['title'], $data[0]['meta_note_type'], $data[0]['inserted']);
		$data[0]['post_code'] = $title;

		// Get Retail Address
		$data[0]['invoice_code'] = self::_invoice_title($data[0]['title'], $data[0]['inserted']);

		$address = quotation_marketing::_conv_address(array('company' => $data[0]['contact']));
		$data[0]['address'] = $address['_address_comp'];

		$data[0]['product'] = sobad_post::get_transactions($data[0]['ID'], array('ID', 'barang', 'price', 'qty', 'discount'));
		$data[0]['currency'] = format_currency();

		$args = array(
			'data'			=> $data[0],
			'header'		=> 'heading',
			'data_header'	=> $data[0],
			'html'			=> '_html',
			'object'		=> self::$object,
			'title'			=> 'Invoice ' . str_replace('/', '-', $title)
		);

		$args = pdf_setting_retail_invoice($args);
		return sobad_convToPdf($args);
	}

	public static function _html($post = array())
	{
		$invoice = self::_info_payment($post);

		_filter_invoice_retail($post['ID'],array(
			'data'		=> $post,
			'payment'	=> $invoice
		));
	}


	public static function _info_payment($data = array())
	{
		$courier = $data['_expedition'];
		$comp = sobad_company::get_id($courier, array('name'));

		$check = array_filter($comp);
		if (!empty($check)) {
			$courier = $comp[0]['name'];
		}

		$info = array(
			0	=> array(
				'title'	=> 'Courier',
				'text'	=> $courier,
			),
			1	=> array(
				'title'	=> 'Tracking No.',
				'text'	=> $data['_resi'],
			)
		);

		$bank = invoice_marketing::_bank_payment();

		$user = kmi_user::get_id($data['user'], array('name', 'no_induk', 'phone_no'));
		$user = $user[0];

		$invoice = array(
			'info'			=> $info,
			'bank'			=> $bank,
			'no_induk'		=> $user['no_induk'],
			'sales'			=> $user['name'],
			'sales_phone'	=> $user['phone_no']
		);

		return $invoice;
	}
}
