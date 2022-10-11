<?php

class forms_retail extends _page
{

	protected static $object = 'forms_retail';

	protected static $table = 'sobad_post';

	protected static $post = 'form';

	// ----------------------------------------------------------
	// Layout Transaksi  ------------------------------------------
	// ----------------------------------------------------------

	protected static function _array()
	{
		$args = array(
			'ID',
			'title',
			'contact',
			'post_date',
			'inserted',
			'notes',
			'type',
			'_due_date',
			'status',
			'updated'
		);

		return $args;
	}

	public static function _post_title($title = 0, $date = '')
	{
		if (empty($date)) {
			$date = date('Y-m-d');
		}

		$no = sprintf("%04d", $title);
		$date = strtotime($date);
		return 'FPO/' . $no . '/' . date('m', $date) . '/' . date('Y', $date);
	}

	public static function _filter_search($field = '', $search = '')
	{
		if ($field == 'title') {
			$table = '`'. base .'post`.';
			$title = array('FPO', '[0-9]{4}', '[0-9]{2}', '^[a-zA-Z0-9]+$');
			return self::_query_filter_search($field, $search, 'title', $title, $table);
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

				// Search By Type
				if ($sort === '1' || $sort === '10') {
					return "type!='0'";
				}

				// Search By title OR Year
				if ($sort == '2' || $sort == '02') {
					$title = $data[count($args) - 1] * 1;
					$year = $data[count($args) - 1];
					return "(YEAR(" . $table . "inserted) = '" . $year . "' OR " . $table . "title='$title')";
				}

				// Search By title
				if (in_array($sort, array('12', '120', '20'))) {
					$title = $sort == '20' ? $data[0] * 1 : $data[1] * 1;
					return "(" . $table . "title='$title')";
				}

				// Search By title AND Month
				if (in_array($sort, array('123', '1230', '23', '023', '230', '0230'))) {
					if (in_array($sort, array('123', '1230', '023', '0230'))) {
						$title = $data[1] * 1;
						$month = $data[2];
					} else {
						$title = $data[0] * 1;
						$month = $data[1];
					}
					return "(" . $table . "title='$title' AND MONTH(" . $table . "inserted) = '" . $month . "')";
				}

				// Search By title AND Month AND Year
				if (in_array($sort, array('1232', '232', '0232'))) {
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
		$data = array();
		$args = self::_array();

		$date = date('Y-m-d');
		$before = strtotime($date);
		$before = date('Y-m-d', strtotime('-1 month', $before));

		$start = intval(parent::$page);
		$nLimit = intval(parent::$limit);

		$kata = '';
		$where = "AND var='form_product' AND post_date BETWEEN '$before' AND '$date'";
		if (parent::$search) {
			$src = parent::like_search($args, $where);
			$cari = $src[0];
			$where = $src[0];
			$kata = $src[1];
		} else {
			$cari = $where;
		}

		$limit = 'ORDER BY post_date DESC LIMIT ' . intval(($start - 1) * $nLimit) . ',' . $nLimit;
		$where .= $limit;

		$object = self::$table;
		$args = $object::get_all($args, $where, 'form');
		$sum_data = $object::count("1=1 " . $cari);

		$data['data'] = array('data' => $kata);
		$data['search'] = array('Semua', 'No. Form');
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

			$print = array(
				'ID'	=> 'preview_' . $id,
				'func'	=> '_preview',
				'color'	=> 'green',
				'icon'	=> 'fa fa-print',
				'label'	=> 'Print',
				'script' => 'sobad_button_pre(this)'
			);

			$edit = array(
				'ID'	=> 'edit_' . $id,
				'func'	=> '_edit',
				'color'	=> 'blue',
				'icon'	=> 'fa fa-edit',
				'label'	=> 'edit'
			);

			$hapus = array(
				'ID'	=> 'del_' . $id,
				'func'	=> '_deleteR',
				'color'	=> 'red',
				'icon'	=> 'fa fa-trash',
				'label'	=> 'hapus',
				'status' => $val['status'] == 0 ? '' : 'disabled'
			);

			// Get Status
			$status = '';
			switch ($val['status']) {
				case 0:
					$status = '#f94d53;';
					break;

				case 1:
					$status = '#26a69a;';
					break;

				case 2:
					$status = '#f5b724;';
					break;

				default:
					$status = '#fff;';
					break;
			}

			$status = '<i class="fa fa-circle" style="color:' . $status . '"></i>';

			// Check Due Date
			$due_date = empty($val['_due_date']) ? '' : $val['_due_date'];
			if (!empty($due_date)) {
				if (($val['status'] != 1 && $due_date < date('Y-m-d H:i:s')) || ($val['status'] == 1 && $due_date < $val['updated'])) {
					$due_date = '<span style="color:#f94d53;">' . format_date_id($due_date) . '</span>';
				} else {
					$due_date = format_date_id($due_date);
				}
			} else {
				$due_date = ' - ';
			}

			// Get product
			$note = array();
			$trans = sobad_post::get_transactions($val['ID'], array('barang', 'qty', 'unit'));
			foreach ($trans as $_key => $_val) {
				$note[] = '-- <strong>' . $_val['name_bara'] . '</strong> :: ' . $_val['product_code_bara'] . ' (' . $_val['qty'] . ' ' . $_val['unit'] . ')';
			}
			$note = implode('<br>', $note);

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
					self::_post_title($val['title'], $val['inserted']),
					true
				),
				'Keterangan' => array(
					'left',
					'auto',
					$note,
					true
				),
				'Tanggal'	=> array(
					'center',
					'10%',
					format_date_id($val['post_date']),
					true
				),
				'DOD'		=> array(
					'center',
					'10%',
					$due_date,
					true
				),
				'Status'	=> array(
					'center',
					'7%',
					$status,
					true
				),
				'print'			=> array(
					'center',
					'8%',
					print_button($print),
					false
				),
				'Edit'			=> array(
					'center',
					'8%',
					edit_button($edit),
					false
				),
				'Hapus'			=> array(
					'center',
					'8%',
					hapus_button($hapus),
					false
				)

			);
		}

