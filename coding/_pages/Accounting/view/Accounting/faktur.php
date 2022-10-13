<?php

class faktur_accounting extends _page
{

	protected static $object = 'faktur_accounting';

	protected static $table = 'sobad_post';

	protected static $post = 'invoice';

	// ----------------------------------------------------------
	// Layout category  ------------------------------------------
	// ----------------------------------------------------------

	protected static function _array()
	{
		$args = array(
			'ID',
			'company',
			'type',
			'title',
			'post_date',
			'inserted',
			'reff',
			'_no_faktur',
			'_discount',
			'var'
		);

		return $args;
	}

	protected static function table($date='')
	{
		$date = empty($date) ? date('Y-m-d') : $date;
		$date = strtotime($date);
		$y = date('Y',$date); $m = date('m',$date);

		$data = array();
		$array = self::_array();

		$where = "AND YEAR(`" . base . "post`.post_date)='$y' AND MONTH(`" . base . "post`.post_date)='$m'";

		$post = self::$type;
		$object = self::$table;
		
		if($post=='invoice'){
			$args = $object::get_invoices($array, $where);
		}else if($post=='order'){
			$args = $object::get_orders($array, $where);
		}else{
			$args = $object::get_fakturPurchase($array, $post, $where);

			if($post=='purchase'){
				$tagihan = $object::get_fakturPurchase($array, 'invoice_purchase', $where);
				$args = array_merge($args,$tagihan);
			}
		}

		$data['class'] = '';
		$data['table'] = array();

		$no = 0;$tot_nominal = 0;$tot_pajak = 0;
		foreach ($args as $key => $val) {
			$id = $val['ID'];

			$edit = array(
				'ID'	=> 'edit_' . $id,
				'func'	=> '_edit',
				'color'	=> 'blue',
				'icon'	=> 'fa fa-edit',
				'label'	=> 'edit',
				'type'	=> self::$type
			);

			$conv = self::_conv_information($post,$val);
			$nominal = $conv['nominal'];
			$ppn = $conv['pajak'];

			if($post=='order' && $ppn<=0){
				continue;
			}

			$tot_nominal += $nominal;
			$tot_pajak += $ppn;

			$no += 1;
			$data['table'][$no - 1]['tr'] = array('');
			$data['table'][$no - 1]['td'] = array(
				'no'		=> array(
					'center',
					'5%',
					$no,
					true
				),
				'Tanggal'	=> array(
					'left',
					'12%',
					format_date_id($val['post_date']),
					true
				),
				$conv['label']	=> array(
					'left',
					'15%',
					$conv['title'],
					true
				),
				'Contact'	=> array(
					'left',
					'auto',
					$val['name_comp'],
					true
				),
				'Faktur'	=> array(
					'left',
					'12%',
					$conv['faktur'],
					true
				),
				'Nominal'	=> array(
					'right',
					'15%',
					'Rp.' . format_nominal($nominal),
					true
				),
				'Pajak'	=> array(
					'right',
					'12%',
					'Rp.' . format_nominal($ppn),
					true
				),
				'Action'	=> array(
					'center',
					'10%',
					edit_button($edit),
					true
				),
			);
		}

		$data['table'][$no]['tr'] = array('');
		$data['table'][$no]['td'] = array(
			'no'		=> array(
				'center',
				'auto',
				'<strong>Total</strong>',
				true,
				5
			),
			'nominal'	=> array(
				'right',
				'15%',
				'<strong>Rp.' . format_nominal($tot_nominal) . '</strong>',
				true
			),
			'pajak'		=> array(
				'right',
				'12%',
				'<strong>Rp.' . format_nominal($tot_pajak) . '</strong>',
				true
			),
			'action'	=> array(
				'center',
				'10%',
				'',
				true
			),
		);

		return $data;
	}

