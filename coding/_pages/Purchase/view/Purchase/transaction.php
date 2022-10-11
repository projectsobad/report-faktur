<?php

class transaction_purchase extends _page
{

	protected static $object = 'transaction_purchase';

	protected static $table = 'sobad_post';

	protected static $post = 'purchase';

	// ----------------------------------------------------------
	// Layout category  ------------------------------------------
	// ----------------------------------------------------------

	public static function _array()
	{
		$args = array(
			'ID',
			'title',
			'company',
			'contact',
			'payment',
			'_no_note',
			'post_date',
			'inserted',
			'status',
			'notes',
			'_total',
			'_project',
			'_brand',
			'_discount',
			'_due_date',
			'_no_faktur',
			'_mode_ppn',
			'reff',
			'_ppn',
			'_shipping_price'
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

		return $no . '/PO/' . $kode . '/' . date('m', $date) . '/' . date('y', $date);
	}

	public static function _filter_search($field = '', $search = '')
	{
		$kode = _setting_nocetak();
		$kode = $kode[0];

		if (in_array($field, array('post_date', 'inserted', 'status', 'notes', 'reff'))) {
			return "`" . base . "post`.$field LIKE '%$search%'";
		}

		if ($field == 'title') {
			$table = '`' . base . 'post`.';
			$title = array('[0-9]{4}', 'PO', $kode, '[0-9]{2}');
			return self::_query_filter_search($field, $search, 'title', $title, $table);
		}

		if ($field == 'company') {
			return "_company.name LIKE '%$search%'";
		}

		if ($field == 'contact') {
			return "_contact.name LIKE '%$search%'";
		}

		if ($field == 'payment') {
			return "_payment.name LIKE '%$search%'";
		}
	}

	public static function _query_filter_search($field = '', $search = '', $arr_field = 'title', $title = array(), $table = '')
	{
		if ($field == $arr_field) {
			$args = array();
			$data = explode('/', $search);

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

				// Search By Title
				if ($sort === '1' || $sort === '10') {
					$title = $data[0] * 1;
					return "(" . $table . "title = '" . $title . "' )";
				}

				// Search By Title
				if (in_array($sort, array('12', '120', '123', '1230'))) {
					$title = $data[0] * 1;
					return "(" . $table . "title = '" . $title . "')";
				}

				// Search All Kode Q atau KMI
				if (in_array($sort, array('2', '02', '20', '020', '23', '023', '230', '0230', '3', '03', '30', '030'))) {
					return "title!='0'";
				}

				// Search By Bulan Inserted
				if (in_array($sort, array('234', '0234', '2340', '02340', '34', '034', '340', '0340', '04'))) {
					$month = $data[count($args) - 1];
					return "(MONTH(" . $table . "inserted) = '" . $month . "')";
				}

				// Search By Tahun Inserted
				if ($sort == '40') {
					$year = $data[count($args) - 1];
					return "(YEAR(" . $table . "inserted) = '" . $year . "')";
				}

				// Search By Bulan dan Tahun Inserted
				if (in_array($sort, array('2344', '02344', '344', '0344', '44', '044'))) {
					$year = $data[count($args) - 1];
					$month = $data[count($args) - 2];
					return "(MONTH(" . $table . "inserted) = '" . $month . "' AND YEAR(" . $table . "inserted) = '" . $year . "')";
				}

				// Search By Bulan Or Tahun
				if ($sort == '4') {
					$title = $data[0] * 1;
					return "( MONTH(" . $table . "inserted) = '" . $data[0] . "' OR YEAR(" . $table . "inserted) LIKE '%" . $data[0] . "%')";
				}

				// Search By Title Dan Bulan Dan Tahun
				if ($sort == '12344') {
					$title = $data[0] * 1;
					$year = '20' . $data[count($args) - 1];
					$month = $data[count($args) - 2];
					return "(" . $table . "title = '" . $title . "' AND MONTH(" . $table . "inserted) = '" . $month . "' AND YEAR(" . $table . "inserted) = '" . $year . "')";
				}

				return "(" . $table . "title='" . $data[0] . "')";
			}
		}
	}