		return $data;
	}

	private static function head_title()
	{
		$args = array(
			'title'	=> 'Form <small>data form</small>',
			'link'	=> array(
				0	=> array(
					'func'	=> self::$object,
					'label'	=> 'form'
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
			'label'		=> 'Data form',
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
			'script'	=> array('')
		);

		return portlet_admin($opt, $box);
	}

	public static function _kmi_employee($args = array())
	{
		$user = array();
		$module = sobad_module::get_all(array('detail'), "AND detail!=''");
		foreach ($module as $key => $val) {
			$detail = unserialize($val['detail']);
			$detail = $detail['access'];

			$user = array_merge($user, $detail);
		}

		$user = array_filter($user);
		$user = empty($user) ? 0 : implode(',', $user);

		$contact = kmi_user::get_all($args, "AND ID IN ($user)");

		return $contact;
	}

	public static function _conv_brand($id=0)
	{
		$brand = sobad_meta::get_id($id,array('meta_value'));
		$brand = isset($brand[0]) ? $brand[0]['meta_value'] : 'default';

		$brand = strtolower($brand);
		$brand = str_replace(' ', '_', $brand);

		return $brand;
	}

	// ----------------------------------------------------------
	// Form data category -----------------------------------
	// ----------------------------------------------------------
	public static function add_form($func = '', $load = 'sobad_portlet')
	{
		$no = quotation_marketing::_get_max('form_product');

		$vals = array(0, $no + 1, 0, date('Y-m-d'), date('Y-m-d H:i:s'), '', 0, date('Y-m-d'), 0, '0000-00-00 00:00:00');
		$vals = array_combine(self::_array(), $vals);

		if ($func == 'add_0') {
			$func = '_add_db';
		}

		$args = array(
			'title'		=> 'Buat Form',
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

		$type = isset($_POST['type']) ? $_POST['type'] : '';

		$args = array(
			'title'		=> 'Edit Form',
			'button'	=> $vals['status'] == 0 ? '_btn_modal_save' : '',
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

		$contact = self::_kmi_employee(array('ID', 'name'));
		$contact = convToOption($contact, 'ID', 'name');

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
				'key'			=> 'no_form',
				'label'			=> 'No. Form',
				'class'			=> 'input-circle',
				'value'			=> self::_post_title($vals['title'], $vals['inserted']),
				'data'			=> 'readonly'
			),
			array(
				'func'			=> 'opt_select',
				'data'			=> $contact,
				'key'			=> 'contact',
				'label'			=> 'Kepada',
				'class'			=> 'input-circle',
				'searching'		=> true,
				'select'		=> $vals['contact'],
				'status'		=> ''
			),
			array(
				'func'			=> 'opt_datepicker',
				'key'			=> 'post_date',
				'label'			=> 'Tanggal',
				'class'			=> 'input-circle',
				'value'			=> $vals['post_date'],
				'data'			=> ''
			),
			array(
				'func'			=> 'opt_datepicker',
				'key'			=> '_due_date',
				'label'			=> 'Due Date',
				'class'			=> 'input-circle',
				'value'			=> $vals['_due_date'],
				'data'			=> ''
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
		);

		$args['func'] = array('sobad_form', '_portlet_item');
		$args['data'] = array($data, array('id' => $vals['ID'], 'status' => $vals['status']));

		return modal_admin($args);
	}

	public static function _portlet_item($_args = array())
	{
		$id = $_args['id'];
		$status = $_args['status'];
		$data = self::_table_item($id, $status);

		$box = array(
			'ID'		=> 'portlet_item',
			'label'		=> 'Data barang',
			'tool'		=> '',
			'action'	=> $status == 0 ? self::_form_action($id) . self::_item_action($id) : self::_form_action($id),
			'func'		=> 'sobad_table',
			'data'		=> $data['data']
		);

		theme_layout('_portlet', $box);
	}

	protected static function _table_item($id = 0, $status = 0)
	{
		$whr = empty($id) ? "AND keyword='form_" . get_id_user() . "'" : '';
		$args = sobad_post::get_transactions($id, array('ID', 'barang', 'qty', 'price', 'discount', 'unit', 'note'), $whr);

		$data['class'] = 'purchase';
		$data['table'] = array();

		$no = 0;
		$paid = 0;
		foreach ($args as $key => $val) {
			$no += 1;
			$view = '';

			// Check Custom Design
			$design = sobad_post::get_all(array('ID'), "AND var='custom_design' AND reff='" . $val['barang'] . "'");
			$check = array_filter($design);
			if (!empty($check) && $id > 0) {
				$view = array(
					'ID'	=> 'custom_' . $val['ID'],
					'func'	=> '_customItem',
					'color'	=> 'green',
					'icon'	=> 'fa fa-eye',
					'label'	=> 'custom',
				);

				$view = apply_button($view);
			}

			$nominal = ($val['price'] - $val['discount']);
			$total = $nominal * $val['qty'];
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
					'15%',
					$val['qty'] . ' ' . $val['unit'],
					true
				),
				'Note'			=> array(
					'center',
					'10%',
					$view,
					true
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
		$type = isset($_POST['type']) ? $_POST['type'] : '';

		$sync = array(
			'ID'	=> 'sync_' . $id,
			'func'	=> '_sync_selling',
			'color'	=> 'btn-default',
			'icon'	=> 'fa fa-refresh',
			'label'	=> 'Sync',
			'load'	=> 'portlet_item',
			'status' => '',
			'spin'	=> true,
			'type'	=> $type
		);

		return _click_button($sync);
	}

	private static function _form_action($id = 0)
	{
		$type = isset($_POST['type']) ? $_POST['type'] : '';

		$ambil = array(
			'ID'	=> 'preview_' . $id,
			'func'	=> '_form_warehouse',
			'color'	=> 'btn-default',
			'icon'	=> 'fa fa-print',
			'label'	=> 'Minta Produk',
			'type'	=> $type
		);

		$pack = array(
			'ID'	=> 'preview_' . $id,
			'func'	=> '_view_packing',
			'color'	=> 'btn-default',
			'icon'	=> 'fa fa-print',
			'label'	=> 'Packing Slip',
			'type'	=> $type
		);

		$qrcode = array(
			'ID'	=> 'preview_' . $id,
			'func'	=> '_view_code',
			'color'	=> 'btn-default',
			'icon'	=> 'fa fa-print',
			'label'	=> 'QRcode',
			'type'	=> $type
		);

		return print_button($ambil) . ' ' . print_button($pack) . ' ' . print_button($qrcode);
	}

	public static function _sync_selling($id = 0)
	{
		$iduser = get_id_user();
		$id = str_replace('sync_', '', $id);
		intval($id);

		$order = sobad_post::get_all(array('ID', 'status', 'id_join', 'barang', 'qty', 'unit'), "AND `". base ."post`.status='0' AND `". base ."post`.var IN ('order','non_order')", 'order');
		$check = array_filter($order);
		if (empty($check)) {
			die(_error::_alert_db('Tidak ada data penjualan!!!'));
		}

		$reff = array();
		$product = array();
		$_join = array();
		foreach ($order as $key => $val) {
			if (!in_array($val['ID'], $reff)) {
				$reff[] = $val['ID'];
			}

			$brng = $val['barang'];
			if (!isset($product[$brng])) {
				$product[$brng] = array('qty' => 0, 'unit' => $val['unit']);
			}

			$_join[] = $val['id_join'];
			$product[$brng]['qty'] += $val['qty'];

			// Update status
			sobad_db::_update_single($val['ID'], ''. base .'post', array(
				'status'		=> 2,
			));
		}

		// Insert data barang
		$note = '';
		if ($id == 0) {
			$note = 'form_' . $iduser;
		}

		foreach ($product as $key => $val) {
			sobad_db::_insert_table(''. base .'transaksi', array(
				'post'		=> $id,
				'barang'	=> $key,
				'qty'		=> $val['qty'],
				'unit'		=> $val['unit'],
				'keyword'	=> $note
			));
		}

		// Insert referensi	order
		// --- Check
		$_key = empty($id) ? "_referensi_" . $iduser : "_referensi";
		$meta = sobad_post::get_meta($id, $_key);

		$check = array_filter($meta);
		if (empty($check)) {
			sobad_db::_insert_table(base .'post-meta', array(
				'meta_id'		=> $id,
				'meta_key'		=> '_referensi_' . $iduser,
				'meta_value'	=> serialize($reff),
			));
		}

		// Insert Packing Stock
		$no = quotation_marketing::_get_max('packing_stock');
		foreach ($reff as $key => $val) {
			$no += 1;
			sobad_db::_insert_table(base .'post', array(
				'title'		=> $no,
				'reff'		=> $val,
				'type'		=> $id,
				'var'		=> empty($id) ? 'packing_stock_' . $iduser : 'packing_stock',
			));
		}

		// Change Status product
		$_join = implode(',', $_join);
		sobad_db::_update_multiple("ID IN ($_join)", ''. base .'transaksi', array('keyword' => 1));

		// Get data Table
		$data = self::_table_item($id);
		$table = table_admin($data['data']);

		return $table;
	}

	// ----------------------------------------------------------
	// Form custom design ---------------------------------------
	// ----------------------------------------------------------
	public static function _conv_design($data = '')
	{
		$_data = array();
		$_def = array('nama', 'tanggal', 'dari', 'tambahan', 'ucapan', 'salam');

		preg_match_all("/(.*?):(.*?)(\r\n|;)/", $data, $args);
		$check = array_filter($args[0]);
		if (!empty($check)) {
			foreach ($args[1] as $key => $val) {
				$index = strtolower(trim($val));
				$txt = str_replace('[br]', '<br>', trim($args[2][$key]));
				$_data[$index] = $txt;
			}
		} else {
			$args = explode("\r\n", $data);
			foreach ($args as $key => $val) {
				$txt = str_replace('[br]', '<br>', trim($val));
				$_data[$_def[$key]] = $txt;
			}
		}

		return $_data;
	}

	public static function _product_custom($id = 0)
	{
		// Get data post
		$post = sobad_post::get_transaction($id, array('post', 'barang'));
		$idx = $post[0]['post'];
		$item = $post[0]['barang'];
		$code = $post[0]['product_code_bara'];

		// Get referensi order
		$reff = sobad_post::get_id($idx, array('ID', '_referensi'), "", 'form');
		$reff = unserialize($reff[0]['_referensi']);

		// Get data product
		$products = array();
		foreach ($reff as $val) {
			$_temp = sobad_post::get_transactions($val, array('note'), "AND barang='$item'");
			foreach ($_temp as $ky => $vl) {
				$products[] = empty($vl['note']) ? array() : self::_conv_design($vl['note']);
			}
		}

		return array(
			'item'	=> $item,
			'sku'	=> $code,
			'data'	=> $products
		);
	}

	public static function _customItem($id = 0)
	{
		$id = str_replace('custom_', '', $id);
		custom_designer::set_dpi_design(96);

		$data = array(
			'ID'		=> 'custom_portlet',
			'label'		=> 'View Design',
			'tool'		=> '',
			'action'	=> self::custom_action(),
			'func'		=> '_view_design',
			'object'	=> self::$object,
			'data'		=> $id
		);

		$args = array(
			'title'		=> 'Custom Design',
			'button'	=> '',
			'status'	=> array(),
			'func'		=> array('_portlet'),
			'data'		=> array($data)
		);

		return modal_admin($args);
	}

	protected static function custom_action()
	{
		$add = array(
			'ID'	=> 'download_0',
			'func'	=> 'download_design',
			'color'	=> 'btn-default',
			'icon'	=> 'fa fa-download',
			'label'	=> 'Download',
			'spin'	=> true,
			'script' => 'download_design(this)'
		);

		return _click_button($add);
	}

	public static function _view_design($id = 0)
	{
		$data = self::_product_custom($id);
		$idx = $data['item'];

		$whr = "AND var='custom_design' AND reff='$idx'";
		$post = sobad_post::get_all(array('ID', '_template', '_style', '_design', '_dpi', '_paper_size'), $whr, 'custom_design');
		$post = $post[0];

		$default = unserialize($post['_design']);
?>
		<div id="style-sheet" style="display: none;">
			<style type="text/css">
				div#here_modal2 {
					width: 310mm;
				}

				.custom-design {
					background-color: #fff;
				}

				.upload-image {
					display: none;
				}

				#layout-custom,
				.custom-design .col-md-12 {
					padding: 0px !important;
				}
			</style>
			<?php echo custom_designer::get_style_design($post['_style']); ?>
		</div>
		<div id="layout-custom">
			<?php
			$pages = count($data['data']);
			foreach ($data['data'] as $key => $val) :
				$design = array_merge($default, $val);
				$template = custom_designer::get_layout_design($post['_template'], $design);
			?>
				<div class="custom-design">
					<?php echo $template['design']; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<script type="text/javascript">
			var myTime = 0;

			function download_design(val) {
				myTime += 1;

				var var_timeout = setTimeout(function() {
					myTime += 1;

					var html = $(val).html();
					$(val).html('<i class="fa fa-spinner fa-spin"></i>');
					$(val).attr('disabled', '');

					var element = document.getElementById('layout-custom');
					var opt = {
						margin: 0,
						filename: '<?php echo $data['sku']; ?>.pdf',
						image: {
							type: 'jpeg',
							quality: 0.98
						},
						html2canvas: {
							scale: 3.125,
							imageTimeout: 0,
							useCORS: true
						},
						jsPDF: {
							unit: 'mm',
							format: 'a3',
							orientation: 'portrait'
						}
					};

					// New Promise-based usage:
					html2pdf().set(opt).from(element).save();

					// Old monolithic-style usage:
					html2pdf(element, opt);

					setTimeout(function() {
						$(val).html(html);
					}, 5000);
				}, 1000);

				if (myTime > 1) clearTimeout(var_timeout);
			}

			function exportHTMLToPDF(val) {
				var html = $(val).html();
				$(val).html('<i class="fa fa-spinner fa-spin"></i>');
				$(val).attr('disabled', '');

				var pages = <?php print($pages); ?>;
				var outputType = 'pdfjsnewwindow'; //'blob';

				var opt = {
					margin: 0,
					filename: '<?php echo $data['sku']; ?>.pdf',
					image: {
						type: 'jpeg',
						quality: 0.98
					},
					html2canvas: {
						scale: 3.125,
						imageTimeout: 0,
						useCORS: true
					},
					jsPDF: {
						unit: 'mm',
						format: 'a3',
						orientation: 'portrait'
					}
				};

				const doc = jspdf.jsPDF(opt.jsPDF);
				const pageSize = jspdf.jsPDF.getPageSize(opt.jsPDF);
				for (let i = 0; i < pages; i++) {
					const page = $('#layout-custom>.custom-design:nth-child(' + i + ')');
					const pageImage = html2pdf().from(page).set(opt).outputImg();
					if (i != 0) {
						doc.addPage();
					}
					doc.addImage(pageImage.src, 'jpeg', 0, 0, pageSize.width, pageSize.height);
				}

				setTimeout(function() {
					$(val).html(html);
				}, 5000);

				// This can be whatever output you want. I prefer blob. 
				const pdf = doc.output(outputType);
				return pdf;
			}
		</script>
	<?php
	}

	// ----------------------------------------------------------
	// Function retail to database 2 ------------------------------
	// ----------------------------------------------------------

	public static function _deleteItem($id = 0)
	{
		$id = str_replace('del_', '', $id);
		intval($id);

		// ---- Reset sync item product with order
		$trans = sobad_post::get_transaction($id, array('reff', 'barang'));
		$trans = $trans[0];

		$_key = empty($trans['reff']) ? "_referensi_" . get_id_user() : "_referensi";
		$meta = sobad_post::get_meta($trans['reff'], $_key);

		$check = array_filter($meta);
		if (!empty($check)) {
			$meta = $meta[0]['meta_value'];
			$meta = unserialize($meta);
			$meta = implode(',', $meta);

			$brng = $trans['barang'];
			$order = sobad_post::get_all(array('ID', 'id_join'), "AND `". base ."post`.ID IN ($meta) AND `". base ."transaksi`.barang='$brng'", "order");

			foreach ($order as $key => $val) {
				sobad_db::_update_single($val['id_join'], ''. base .'transaksi', array('ID' => $val['id_join'], 'keyword' => ''));
			}
		}

		// ---- End Reset		

		// hapus transaksi
		$q = sobad_db::_delete_single($id, ''. base .'transaksi');
		$type = $_POST['type'];

		if ($q !== 0) {
			$data = self::_table_item($type);
			$table = table_admin($data['data']);
			return $table;
		}
	}

	// ----------------------------------------------------------
	// Function retail to database ------------------------------
	// ----------------------------------------------------------

	public static function _deleteR($id = 0)
	{
		$id = str_replace('del_', '', $id);
		intval($id);

		$trans = sobad_post::get_transactions($id, array('ID'));
		foreach ($trans as $key => $val) {
			self::_deleteItem($val['ID']);
		}

		// Delete form permintaan Gudang
		sobad_db::_delete_multiple("post='$id' AND var='reduce_stock'", ''. base .'post');

		return self::_delete($id);
	}

	public static function _callback($args = array(), $_args = array())
	{
		$args['var'] = 'form_product';
		$args['user'] = get_id_user();

		$args['post_date'] = $args['post_date'] == '1970-01-01' ? date('Y-m-d') : $args['post_date'];
		$args['_due_date'] = $args['_due_date'] == '1970-01-01' ? '' : $args['_due_date'];

		if (empty($args['ID'])) {
			$args['updated'] = '0000-00-00';
		}

		return $args;
	}

	protected static function _addDetail($args = array(), $_args = array())
	{
		$idx = $args['index'];
		$iduser = get_id_user();
		//$args = sobad_asset::ajax_conv_json($_args);

		// Update Detail Transaksi
		$keyword = 'form_' . $iduser;
		$q = sobad_db::_update_multiple("post='0' AND keyword='$keyword'", ''. base .'transaksi', array('post' => $idx, 'keyword' => ''));

		// Update Post Meta
		$keyword = '_referensi_' . $iduser;
		$q = sobad_db::_update_multiple("meta_id='0' AND meta_key='$keyword'", ''. base .'post-meta', array('meta_id' => $idx, 'meta_key' => '_referensi'));

		// Update Packing Stock
		$keyword = 'packing_stock_' . $iduser;
		$q = sobad_db::_update_multiple("type='0' AND var='$keyword'", ''. base .'post', array('type' => $idx, 'var' => 'packing_stock'));

		// Insert permintaan Gudang
		$no = quotation_marketing::_get_max('reduce_stock');
		$q = sobad_db::_insert_table(base .'post', array(
			'title'		=> $no + 1,
			'user'		=> get_id_user(),
			'updated'	=> '0000-00-00 00:00:00',
			'var'		=> 'reduce_stock',
			'notes'		=> 'product',
			'reff'		=> $idx
		));

		return $q;
	}

	// ----------------------------------------------------------
	// Print Form Order -----------------------------------------
	// ----------------------------------------------------------

	public static function _preview($id)
	{
		$_SESSION[_prefix . 'development'] = 0;
		$id = str_replace('preview_', '', $id);
		intval($id);

		$form = self::_array();
		$form[] = 'user';

		$object = self::$table;
		$item = sobad_post::get_transactions($id, array('barang', 'qty'));
		$data = $object::get_id($id, $form, '', self::$post);

		$title = self::_post_title($data[0]['title'], $data[0]['post_date']);
		$title = str_replace('/', '-', $title);

		$data[0]['item'] = $item;

		$args = array(
			'data'			=> $data[0],
			'header'		=> 'heading',
			'data_header'	=> 'FORM ORDER',
			'html'			=> '_html',
			'object'		=> self::$object,
			'title'			=> 'Packing Slip' . $title
		);

		$args = pdf_setting_retail_form($args);
		return sobad_convToPdf($args);
	}

	public static function _html($post = array())
	{
		$contact = kmi_user::get_id($post['contact'], array('name'));
		$user = kmi_user::get_id($post['user'], array('name'));

		$post['post_code'] = self::_post_title($post['title'], $post['post_date']);
		$post['name_cont'] = $contact[0]['name'];
		$post['name_user'] = $user[0]['name'];

		$dod = '-';
	    if (!empty($data['_due_date']) && $data['_due_date'] != '0000-00-00') {
	        $dod = format_date_id($data['_due_date']);
	    }

	    $post['dod'] = $dod;

		report::view('Retail/form_product/form',$post);
	}

	// ----------------------------------------------------------
	// Print data QRcode Retail ---------------------------------
	// ----------------------------------------------------------

	public function _view_code($data = array())
	{
		$_SESSION[_prefix . 'development'] = 0;

		$data = sobad_asset::ajax_conv_json($data);

		$args = array(
			'data'			=> $data,
			'html'			=> '_html_code',
			'object'		=> self::$object,
			'title'			=> 'QRcode ' . $data['no_form']
		);

		$args = pdf_setting_retail_qrcode($args);
		return sobad_convToPdf($args);
	}

	public function _html_code($data = array())
	{
		$post = sobad_post::get_id($data['ID'], array('ID', '_referensi'), '', 'form');
		$reff = unserialize($post[0]['_referensi']);

		foreach ($reff as $key => $val) {
			$item = sobad_post::get_transactions($val, array('ID', 'barang', 'qty'));

			foreach ($item as $ky => $vl) {
				$brand = self::_conv_brand($vl['type_bara']);

				$meta = sobad_item::get_id($vl['barang'],array('ID','_shape','_dimension'));
				$meta = $meta[0];

				$vl['_shape'] = @$meta['_shape'];
				$vl['_dimension'] = @$meta['_dimension'];

				for ($i = 1; $i <= $vl['qty']; $i++) {
					report::view('Retail/'.$brand.'/qrcode',$vl);
				}
			}
		}
	}

	// ----------------------------------------------------------
	// Print data Packing Slip Retail ---------------------------
	// ----------------------------------------------------------

	public function _view_packing($data = array())
	{
		$_SESSION[_prefix . 'development'] = 0;

		$data = sobad_asset::ajax_conv_json($data);

		$args = array(
			'data'			=> $data,
			'html'			=> '_html_packing',
			'object'		=> self::$object,
			'title'			=> 'QRcode ' . $data['no_form']
		);

		$args = pdf_setting_retail_packing($args);
		return sobad_convToPdf($args);
	}

	public function _html_packing($args = array())
	{
		$post = sobad_post::get_id($args['ID'], array('ID', '_referensi'), '', 'form');
		$reff = unserialize($post[0]['_referensi']);

		foreach ($reff as $key => $val) {
			$order = sobad_post::get_id($val, array('ID', 'title', 'contact', 'type', 'post_date', 'inserted', '_expedition'), '', 'order');
			$data = $order[0];

			$data['order_no'] = transaksi_retail::_post_title($data['title'], $data['meta_note_type'], $data['inserted']);

			$contact = sobad_company::get_id($data['contact'], array('ID', '_address', '_postcode', '_subdistrict', '_city', '_province'));
			$contact = @$contact[0];

			$address = sobad_region::_conv_address($contact['_address'], array(
			    'postcode'		=> $contact['_postcode'],
			    'subdistrict'	=> $contact['_subdistrict'],
			    'city'			=> $contact['_city'],
			    'province'		=> $contact['_province']
			));

			$data['address'] = $address['result'];

			// GET Kurir
			$courier = $data['_expedition'];
			$comp = sobad_company::get_id($courier, array('name'));

			$check = array_filter($comp);
			if (!empty($check)) {
			    $courier = $comp[0]['name'];
			}

			$data['courier'] = $courier;
			$data['product'] = sobad_post::get_transactions($data['ID'],array('barang','qty','unit'));

			_filter_packing_retail($data['ID'],array(
				'data'		=> $data
			));
		}
	}

	// ----------------------------------------------------------
	// Print data Packing Slip Retail ---------------------------
	// ----------------------------------------------------------

	public function _form_warehouse($data = array())
	{
		$_SESSION[_prefix . 'development'] = 0;

		$data = sobad_asset::ajax_conv_json($data);

		$args = array(
			'data'			=> $data,
			'html'			=> '_html_warehoue',
			'object'		=> self::$object,
			'title'			=> 'Permintaan Barang Keluar' . $data['no_form']
		);

		$args = pdf_setting_form_warehouse($args);
		return sobad_convToPdf($args);
	}

	public function _html_warehoue($args = array())
	{
		$post = sobad_post::get_id($args['ID'], array('ID', 'contact', 'user', '_referensi'), '', 'form');
		$reff = unserialize($post[0]['_referensi']);

		foreach ($reff as $key => $val) {
			$order = sobad_post::get_id($val, array('ID', 'title', 'contact', 'type', 'post_date', 'inserted', '_expedition'), '', 'order');
			$data = $order[0];

			$contact = kmi_user::get_id($post[0]['contact'], array('name'));
			$user = kmi_user::get_id($post[0]['user'], array('name'));

			$data['name_user'] = $user[0]['name'];
			$data['name_cont'] = $contact[0]['name'];

			$data['order_no'] = transaksi_retail::_post_title($data['title'], $data['meta_note_type'], $data['inserted']);
			$data['item'] = sobad_post::get_transactions($data['ID'],array('barang','qty','unit'));

			$data['order_no_qr'] = 'ORDER#' . $data['order_no'];

			heading('');
			report::view('Retail/form_product/permintaan_barang',$data);
		}
	}
}