	private static function head_title()
	{
		$args = array(
			'title'	=> 'Faktur<small>faktur pajak</small>',
			'link'	=> array(
				0	=> array(
					'func'	=> self::$object,
					'label'	=> 'faktur'
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
			'label'		=> 'No Faktur',
			'tool'		=> '',
			'action'	=> self::action(),
			'func'		=> 'sobad_table',
			'data'		=> $data
		);

		return $box;
	}

	protected static function layout()
	{
		self::$type = 'invoice';

		$tabs = array();
		$tabs[0] = array(
			'key'	=> 'invoice',
			'label'	=> 'Invoice',
			'qty'	=> ''
		);

		$tabs[1] = array(
			'key'	=> 'order',
			'label'	=> 'Retail',
			'qty'	=> ''
		);

		$tabs[2] = array(
			'key'	=> 'purchase',
			'label'	=> 'Pembelian',
			'qty'	=> ''
		);

		$box = self::get_box();

		$tabs = array(
			'active'	=> self::$type,
			'tab'		=> $tabs,
			'func'		=> '_portlet',
			'data'		=> $box
		);

		$opt = array(
			'title'		=> self::head_title(),
			'style'		=> array(),
			'script'	=> array('')
		);

		return tabs_admin($opt, $tabs);
	}

	protected static function action(){
		$type = self::$type;
		$date = date('Y-m');

		$print = array(
			'ID'	=> 'preview',
			'func'	=> '_preview',
			'color'	=> 'btn-default',
			'icon'	=> 'fa fa-print',
			'label'	=> 'print',
			'type'	=> $type
		);

		$excel = array(
			'ID'	=> 'excel_0',
			'func'	=> '_export_excel',
			'color'	=> 'btn-default',
			'icon'	=> 'fa fa-file-excel-o',
			'label'	=> 'Export',
			'type'	=> $type
		);

		ob_start();
		?>
			<div style="display: inline-flex;margin-right: 20px;" class="input-group input-medium date date-picker" data-date-format="yyyy-mm" data-date-viewmode="months">
				<input id="monthpicker" type="text" class="form-control" value="<?php print($date); ?>" data-sobad="_filter" data-load="sobad_portlet" data-type="<?php print($type); ?>" name="filter_date" onchange="sobad_filtering(this)">
			</div>
			<script type="text/javascript">
				if (jQuery().datepicker) {
					$("#monthpicker").datepicker({
						format: "yyyy-mm",
						viewMode: "months",
						minViewMode: "months",
						rtl: false,
						orientation: "right",
						autoclose: true
					});
				};
			</script>
		<?php

		echo print_button($print);
		echo print_button($excel);
		return ob_get_clean();
	}

	public static function _conv_information($post = '', $val = array()){
		$label = ''; $title = '-';

		if($post=='invoice'){
			$label = 'No Inv';
			$title = invoice_marketing::_post_title($val['title'],$val['inserted']);
			$value = self::_get_nominalInvoice($val['reff_reff'],$val['reff']);

			$nominal = $value['nominal'];
			$ppn = $value['ppn'];
		}else if($post=='order'){
			$label = 'No Order';
			$title = transaksi_retail::_post_title($val['title'],$val['meta_note_type'],$val['inserted']);
			$value = self::_get_nominalOrder($val['ID'],$val['_discount']);

			$nominal = $value['nominal'];
			$ppn = $value['ppn'];

			$val['_no_faktur'] = '-';
		}else if($post=='purchase'){
			if($val['var']=='purchase'){
				$label = 'No PO';
				$title = transaction_purchase::_post_title($val['title'],$val['inserted']);
				$value = self::_get_nominalPurchase($val['ID']);
			}else{
				$label = 'No PO';
				$title = transaction_purchase::_post_title($val['title_reff'],$val['inserted_reff']);
				$value = self::_get_nominal_tagihan($val['ID']);
			}

			$nominal = $value['nominal'] + $value['ongkir'];
			$ppn = $value['ppn'];
		}

		return array(
			'label'		=> $label,
			'title'		=> $title,
			'nominal'	=> $nominal,
			'pajak'		=> $ppn,
			'faktur'	=> $val['_no_faktur']
		);
	}

	public static function _get_nominalInvoice($id_quo=0, $id_project=0){
		//Nominal
		$quotation = sobad_post::get_id($id_quo, array('post_date','_discount', '_shipping_price', '_ppn','_ppn_status'), '', 'quotation');
		if(!isset($quotation[0])){
			return array(
				'total'		=> 0,
				'discount'	=> 0,
				'nominal'	=> 0,
				'ppn'		=> 0,
				'ongkir'	=> 0,
				'status_ppn'=> 0
			);
		}

		$quotation = $quotation[0];

		$project = sobad_post::get_id($id_project, array('_po_number'), '', 'project');
		$project = $project[0];

		$quop = array();
		$quops = sobad_post::get_transactions($id_quo, array('barang', 'price', 'discount'));
		foreach ($quops as $_key => $_val) {
			$quop[$_val['barang']] = $_val;
		}

		$nominal = 0;
		$product = sobad_post::get_transactions($id_project, array('barang', 'qty'), "AND note='1'");
		foreach ($product as $_key => $_val) {
			$brng = $_val['barang'];

			// $_price = $_val['qty'] * $quop[$brng]['price'];
			// $_disc = $quop[$brng]['discount'] <= 100 ? $_price * $quop[$brng]['discount'] / 100 : $quop[$brng]['discount'];
			// $nominal += ($_price - $_disc);

			if (isset($quop[$brng])) {
				$_price = $_val['qty'] * $quop[$brng]['price'];
				$_disc = $quop[$brng]['discount'] <= 100 ? $_price * $quop[$brng]['discount'] / 100 : $quop[$brng]['discount'];
				$nominal += round($_price - $_disc,0);
			}
		}

		$total = $nominal;

		$_discount = (int) $quotation['_discount'] <= 100 ? (int) $quotation['_discount'] * $nominal / 100 : (int) $quotation['_discount'];
		$nominal -= $_discount;

		// check ppn
		$ppn = 0;
		$getData = sobad_company::_check_profile();
		if ($getData['status'] == true) {
			
			$tanggal1 = "2022-04-01";
			$tanggal2 = $quotation['post_date'];

			if ($tanggal2 > $tanggal1) {
				$persen = $getData['_ppn'];
			} else {
				$persen = 10;
			}

			if($quotation['_ppn_status'] == 1){
				$ppn = $nominal * $persen / 100;
				$ppn = round($ppn, 0);

			}else if ($quotation['_ppn_status'] == 2) {
				$price = (100 * $nominal) / (100 + $persen);

				$ppn = $nominal - $price;
				$ppn = round($ppn, 0);
			}
		}

		$ongkir = (int) $quotation['_shipping_price'];

		return array(
			'total'		=> (int) $total,
			'discount'	=> (int) $_discount,
			'nominal'	=> (int) $nominal,
			'ppn'		=> (int) $ppn,
			'ongkir'	=> (int) $ongkir,
			'status_ppn'=> (int) $quotation['_ppn_status']
		);
	}

	public static function _get_nominalOrder($id=0,$discount=0){

		$detail = sobad_post::get_transactions($id,array('barang','qty','price','discount','note','extends'));
		$jumlah = count($detail);
		
		$nominal = 0;$ppn = 0;$ongkir = 0; 
		if($jumlah>0){
			foreach ($detail as $ky => $vl) {
				$value = ($vl['price'] - $vl['discount']) * $vl['qty'];
				if($vl['note']==2){
					$value -= $vl['extends'];
				}

				$nominal += $value;
				$ppn += $vl['extends'];
			}

			$nominal -= (int) $discount;
		}

		return array(
			'nominal'	=> $nominal,
			'ppn'		=> $ppn,
			'ongkir'	=> $ongkir
		);
	}

	public static function _get_nominalPurchase($id=0){

		$detail = sobad_post::get_detail_purchase($id,array('barang','qty','price','discount','extends'));
		$jumlah = count($detail);
		
		$nominal = 0;$ppn = 0;$ongkir = 0;
		if($jumlah>0){
			foreach ($detail as $ky => $vl) {
				if($vl['extends']==0){
					$item = sobad_item::get_id($vl['barang'],array('var'));
					$vl['extends'] = isset($item[0]) ? $item[0]['var'] : 0;
				}

				if($vl['extends']==25){
					$ppn += $vl['price'];
				}else if($vl['extends']==21){
					$ongkir += $vl['price'];
				}else{
					$disc = $vl['discount']<=100?$vl['discount'] * $vl['price'] / 100:$vl['discount'];
					$nominal += round($vl['price'] - $disc,0);
				}
			}
		}

		$purc = sobad_post::get_id($id,array('ID','_shipping_price','_ppn','_mode_ppn','_discount'),'','purchase');
		$purc = $purc[0];

		$nominal -= $purc['_discount'];
		$ongkir += (int) $purc['_shipping_price'];

		if ($purc['_mode_ppn'] == 2) {
			$ppn += (int) $purc['_ppn'];
			$nominal -= $ppn;
		}

		return array(
			'nominal'	=> $nominal,
			'ppn'		=> (int) $purc['_ppn'],
			'ongkir'	=> $ongkir
		);
	}

	public static function _get_nominal_tagihan($reff = 0)
	{
		// get diskon Transaksi
		$purc = sobad_post::get_id($reff, array('ID', '_mode_ppn', '_ppn', '_total'), '', 'invoice_purchase');

		$purc = $purc[0];
		$total = (int) $purc['_total'];
		if ($purc['_mode_ppn'] == 2) {
			$total -= (int) $purc['_ppn'];
		}

		return array(
			'nominal'	=> $total,
			'ppn'		=> (int) $purc['_ppn'],
			'ongkir'	=> 0
		);
	}

	// --------------------------------------------
	// --- Form Layout ----------------------------
	// --------------------------------------------	

	public static function _filter($date = '')
	{
		ob_start();
		self::$type = $_POST['type'];
		$table = self::table($date);
		theme_layout('sobad_table', $table);
		return ob_get_clean();
	}

	protected static function edit_form($vals = array())
	{
		$check = array_filter($vals);
		if (empty($check)) {
			return '';
		}

		$args = array(
			'title'		=> 'Edit cashflow',
			'button'	=> '_btn_modal_save',
			'status'	=> array(
				'link'		=> '_update_db',
				'load'		=> 'sobad_portlet',
				'type'		=> $_POST['type']
			)
		);

		return self::_data_form($args, $vals);
	}

	public static function _data_form($args = array(), $vals = array())
	{
		$check = array_filter($args);
		if (empty($check)) {
			return '';
		}

		$label = ''; $title = '-';

		$post = $_POST['type'];
		if($post=='invoice'){
			$label = 'No Inv';
			$title = invoice_marketing::_post_title($vals['title'],$vals['inserted']);
		}else if($post=='purchase'){
			$label = 'No PO';
			$title = transaction_purchase::_post_title($vals['title'],$vals['inserted']);
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
				'key'			=> 'postcode',
				'label'			=> $label,
				'class'			=> 'input-circle',
				'value'			=> $title,
				'data'			=> 'placeholder="" disabled'
			),
			array(
				'func'			=> 'opt_input',
				'type'			=> 'text',
				'key'			=> 'company',
				'label'			=> 'Contact',
				'class'			=> 'input-circle',
				'value'			=> $vals['name_comp'],
				'data'			=> 'placeholder="" disabled'
			),
			array(
				'func'			=> 'opt_input',
				'type'			=> 'text',
				'key'			=> 'post_date',
				'label'			=> 'Tanggal',
				'class'			=> 'input-circle',
				'value'			=> format_date_id($vals['post_date']),
				'data'			=> 'placeholder="" disabled'
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
		);

		$args['func'] = array('sobad_form');
		$args['data'] = array($data);

		return modal_admin($args);
	}

	// --------------------------------------------
	// --- Print Faktur ---------------------------
	// --------------------------------------------	

	public static function _filter_data(){
		$type = $_GET['type'];
		$filter = empty($_GET['filter']) ? date('Y-m-d') : $_GET['filter'];

		$_date = strtotime($filter);
		$y = date('Y', $_date);
		$m = date('m', $_date);
		$date = conv_month_id($m) . ' ' . $y;

		if($type=='invoice'){
			$title = 'Invoice';
		}else if($type=='order'){
			$title = 'Retail';
		}else{
			$title = 'Purchase';
		}

		// Set Data
		$data = array(
			'type'	=> $type,
			'filter'=> $filter,
			'title'	=> $title,
			'date'	=> $date
		);

		return $data;
	}

	public function _export_excel()
	{
		$data = self::_filter_data();

		ob_start();
		header("Content-type: application/vnd-ms-excel");
		header("Content-Disposition: attachment; filename=Faktur " . $data['title'] . " - " . $data['date'] . ".xls");

		self::_html($data); //self::_headReport($data);
		return ob_get_clean();
	}

	public static function _preview()
	{
		$_SESSION[_prefix . 'development'] = 0;

		$post = $_GET['type'];
		$data = self::_filter_data();

		$args = array(
			'data'			=> $data,
			'html'			=> '_html',
			'object'		=> self::$object,
			'title'			=> 'Faktur ' . $data['title'] . ' - ' . $data['date']
		);

		$args = pdf_setting_account_cashflow($args);
		return sobad_convToPdf($args);
	}

	public static function _html($args = array())
	{
		// Get Name Account
		$title = $args['title'];
		$type = $args['type'];
		$date = $args['filter'];

		$_date = strtotime($date);
		$y = date('Y', $_date);
		$m = date('m', $_date);

		// Body Report //
		$where = "AND YEAR(`" . base . "post`.post_date)='$y' AND MONTH(`" . base . "post`.post_date)='$m'";

		$array = self::_array();
		$object = self::$table;

		if($type=='invoice'){
			$args = $object::get_invoices($array, $where);
		}else if($type=='order'){
			$args = $object::get_orders($array, $where);
		}else{
			$args = $object::get_fakturPurchase($array, $post, $where);

			if($type=='purchase'){
				$tagihan = $object::get_fakturPurchase($array, 'invoice_purchase', $where);
				$args = array_merge($args,$tagihan);
			}
		}

		$data = array();
		$nominal = $pajak = 0;
		foreach ($args as $key => $val) {
			$conv = self::_conv_information($type,$val);
			$label = $conv['label'];

			if($post=='order' && $conv['pajak'] <= 0){
				continue;
			}

			$nominal += $conv['nominal'];
			$pajak += $conv['pajak'];

			$data[] = array(
				'date'		=> format_date_id($val['post_date']),
				'title'		=> $conv['title'],
				'contact'	=> $val['name_comp'],
				'faktur'	=> $conv['faktur'],
				'nominal'	=> 'Rp. ' . format_nominal($conv['nominal']),
				'pajak'		=> 'Rp. ' . format_nominal($conv['pajak'])
			);
		}

		$data_header = array(
			'title'			=> $title,
			'label'			=> $label,
			'date'			=> $date,
			'data'			=> $data,
			'tot_nominal'	=> 'Rp. ' . format_nominal($nominal),
			'tot_pajak'		=> 'Rp. ' . format_nominal($pajak)
		);

		heading();
		report::view('Accounting/report/faktur',$data_header);
	}
}