	protected static function table()
	{
		$data = array();
		$args = self::_array();

		$start = intval(parent::$page);
		$nLimit = intval(parent::$limit);

		$_search = '';
		$kata = '';
		$where = "AND `" . base . "post`.var='purchase' AND `" . base . "post`.trash='0'";
		if (self::$search) {
			$src = self::like_search($args, $where);
			$cari = $src[0];
			$where = $src[0];
			$kata = $src[1];
			$_search = $src[2];
		} else {
			$cari = $where;
		}

		$limit = 'ORDER BY post_date DESC LIMIT ' . intval(($start - 1) * $nLimit) . ',' . $nLimit;
		$where .= $limit;

		$object = self::$table;
		$sum_data = $object::count("1=1 " . $cari, $args, self::$post);
		$args = $object::get_all($args, $where, 'purchase');

		$data['data'] = array('data' => $kata, 'value' => $_search);
		$data['search'] = array('Semua', 'no. Pengadaan', 'Supplier', 'Nama Kontak', 'Pembayaran', 'No Nota');
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

		$no = ($start - 1) * $nLimit;
		foreach ($args as $key => $val) {
			$no += 1;
			$id = $val['ID'];

			// Check Inquiry
			$check = sobad_post::_check_referensi($id, 'inquiry');

			$status = '';
			if ($check) {
				$status = 'disabled';
			}

			$tagihan = array(
				'ID'	=> 'tagihan_' . $id,
				'func'	=> '_add_form_invoice',
				'color'	=> 'green',
				'icon'	=> 'fa fa-plus',
				'label'	=> 'Tagihan',
				// 'status' => $status
			);

			$det_tagihan = array(
				'ID'	=> 'tagihan_' . $val['ID'],
				'func'	=> '_viewTagihan',
				'color'	=> 'purple',
				'icon'	=> 'fa fa-money',
				'label'	=> 'tagihan'
			);

			$pay = array(
				'ID'	=> 'pay_' . $val['ID'],
				'func'	=> '_viewPay',
				'color'	=> 'green',
				'icon'	=> 'fa fa-money',
				'label'	=> 'history'
			);

			$view = array(
				'ID'	=> 'view_' . $val['ID'],
				'func'	=> '_view',
				'color'	=> 'yellow',
				'icon'	=> 'fa fa-eye',
				'label'	=> 'view'
			);

			$edit = array(
				'ID'	=> 'edit_' . $id,
				'func'	=> '_edit',
				'color'	=> 'blue',
				'icon'	=> 'fa fa-edit',
				'label'	=> 'edit'
			);

			$hapus = array(
				'ID'	=> 'trash_' . $id,
				'func'	=> '_trashG',
				'color'	=> 'red',
				'icon'	=> 'fa fa-trash',
				'label'	=> 'hapus',
			);

			$button = array(
				'label'		=> 'action',
				'color'		=> 'default',
				'button'	=> array(
					_modal_button($pay),
					_modal_button($det_tagihan),
					_modal_button($view),
					edit_button($edit),
					hapus_button($hapus),
				)
			);

			$total = $val['_total'];
			if ($val['_mode_ppn'] == 1) {
				$total += $val['_ppn'];
			}

			$total += intval($val['_shipping_price']) - intval($val['_discount']);

			$color = '#cb5a5e';
			if ($val['status'] == 1) {
				$color = '#26a69a';
			} else if ($val['status'] == 2) {
				$color = '#f5b724;';
			}

			$status = '<i class="fa fa-circle" style="color:' . $color . '">';

			$data['table'][$key]['tr'] = array('');
			$data['table'][$key]['td'] = array(
				'No'			=> array(
					'center',
					'5%',
					$no,
					true
				),
				'Pengadaan'		=> array(
					'left',
					'auto',
					self::_post_title($val['title'], $val['inserted']),
					true
				),
				'Tanggal'		=> array(
					'left',
					'12%',
					$val['post_date'] == '0000-00-00' ? '-' : format_date_id($val['post_date']),
					true
				),
				'Suplier'		=> array(
					'left',
					'12%',
					$val['name_comp'],
					true
				),
				'No Nota'		=> array(
					'left',
					'10%',
					$val['_no_note'],
					true
				),
				'Payment'		=> array(
					'right',
					'10%',
					$val['name_paym'],
					true
				),
				'Total'			=> array(
					'right',
					'12%',
					'Rp. ' . format_nominal($total),
					true
				),
				'Status'		=> array(
					'center',
					'5%',
					$status,
					true
				),
				'Tagihan'		=> array(
					'center',
					'10%',
					_modal_button($tagihan),
					false
				),
				'Action'		=> array(
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
			'title'	=> 'Pengadaan <small>data pengadaan</small>',
			'link'	=> array(
				0	=> array(
					'func'	=> self::$object,
					'label'	=> 'pengadaan'
				)
			),
			'date'	=> false
		);

		return $args;
	}

	protected static function get_box()
	{
		$data = self::table();

		$box = array(
			'label'		=> 'Data Pengadaan',
			'tool'		=> '',
			'action'	=> parent::action(),
			'func'		=> 'sobad_table',
			'data'		=> $data
		);

		return $box;
	}

	protected static function layout()
	{
		$box = self::get_box();

		$opt = array(
			'title'		=> self::head_title(),
			'style'		=> array(),
			'script'	=> array('quotation_marketing', '_style')
		);

		return portlet_admin($opt, $box);
	}

	// ----------------------------------------------------------
	// Form data category -----------------------------------
	// ----------------------------------------------------------
	public static function add_form($func = '', $load = 'sobad_portlet', $reff = 0)
	{
		$no = quotation_marketing::_get_max('purchase');
		$vals = array(0, $no + 1, 0, 0, 0, '', date('Y-m-d'), date('Y-m-d'), 0, '', 0, 0, 2, 0, '', '', '0', $reff, 0, 0);
		$vals = array_combine(self::_array(), $vals);

		if ($func == 'add_0') {
			$func = '_add_db';
		}

		$args = array(
			'title'		=> 'Tambah data purchase',
			'button'	=> '_btn_modal_save',
			'status'	=> array(
				'link'		=> $func,
				'load'		=> $load
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

		$args = array(
			'title'		=> 'Edit data purchase',
			'button'	=> '_btn_modal_save',
			'status'	=> array(
				'link'		=> '_update_db',
				'load'		=> 'sobad_portlet'
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

		$ids = $vals['company'];

		// GET suplier barang ------
		$where = empty($vals['ID']) ? "LIMIT 0,25" : "AND ID='$ids'";
		$suplier = sobad_company::get_suppliers(array('ID', 'name'), $where);
		$suplier = convToOption($suplier, 'ID', 'name');

		// GET suplier contact ------
		$contact = array();
		if (!empty($ids)) {
			$contact = sobad_company::get_customers(array('ID', 'name'), "AND reff='$ids'");
		}

		$contact = convToOption($contact, 'ID', 'name');

		// GET payment method
		$payment = account_purchase::_option_accounts();

		// GET button add supplier
		$add_supply = array(
			'ID'	=> 'add_0',
			'func'	=> '_form_supplier',
			'class'	=> '',
			'color'	=> 'green',
			'icon'	=> 'fa fa-plus',
			'label'	=> 'Add',
			'type'	=> 'supplier',
			'spin'	=> false
		);

		$add_contact = array(
			'ID'	=> 'contact_0',
			'func'	=> '_form_contact',
			'class'	=> '',
			'color'	=> 'green',
			'icon'	=> 'fa fa-plus',
			'label'	=> 'Add',
			'type'	=> $ids,
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
		$pay = sobad_post::get_all(array('price'), "AND `" . base . "post`.var='pay' AND `" . base . "post`.reff='" . $vals['ID'] . "'", 'pay');
		$check = array_filter($pay);
		if (empty($check)) {
			$pay = 0;
		} else {
			$pay = $pay[0]['price'];
		}

		$readonly = $vals['reff'] > 0 ? 'readonly' : '';

		$data = array(
			0 => array(
				'func'			=> 'opt_hidden',
				'type'			=> 'hidden',
				'key'			=> 'ID',
				'value'			=> $vals['ID']
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
				'func'			=> 'opt_input',
				'type'			=> 'text',
				'key'			=> 'name',
				'label'			=> 'No Purchase',
				'class'			=> 'input-circle',
				'value'			=> self::_post_title($vals['title'], $vals['inserted']),
				'data'			=> 'placeholder="No Purchase" readonly'
			),
			array(
				'id'			=> 'supplier',
				'func'			=> 'opt_select',
				'data'			=> $suplier,
				'key'			=> 'company',
				'label'			=> 'Suplier',
				'button'		=> apply_button($add_supply),
				'searching'		=> true,
				'class'			=> 'input-circle',
				'select'		=> $vals['company'],
				'status'		=> 'data-sobad="_get_contact" data-load="contact" data-attribute="purchase_contact"'
			),
			array(
				'id'			=> 'contact',
				'func'			=> 'opt_select',
				'data'			=> $contact,
				'key'			=> 'contact',
				'label'			=> 'Contact',
				'button'		=> apply_button($add_contact),
				'searching'		=> true,
				'class'			=> 'input-circle',
				'select'		=> $vals['contact']
			),
			array(
				'func'			=> 'opt_input',
				'type'			=> 'text',
				'key'			=> '_no_note',
				'label'			=> 'No Nota',
				'class'			=> 'input-circle',
				'value'			=> $vals['_no_note'],
				'data'			=> 'placeholder="No Nota"'
			),
			array(
				'func'			=> 'opt_input',
				'type'			=> 'text',
				'key'			=> '_no_faktur',
				'label'			=> 'No Faktur',
				'class'			=> 'input-circle',
				'value'			=> $vals['_no_faktur'],
				'data'			=> 'placeholder="No Faktur"'
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
				'func'			=> 'opt_datepicker',
				'type'			=> 'date',
				'key'			=> '_due_date',
				'label'			=> 'Jatuh Tempo',
				'class'			=> 'input-circle',
				'value'			=> $vals['_due_date'],
				'data'			=> 'placeholder="Jatuh Tempo"'
			),
			array(
				'func'			=> 'opt_textarea',
				'key'			=> 'notes',
				'label'			=> 'Catatan',
				'class'			=> 'input-circle',
				'value'			=> $vals['notes'],
				'data'			=> 'placeholder="Catatan"',
				'rows'			=> 4
			),
			array(
				'id'			=> 'name_akun',
				'func'			=> 'opt_select',
				'data'			=> $payment,
				'key'			=> 'payment',
				'label'			=> 'Akun',
				'class'			=> 'input-circle',
				'select'		=> $vals['payment'],
				'status'		=> 'data-sobad="set_balance_akun" data-load="balance_akun" data-attribute="val" ' . $readonly
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
				'data'			=> 'placeholder="Harga" '
			),
			array(
				'id'			=> 'discount_purchase',
				'func'			=> 'opt_input',
				'type'			=> 'price',
				'key'			=> '_discount',
				'label'			=> 'Diskon',
				'class'			=> 'input-circle',
				'value'			=> format_nominal(intval($vals['_discount'])),
				'data'			=> 'placeholder="Diskon" '
			),
			array(
				'func'			=> 'opt_box',
				'type'			=> 'radio',
				'key'			=> '_mode_ppn',
				'label'			=> 'PPN',
				'inline'		=> true,
				'value'			=> $vals['_mode_ppn'],
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
			array(
				'func'			=> 'opt_input',
				'type'			=> 'price',
				'key'			=> '_shipping_price',
				'label'			=> 'Biaya Kirim',
				'class'			=> 'input-circle',
				'value'			=> format_nominal($vals['_shipping_price']),
				'data'			=> 'placeholder=""'
			),
		);

		if ($vals['reff'] > 0) {
			// GET user
			$id_cont = array($vals['reff']);
			$cont = sobad_post::get_all(array('contact'), "AND var='moneyG' AND status IN ('0','2') GROUP BY contact", 'moneyG');
			foreach ($cont as $key => $val) {
				$id_cont[] = $val['contact'];
			}

			$id_cont = implode(',', $id_cont);
			$contact = kmi_user::get_all(array('ID', 'name'), "AND ID IN ($id_cont)");
			$contact = convToOption($contact, 'ID', 'name');

			$data[] = array(
				'id'			=> 'money_user',
				'func'			=> 'opt_select',
				'data'			=> $contact,
				'key'			=> 'reff',
				'label'			=> 'Uang gantung',
				'searching'		=> true,
				'class'			=> 'input-circle',
				'select'		=> $vals['reff'],
			);
		}

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
			//update_payment('<?php print($data['paid']); ?>');
			$('#total_purchase').val(number_format(<?php print($data['paid']); ?>));

			$('#supplier').parent().children('.bs-select').children('div.dropdown-menu').children('.bs-searchbox').children().on('change', function() {
				sobad_loading('.bs-select ul.selectpicker');

				data = "ajax=_set_supplier&object=" + object + "&type=" + this.value + "&data=";
				sobad_ajax('#supplier', data, select_option_search, false);
			});

			function purchase_contact(data, id) {
				sobad_option_search(data, id);

				var id_sup = $('#supplier').val();
				$('#contact_0').attr('data-type', id_sup);
			}

			function update_payment(value) {
				$('#pay_purchase').val(number_format(value));
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

			function select_option_search(data, id) {
				$(id).html(data);
				$('.bs-select').selectpicker('refresh');

				$('div.bs-select:nth-child(2) ul.selectpicker .blockUI').remove();
			}
		</script>
	<?php
	}

	public static function _table_item($id = 0)
	{
		$whr = empty($id) ? "AND temporary='purchase_" . get_id_user() . "'" : '';
		$args = sobad_post::get_transactions($id, array('ID', 'barang', 'qty', 'price', 'discount', 'unit', 'note'), $whr);

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
				'type'	=> 1,
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

			$total = $val['price'] - $val['discount'];
			$paid += $total;
			$nominal = $total / $val['qty'];

			$data['table'][$key]['tr'] = array('');
			$data['table'][$key]['td'] = array(
				'no'			=> array(
					'center',
					'5%',
					$no,
					true
				),
				'Nama'			=> array(
					'left',
					'auto',
					$val['name_bara'],
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
					'15%',
					format_nominal($nominal) . '/' . $val['unit'],
					true
				),
				'Total'			=> array(
					'right',
					'15%',
					'Rp. ' . format_nominal($total),
					true
				),
				'Note'			=> array(
					'left',
					'20%',
					$val['note'],
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

	public static function _item_action($id = 1)
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

	public static function _set_supplier($id = 0)
	{
		$search = isset($_POST['type']) ? $_POST['type'] : '';
		$q = sobad_company::get_suppliers(array('ID', 'name'), "AND (ID='$id' OR name LIKE '%$search%') LIMIT 0,25");

		$des = '<option value="0"> Tidak Ada</option>';
		$check = array_filter($q);
		if (!empty($check)) {
			foreach ($q as $key => $cust) {
				$des .= '<option value="' . $cust['ID'] . '"> ' . $cust['name'] . ' </option>';
			}
		}

		return $des;
	}

	public static function _get_contact($id = 0)
	{
		$search = isset($_POST['type']) ? $_POST['type'] : '';
		$q = sobad_company::get_customers(array('ID', 'name'), "AND reff='$id'");

		$des = '';
		$check = array_filter($q);
		if (!empty($check)) {
			foreach ($q as $key => $cust) {
				$des .= '<option value="' . $cust['ID'] . '"> ' . $cust['name'] . ' </option>';
			}

			return $des;
		}

		return '<option value="0"> Tidak Ada</option>';
	}

	public static function set_balance_akun($id = 0)
	{
		$akun = sobad_account::get_id($id, array('balance'));
		$akun = format_nominal($akun[0]['balance']);

		return empty($akun) ? $akun . ' ' : $akun;
	}

	public static function _get_project($id = 0)
	{
		$group = array('internal' => array(0 => 'Tidak Ada'));

		if ($id == 2) {
			$post = sobad_post::get_all(array('ID', 'title', 'inserted', '_project'), "AND `" . base . "post`.var IN ('project','internal_project') AND status IN (0,1,3)", 'project');
			foreach ($post as $_key => $_val) {
				$id_pro = $_val['ID'];
				$project = project_marketing::_post_title($_val['title'], $_val['inserted']) . " :: " . $_val['_project'];
				$group[$project] = array();

				// Get Order No.
				$order = sobad_post::get_all(array('ID', 'title', 'notes', 'inserted'), "AND `" . base . "post`.var='order_project' AND `" . base . "post`.reff='$id_pro'", 'order_project');

				foreach ($order as $ky => $vl) {
					$order_no = project_marketing::_order_title($vl['title'], $vl['inserted']);
					$notes = empty($vl['notes']) ? 'All Parts' : empty($vl['name_note']) ? $vl['notes'] : $vl['name_note'];

					$group[$project][$vl['ID']] = $order_no . ' :: ' . $notes;
				}
			}
		}

		return $group;
	}

	public static function _get_partid($id = 0)
	{
		$partid = array(0 => 'Tidak Ada');

		$post = sobad_post::get_id($id, array('ID', 'barang'), "", 'order_project');
		foreach ($post as $key => $val) {
			$partid[$val['ID']] = $val['part_id_bara'] . ' => ' . $val['name_bara'] . ' :: ' . $val['product_code_bara'];
		}

		return $partid;
	}

	// ------------------------------------------------------------
	// ----- Supplier Add Purchase --------------------------------
	// ------------------------------------------------------------

	public static function _form_supplier()
	{
		return company_marketing::add_form('_add_supplier', 'supplier', 2);
	}

	public static function _add_supplier($args = array())
	{
		$_POST['type'] = 'company_2';
		return company_marketing::_add_db($args, '_load_supplier', 'transaction_purchase');
	}

	public static function _load_supplier()
	{
		$supply = sobad_company::get_suppliers(array('ID', 'name'));

		$opt = '';
		foreach ($supply as $ky => $val) {
			$opt .= '<option value="' . $val['ID'] . '"> ' . $val['name'] . ' </option>';
		}

		return $opt;
	}

	// ------------------------------------------------------------
	// ----- Supplier Add Contact --------------------------------
	// ------------------------------------------------------------

	public static function _form_contact()
	{
		$type = isset($_POST['type']) ? $_POST['type'] : 0;
		return company_marketing::add_form('_add_contact', 'contact', 1, $type, true);
	}

	public static function _add_contact($args = array())
	{
		return company_marketing::_add_db($args, '_load_contact', 'transaction_purchase');
	}

	public static function _load_contact()
	{
		$type = isset($_POST['type']) ? $_POST['type'] : 0;
		$supply = sobad_company::get_customers(array('ID', 'name'), "AND reff='$type'");

		$opt = '';
		foreach ($supply as $ky => $val) {
			$opt .= '<option value="' . $val['ID'] . '"> ' . $val['name'] . ' </option>';
		}

		return $opt;
	}

	public static function _form_script()
	{
		return company_marketing::_form_script(true);
	}

	public static function _set_company($id = 0)
	{
		return company_marketing::_set_company($id);
	}

	// ------------------------------------------------------------
	// ----- View History Pembayaran ------------------------------
	// ------------------------------------------------------------	

	public static function _viewPay($id = 0)
	{
		$id = str_replace('pay_', '', $id);
		$table = self::_table_pay($id,'pay');

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

	public static function _table_pay($idx = 0, $var = 'pay')
	{
		$unit = unit::_get(array('bank'));
		$unit = $unit['bank']['unit'];

		$data['class'] = '';
		$data['table'] = array();

		$args = sobad_post::get_all(array('user', 'payment', 'post_date', 'price'), "AND `" . base . "post`.var='$var' AND `" . base . "post`.reff='$idx'", $var);

		$tagihan = sobad_post::get_all(array('ID'), "AND `" . base . "post`.var='invoice_purchase' AND `" . base . "post`.reff='$idx'", $var);
		foreach ($tagihan as $key => $val) {
			$_idx = $val['ID'];
			$_args = sobad_post::get_all(array('user', 'payment', 'post_date', 'price'), "AND `" . base . "post`.var='$var' AND `" . base . "post`.reff='$_idx'", $var);
			
			foreach ($_args as $ky => $vl) {
				$args[] = $vl;
			}
		}

		$no = 0;
		foreach ($args as $ky => $vl) {
			if ($vl['price'] <= 0) {
				continue;
			}
			$no += 1;

			$user = kmi_user::get_id($vl['user'], array('name'));
			$check = array_filter($user);

			$user = empty($check) ? '-' : $user[0]['name'];

			$account = $vl['name_paym'];
			if ($vl['bank_paym'] != 0) {
				$account = $unit[$vl['bank_paym']]['name'] . ' (' . $unit[$vl['bank_paym']]['code'] . ')<br>a.n. ' . $account;
			}

			$data['table'][$no - 1]['tr'] = array('');
			$data['table'][$no - 1]['td'] = array(
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
					'Rp. ' . format_nominal($vl['price']),
					true
				),
				'Tanggal'		=> array(
					'left',
					'15%',
					format_date_id($vl['post_date']),
					true
				)
			);
		}

		return $data;
	}

	// ------------------------------------------------------------
	// ----- View History Tagihan ---------------------------------
	// ------------------------------------------------------------	

	public static function _viewTagihan($id = 0)
	{
		$id = str_replace('tagihan_', '', $id);
		$table = self::_table_tagihan($id);

		$args = array(
			'title'		=> 'History Tagihan',
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

	public static function _table_tagihan($idx = 0)
	{
		$data['class'] = '';
		$data['table'] = array();

		$args = sobad_post::get_all(array('ID','status','_due_date','_total','_ppn','_mode_ppn','_no_note'), "AND `" . base . "post`.var='invoice_purchase' AND `" . base . "post`.reff='$idx'",'invoice_purchase');

		$no = 0;
		foreach ($args as $key => $val) {
			$no += 1;

			$color = '#cb5a5e';
            if ($val['status'] == 1) {
                $color = '#26a69a';
            } else if ($val['status'] == 2) {
                $color = '#f5b724;';
            }

            $status = '<i class="fa fa-circle" style="color:' . $color . '">';

			$total = $val['_total'];
            if ($val['_mode_ppn'] == 1) {
                $total += $val['_ppn'];
            }

			$data['table'][$no - 1]['tr'] = array('');
			$data['table'][$no - 1]['td'] = array(
				'no'			=> array(
					'center',
					'5%',
					$no,
					true
				),
				'No Inv'			=> array(
					'left',
					'auto',
					$val['_no_note'],
					true
				),
				'Tagihan'	=> array(
					'right',
					'20%',
					'Rp. ' . format_nominal($total),
					true
				),
				'Due Date'		=> array(
					'left',
					'15%',
					format_date_id($val['_due_date']),
					true
				),
				'Bayar'		=> array(
					'center',
					'10%',
					$status,
					false
				),
			);
		}

		return $data;
	}

	// ------------------------------------------------------------
	// ----- View Table Item --------------------------------------
	// ------------------------------------------------------------	

	public static function _view($id = 0)
	{
		$id = str_replace('view_', '', $id);
		$table = self::_table_item($id);
		$table = $table['data'];

		foreach ($table['table'] as $ky => $val) {
			unset($table['table'][$ky]['td']['Edit']);
			unset($table['table'][$ky]['td']['Hapus']);
		}

		$args = array(
			'title'		=> 'View data purchase',
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

	public function _add_form_invoice($id=0)
	{
		$id = str_replace("tagihan_","",$id);
		intval($id);

		$vals = array(0, 0, 0, 0, 0, '', '', date('Y-m-d'), date('Y-m-d'), 0, $id, 0,0,date('Y-m-d'),0);
		$vals = array_combine(invoice_purchase::_array(), $vals);

		$args = array(
			'title'     => 'tambah Tagihan',
			'button'    => '_btn_modal_save',
			'status'    => array(
				'link'        => '_add_db_invoice',
				'load'        => 'sobad_portlet',
			)
		);

		return invoice_purchase::_data_form($args, $vals);
	}

	public static function _tagihan_detail($idx=0){
		return invoice_purchase::_tagihan_detail($idx);
	}

	public static function _add_db_invoice($data = array())
	{

		$_data = invoice_purchase::_add_db($data, 'invoice');
		$page = isset($_POST['page']) ? $_POST['page'] : 1;
		return self::_get_table($page);
	}

	// ----------------------------------------------------------
	// Form2 data purchase --------------------------------------
	// ----------------------------------------------------------

	public static function _form_add($id = '')
	{
		$vals = array(
			'ID'		=> 0,
			'post'		=> $id,
			'barang'	=> 0,
			'price'		=> 0,
			'qty'		=> 1,
			'discount'	=> 0,
			'unit'		=> 'pcs',
			'note'		=> '',
			'keyword'	=> 0,
			'extends'	=> 2,
			'name_bara'	=> '',
			'var_bara'	=> 2,
			'temporary'	=> 0
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

		$unit = sobad_item::get_id($vals['barang'], array('_unit'));
		$unit = empty($unit[0]['_unit']) ? 'pcs' : $unit[0]['_unit'];

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

		// Project
		$project = self::_get_project(2);

		// GET
		$units = unit::_get(array('length', 'area', 'volume', 'capacity', 'weight', 'custom'));
		$units = unit::conv_to_option($units);

		$sts = $vals['barang'] == 0 ? '' : 'disabled';

		$var = item_purchase::_gets_akun();

		$data = array(
			0 => array(
				'func'			=> 'opt_hidden',
				'type'			=> 'hidden',
				'key'			=> '_id',
				'value'			=> $vals['ID']
			),
			array(
				'func'			=> 'opt_hidden',
				'type'			=> 'hidden',
				'key'			=> 'ID',
				'value'			=> $vals['post']
			),
			array(
				'func'			=> 'opt_hidden',
				'id'			=> 'brng_purchase',
				'type'			=> 'hidden',
				'key'			=> 'barang',
				'value'			=> $vals['barang']
			),
			array(
				'func'			=> 'opt_input',
				'id'			=> 'name_purchase',
				'type'			=> 'text',
				'key'			=> 'name',
				'label'			=> 'Nama',
				'class'			=> '',
				'value'			=> $vals['name_bara'],
				'data'			=> 'placeholder="Nama" ' . $sts
			),
			array(
				'func'			=> 'opt_select',
				'id'			=> 'type_purchase',
				'group'			=> true,
				'searching'		=> true,
				'data'			=> $var,
				'key'			=> 'var',
				'label'			=> 'Type',
				'class'			=> 'input-circle',
				'select'		=> $vals['extends'],
				'status'		=> ''
			),
			array(
				'func'			=> 'opt_select',
				'data'			=> $project,
				'key'			=> 'keyword',
				'label'			=> 'Order No.',
				'searching'		=> true,
				'group'			=> true,
				'class'			=> 'input-circle',
				'select'		=> $vals['keyword'],
				'status'		=> ''
			),
			array(
				'func'			=> 'opt_input',
				'id'			=> 'price_purchase',
				'type'			=> 'price',
				'key'			=> 'price',
				'label'			=> 'Harga',
				'class'			=> 'input-circle',
				'value'			=> format_nominal($vals['price']),
				'data'			=> 'placeholder="Harga"'
			),
			array(
				'func'			=> 'opt_input',
				'id'			=> 'qty_purchase',
				'type'			=> 'text',
				'key'			=> 'qty',
				'label'			=> 'Jumlah',
				'class'			=> 'input-circle quantity',
				'value'			=> format_quantity($vals['qty']),
				'data'			=> 'placeholder="Quantity"'
			),
			array(
				'func'			=> 'opt_select',
				'id'			=> 'unit_purchase',
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
				'func'			=> 'opt_textarea',
				'key'			=> 'note',
				'label'			=> 'Catatan',
				'class'			=> 'input-circle',
				'value'			=> $vals['note'],
				'data'			=> 'placeholder="Note"',
				'rows'			=> 4
			),
		);

		$args['func'] = array('sobad_form', '_script_purchase');
		$args['data'] = array($data, $vals['barang']);

		return modal_admin($args);
	}

	public static function _script_purchase($id = 0)
	{
		$data = sobad_item::get_all(array('ID', 'name', 'stock', 'price', '_unit', 'var'), "AND type='0'");
		$data = json_encode($data);

	?>
		<script type="text/javascript">
			var items = <?php print($data); ?>;

			var barangs = new Bloodhound({
				datumTokenizer: function(d) {
					return Bloodhound.tokenizers.whitespace(d.name);
				},
				queryTokenizer: Bloodhound.tokenizers.whitespace,
				local: <?php print($data); ?>
			});

			// initialize the bloodhound suggestion engine
			barangs.initialize();

			$('#name_purchase').typeahead(null, {
				displayKey: 'name',
				limit: 1,
				hint: false,
				source: barangs.ttAdapter()
			});

			$('#name_purchase').on('change', function(e) {
				_set_barang_purchase(this.value);
			});

			function _set_barang_purchase(name) {
				$('#brng_purchase').val(0);
				$('#price_purchase').val(0);
				$('#qty_purchase').val(1);

				for (keyName in items) {
					if (items[keyName]['name'] == name) {
						$('#brng_purchase').val(items[keyName]['ID']);
						$('#price_purchase').val(number_format(items[keyName]['price']));
						$('#type_purchase').val(items[keyName]['var']).change();
						$('#unit_purchase').val(items[keyName]['_unit']).change();
					}
				}
			}

			/*
		        $('#name_purchase').tagsInput({
					'width':'auto',
					'limit':1,
					'minChars': 3,
					'autocomplete':{
						source:barangs.ttAdapter()
					}
				});
			*/
		</script>
<?php
	}

	// -------------------------------------------------------------
	// Database ----------------------------------------------------
	// -------------------------------------------------------------

	public static function _reStock($id = 0)
	{
		// re-stock 
		$data = sobad_post::get_transaction($id, array('barang', 'qty', 'unit', 'price', 'discount'));
		$data = $data[0];

		$item = sobad_item::get_id($data['barang'], array('stock', '_unit'));
		$item = $item[0];

		$unit = unit::conversi_unit($data['unit'], $item['_unit'], $data['qty']);
		$stock = $data['stock_bara'] - $unit;

		// Update Stock
		sobad_db::_update_single($data['barang'], '' . base . 'item', array('ID' => $data['barang'], 'stock' => $stock));
	}

	public static function _upStock($id = 0, $qty = 0, $price = 0, $discount = 0)
	{
		$data = sobad_post::get_transaction($id, array('barang', 'qty', 'unit'));
		$data = $data[0];


		$stock = floatval($qty - $data['qty']);
		$disc = $discount <= 100 ? $discount * $price / 100 : $discount;
		$harga = $price - $disc;

		// update stock 
		$item = sobad_item::get_id($data['barang'], array('stock', '_unit'));
		$item = $item[0];

		$unit = unit::conversi_unit($data['unit'], $item['_unit'], $stock);
		$stock = $item['stock'] + $unit;

		$price = round($harga / $qty, 2);

		// Update Stock
		sobad_db::_update_single($data['barang'], '' . base . 'item', array('ID' => $data['barang'], 'stock' => $stock, 'price' => $price));
	}

	public static function _deleteItem($id)
	{
		$id = str_replace('del_', '', $id);
		intval($id);

		// re-stock 
		self::_reStock($id);

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

		$args = array('ID', 'post', 'barang', 'price', 'qty', 'discount', 'unit', 'note', 'keyword', 'extends', 'temporary');
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
		$reff = $args['ID'];
		unset($args['_id']);
		unset($args['ID']);

		if (isset($args['search'])) {
			$src = array(
				'search'	=> $args['search'],
				'words'		=> $args['words']
			);

			unset($args['search']);
			unset($args['words']);
		}

		$qty = clear_format($args['qty']);
		$price = $args['price'];
		$discount = $args['discount'];

		// Update Stock
		self::_upStock($id, $qty, $price, $discount);

		// Update Transaksi
		$_args = array(
			'post'		=> $reff,
			'barang'	=> $args['barang'],
			'qty'		=> $qty,
			'unit'		=> $args['unit'],
			'price'		=> $price,
			'discount'	=> $discount,
			'temporary'	=> empty($reff) ? 'purchase_' . get_id_user() : '',
			'note'		=> $args['note'],
			'extends'	=> $args['var'],
			'keyword'	=> $args['keyword']
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
		$reff = $args['ID'];
		unset($args['_id']);
		unset($args['ID']);

		if (isset($args['search'])) {
			$src = array(
				'search'	=> $args['search'],
				'words'		=> $args['words']
			);

			unset($args['search']);
			unset($args['words']);
		}

		$qty = clear_format($args['qty']);
		$price = $args['price'];
		$discount = $args['discount'];

		$barang = $args['barang'];
		if ($barang == 0) {
			$args = self::_insertItem($args);
		}

		$_args = array(
			'post'		=> $reff,
			'barang'	=> $args['barang'],
			'qty'		=> $qty,
			'unit'		=> $args['unit'],
			'price'		=> $price,
			'discount'	=> $discount,
			'note'		=> $args['note'],
			'temporary'	=> 'purchase_' . get_id_user(),
			'extends'	=> $args['var'],
			'keyword'	=> $args['keyword']
		);

		$q = sobad_db::_insert_table('' . base . 'transaksi', $_args);

		if ($barang != 0) {
			// Update Stock
			self::_upStock($q, $qty * 2, $price * 2, $discount);
		}

		if ($q !== 0) {
			$data = self::_table_item($reff);
			$table = table_admin($data['data']);
			$table .= '<script>update_payment(' . $data['paid'] . ');</script>';

			return $table;
		}
	}

	public static function _insertItem($args = array())
	{
		$check = array_filter($args);
		if (empty($check)) {
			return $args;
		}

		if (empty($args['name'])) {
			return '';
		}

		$stock = $args['qty'];
		$harga = $args['price'];
		$disc = $args['discount'];

		$disc = $disc <= 100 ? $disc * $harga / 100 : $disc;
		$harga -= $disc;

		$stock = $args['var'] == 3 ? 1 : $stock;

		$data = array(
			'name'		=> $args['name'],
			'company'	=> 3,
			'price'		=> $harga,
			'stock'		=> $stock,
			'type'		=> 0,
			'var'		=> $args['var']
		);

		$idx = sobad_db::_insert_table('' . base . 'item', $data);
		$q = sobad_db::_insert_table('' . base . 'item-meta', array(
			'meta_id'		=> $idx,
			'meta_key'		=> '_unit',
			'meta_value'	=> $args['unit']
		));

		$args['barang'] = $idx;
		return $args;
	}

	// ----------------------------------------------------------
	// Function purchase to database ----------------------------
	// ----------------------------------------------------------

	public static function _trashG($id = 0)
	{
		$id = str_replace('trash_', '', $id);
		intval($id);

		$post = sobad_post::get_id($id, array('ID', 'reff'));
		if ($post[0]['reff'] > 0) {
			self::_reset_moneyG($id);
		} else {
			self::_reset_saldo($id);
		}

		sobad_db::_update_multiple("reff='$id' AND var='pay'", '' . base . 'post', array('status' => 1));

		return self::_trash($id);
	}

	private static function _reset_moneyG($idx = 0)
	{
		$post = sobad_post::get_id($idx, array('ID', 'reff', '_moneyG'), '', 'purchase');
		$post = $post[0];

		$pay = sobad_post::get_all(array('ID', 'price'), "AND `" . base . "post`.reff='$idx' AND `" . base . "post`.var='pay'", 'pay');
		$pay = $pay[0];

		$total = $pay['price'];
		$moneyG = explode(',', $post['_moneyG']);
		foreach ($moneyG as $key => $val) {
			$_money = sobad_post::get_id($val, array('ID', 'payment', '_saldo', '_total'), '', 'moneyG');
			$_money = $_money[0];

			$_saldo = $_money['_saldo'] + $total;
			$_saldo = $_saldo >= $_money['_total'] ? $_money['_total'] : $_saldo;
			$total -= ($_money['_total'] - $_money['_saldo']);

			sobad_db::_update_multiple("meta_id='" . $val . "' AND meta_key='_saldo' ", '' . base . 'post-meta', array('meta_id' => $val, 'meta_value' => $_saldo));

			if ($_saldo > 0) {
				sobad_db::_update_single($val, '' . base . 'post', array('ID' => $val, 'status' => 0));
			}
		}
	}

	private static function _check_moneyG($args = array())
	{
		$reff = $args['reff'];
		$moneyG = sobad_post::get_all(array('ID', 'contact', '_saldo'), "AND status='0' AND contact='$reff' AND var='moneyG'", 'moneyG');

		$_reff = array();
		$order = clear_format($args['pay']);
		foreach ($moneyG as $key => $val) {
			$_saldo = $val['_saldo'] - $order;
			$_saldo = $_saldo <= 0 ? 0 : $_saldo;

			sobad_db::_update_multiple("meta_id='" . $val['ID'] . "' AND meta_key='_saldo' ", '' . base . 'post-meta', array('meta_id' => $val['ID'], 'meta_value' => $_saldo));

			if ($_saldo <= 0) {
				sobad_db::_update_single($val['ID'], '' . base . 'post', array('status' => 1));
			}

			$_reff[] = $val['ID'];
			$order -= $val['_saldo'];

			if ($order <= 0) {
				break;
			}
		}

		if ($order > 0) {
			$cnt = count($_reff) - 1;

			sobad_db::_update_multiple("meta_id='" . $val['ID'] . "' AND meta_key='_saldo' ", '' . base . 'post-meta', array('meta_id' => $_reff[$cnt], 'meta_value' => ($order * -1)));

			if ($_saldo <= 0) {
				sobad_db::_update_single($_reff[$cnt], '' . base . 'post', array('status' => 2));
			}
		}

		$args['_moneyG'] = implode(',', $_reff);
		return $args;
	}

	private static function _reset_saldo($idx = 0)
	{
		$pays = sobad_post::get_all(array('ID', 'payment', 'id_join', 'price'), "AND `" . base . "post`.var='pay' AND `" . base . "post`.reff='$idx'", 'pay');

		$akun = sobad_account::get_id($pays[0]['payment'], array('balance'));
		$akun = $akun[0];

		$saldo = $akun['balance'] + $pays[0]['price'];
		sobad_db::_update_single($pays[0]['payment'], '' . base . 'account', array('ID' => $pays[0]['payment'], 'balance' => $saldo));
	}

	public static function _callback($args = array(), $_args = array())
	{
		$args['_shipping_price'] = clear_format($args['_shipping_price']);
		$args['_discount'] = clear_format($args['_discount']);
		$args['_total'] = clear_format($args['_total']);
		$args['_due_date'] = $args['_due_date'] == '1970-01-01' ? '' : $args['_due_date'];

		$args['reff'] = isset($args['reff']) ? $args['reff'] : 0;
		$args['var'] = 'purchase';
		$args['user'] = get_id_user();

		// Update Total dan hitung ppn
		$total = ($args['_total'] - $args['_discount']);
		$shipping = $args['_shipping_price'];

		$ppn = 0;
		$getData = sobad_company::_check_profile();
		if ($args['_mode_ppn'] == 1) {
			$ppn = $total * $getData['_ppn'] / 100;
			$ppn = round($ppn, 0);

			$total += $ppn;
		} else if ($args['_mode_ppn'] == 2) {
			$price = (100 * $total) / (100 + $getData['_ppn']);

			$ppn = $total - $price;
			$ppn = round($ppn, 0);
		}

		$args['_ppn'] = $ppn;
		$total += $shipping;

		$args['status'] = 0;
		if ($args['pay'] >= $total) {
			$args['pay'] = $total;
			$args['status'] = 1;
		} else {
			$purchase = 0;
			if ($args['ID'] > 0) {
				$purchase = sobad_post::get_id($args['ID'], array('status'));
				$purchase = $purchase[0]['status'];
			}

			if (!empty($args['_no_note'])) {
				$args['status'] = 2;
			}

			if ($purchase == 1) {
				$args['status'] = 1;
			}
		}

		// Check Referensi ---> Uang Gantung
		if ($args['reff'] > 0) {
			$args['status'] = 1;
			if ($args['ID'] <= 0) {
				$args['updated'] = '0000-00-00 00:00:00';
			}

			if ($args['ID'] > 0) {
				self::_reset_moneyG($args['ID']);
			}

			$args = self::_check_moneyG($args);
			return $args;
		}

		// Reset Saldo
		if ($args['ID'] > 0) {
			self::_reset_saldo($args['ID']);
		}

		// Pengurangan Saldo
		$akun = sobad_account::get_id($args['payment'], array('balance'));
		$akun = $akun[0];

		if ($akun['balance'] < $args['pay']) {
			die(_error::_alert_db('Jumlah Saldo Tidak Cukup !!!'));
		}

		$saldo = $akun['balance'] - $args['pay'];
		sobad_db::_update_single($args['payment'], '' . base . 'account', array('ID' => $args['payment'], 'balance' => $saldo));

		// Update Pembayaran
		if ($args['ID'] > 0) {
			$pays = sobad_post::get_all(array('ID', 'payment', 'id_join', 'price', 'discount', 'qty'), "AND `" . base . "post`.var='pay' AND `" . base . "post`.reff='" . $args['ID'] . "'", 'pay');

			sobad_db::_update_single($pays[0]['ID'], '' . base . 'post', array('payment' => $args['payment'], 'updated' => date('Y-m-d H:i:s')));
			sobad_db::_update_single($pays[0]['id_join'], '' . base . 'transaksi', array('ID' => $pays[0]['id_join'], 'price' => $args['pay']));

			if ($args['pay'] != $pays[0]['price']) {
				$total = 0;
				foreach ($pays as $key => $val) {
					$val['price'] = $key == 0 ? $args['pay'] : (($val['price'] - $val['discount']) * $val['qty']);
					$total += $val['price'];
				}

				$args['status'] = $total >= $args['_total'] ? 1 : 2;
			}
		} else {
			$args['updated'] = '0000-00-00 00:00:00';
		}

		return $args;
	}

	public static function _updateDetail($data = array(), $args = array())
	{
		$idx = $data['index'];
		$args = sobad_asset::ajax_conv_json($args);

		$pay = sobad_post::get_all(array('ID', 'id_join', 'price'), "AND `" . base . "post`.reff='$idx' AND `" . base . "post`.var='pay'", 'pay');
		$pay = $pay[0];

		$q = sobad_db::_update_single(
			$pay['id_join'],
			'' . base . 'transaksi',
			array(
				'ID'	=> $pay['id_join'],
				'price' => $args['pay']
			)
		);

		return $q;
	}

	public static function _addDetail($data = array(), $args = array())
	{
		$idx = $data['index'];
		$args = sobad_asset::ajax_conv_json($args);

		// Update Detail Transaksi
		$keyword = 'purchase_' . get_id_user();
		$q = sobad_db::_update_multiple("post='0' AND temporary='$keyword'", '' . base . 'transaksi', array('post' => $idx, 'temporary' => ''));

		// Insert Post Pembayaran
		$no = quotation_marketing::_get_max('pay');
		$p = sobad_db::_insert_table('' . base . 'post', array(
			'title'			=> $no + 1,
			'user'			=> get_id_user(),
			'payment'		=> $args['payment'],
			'post_date'		=> $args['post_date'],
			'updated'		=> '0000-00-00 00:00:00',
			'var'			=> 'pay',
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

		return $q;
	}

	// Next ---> Untuk membuat no inventory (belum selesai)
	private static function _detail_asset($idx = 0, $reff = 0, $qty = 0)
	{
		$trans = sobad_post::get_transactions($idx, array('barang', 'qty'), "AND _barang.type='0' AND _barang.var='1'");
		$_detail = array();

		if ($reff > 0) {
			$_detail = sobad_item::get_transaction($idx, $reff);
			$_count = count($_detail);

			if ($_count != $qty) {
				foreach ($_detail as $key => $val) {
					// Update status
					if ($_count <= $qty) {
					}
				}
			}
		}
	}
}
