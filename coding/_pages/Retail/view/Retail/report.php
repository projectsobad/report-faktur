<?php

class report_retail{
	protected static $object = 'report_retail';

	protected static $table = 'sobad_post';

	private static function head_title(){
		$args = array(
			'title'	=> 'Report <small>data report</small>',
			'link'	=> array(
				0	=> array(
					'func'	=> self::$object,
					'label'	=> 'report'
				)
			),
			'date'	=> false
		); 
		
		return $args;
	}

	protected static function get_form(){		
		$box = array(
			'label'		=> 'Form Penjualan',
			'tool'		=> '',
			'action'	=> '',
			'object'	=> self::$object,
			'func'		=> '_form',
			'data'		=> ''
		);

		return $box;
	}

	protected static function get_box(){		
		$box = array(
			'ID'		=> 'report_selling',
			'label'		=> 'Data Penjualan',
			'tool'		=> '',
			'action'	=> '',
			'func'		=> 'sobad_table',
			'data'		=> array(
				'class'		=> '',
				'table'		=> array()
			)
		);

		return $box;
	}

	public static function _sidemenu(){
		$title = self::head_title();
		$data = array();
	
		$data[] = array(
			'style'		=> array(),
			'script'	=> array(),
			'func'		=> '_portlet',
			'data'		=> self::get_form()
		);

		$data[] = array(
			'style'		=> array(),
			'script'	=> array(),
			'func'		=> '_portlet',
			'data'		=> self::get_box()
		);
	
		ob_start();
		theme_layout('_head_content',$title);
		theme_layout('_panel',$data);
		return ob_get_clean();
	}

	public static function _form(){
		$year = array();
		for($i=2019;$i<=date('Y');$i++){
			$year[$i] = $i;
		}

		$month = array();
		for($i=1;$i<=12;$i++){
			$month[$i] = conv_month_id($i);
		}

		$channel = sobad_meta::_gets('channel',array('ID','meta_value'));
		$channel = convToOption($channel,'ID','meta_value');
		$channel[0] = 'Semua';

		$meta = sobad_meta::_gets('category',array('ID','meta_value','meta_reff'),"ORDER BY meta_value ASC");
		$meta = category_designer::_tableTree($meta);

		$categori = product_designer::_option();
		$categori[0] = 'Semua';

		$data = array(
			array(
				'func'			=> 'opt_select',
				'data'			=> $year,
				'key'			=> 'year',
				'label'			=> 'Tahun',
				'class'			=> 'input-circle',
				'searching'		=> true,
				'select'		=> date('Y'),
			),
			array(
				'func'			=> 'opt_select',
				'data'			=> $month,
				'key'			=> 'month',
				'label'			=> 'Bulan',
				'class'			=> 'input-circle',
				'searching'		=> true,
				'select'		=> date('m'),
			),
			array(
				'func'			=> 'opt_select',
				'data'			=> array(1 => 'Omset', 2 => 'Product'),
				'key'			=> 'type',
				'label'			=> 'Jenis',
				'class'			=> 'input-circle',
				'select'		=> 1,
			),
			array(
				'func'			=> 'opt_select',
				'data'			=> $channel,
				'key'			=> 'channel',
				'label'			=> 'Channel',
				'class'			=> 'input-circle',
				'searching'		=> true,
				'select'		=> 0,
			),
			array(
				'func'			=> 'opt_select',
				'data'			=> $categori,
				'key'			=> 'category',
				'label'			=> 'Kategori',
				'class'			=> 'input-circle',
				'select'		=> 0,
			),
		);

		?>
			<div class="row">
				<?php theme_layout('report_form',$data); ?>
				<div style="text-align: right;margin-right: 20px;">
					<button data-sobad="_view" data-load="report_selling" data-type="" type="button" class="btn blue" data-dismiss="modal" onclick="sobad_submitLoad(this)" ><i class="fa fa-book"></i> Report</button>
					<button data-sobad="_print" data-load="" data-type="" type="button" class="btn yellow" data-dismiss="modal" onclick="sobad_report(this)" ><i class="fa fa-print"></i> Print</button>
				</div>
			</div>
		<?php
	}

	// ----------------------------------------------------------
	// Print data report ----------------------------------------
	// ----------------------------------------------------------

	public static function _print($args=array()){
		$_SESSION[_prefix.'development'] = 0;

		$args = sobad_asset::ajax_conv_json($args);

		$args = array(
			'data'			=> $args,
			'html'			=> '_html',
			'object'		=> self::$object,
			'title'			=> 'Report Retail '.conv_month_id($args['month']).' '.$args['year']
		);

		$args = pdf_setting_purchase_report($args);
		return sobad_convToPdf($args);
	}

	public static function _html($post = array()) {
		$check = array_filter($post);
		if(empty($check)){
			return '';
		}

		$data = self::_get_data_report($post);

		$user = kmi_user::get_id(get_id_user(),array('name'));
		$name = isset($user[0]) ? $user[0]['name'] : '';

		$company = sobad_company::get_all(array('_ppn','_type'),"AND `". base ."company`.type='6'",'company_profile');
		$company = $company[0]['_type'] == 1 ? 'Termasuk PPN' : 'Belum PPN';

		$date = 'Tahun : ' . $post['year'] . ' Bulan : ' . conv_month_id($post['month']);

		$header = 'REPORT';
		$report = array(
			'user'		=> $name,
			'data'		=> $data,
			'company'	=> $company,
			'toko' 		=> 'Retail',
			'date'		=> $date,
			'type'		=> $post['type']
		);

		heading();
		report::view('Retail/report/content',$report);		
	}

	private static function _get_data_report($input=array()){
		$user = get_id_user();
		$args = array(
			'ID',
			'title',
			'reff',
			'contact',
			'inserted',
			'post_date',
			'type',
			'status',
			'barang',
			'price',
			'discount',
			'qty'
		);

		$y = $input['year']; $m = $input['month'];

		$meta = sobad_meta::_gets('category',array('ID','meta_value','meta_reff'));
		$cat = category_designer::_indexTree($meta,$input['category']);
		$cat = implode(',', $cat);

		$cat = empty($input['category'])?'':"AND _barang.category IN ($cat)";
		$channel = empty($input['channel'])?'':"AND `". base ."post`.type='".$input['channel']."'";
		
		$where = "AND YEAR(`". base ."post`.post_date)='$y' AND MONTH(`". base ."post`.post_date)='$m' AND `". base ."post`.trash='0' AND `". base ."post`.var='order' $channel $cat";
		$args = sobad_post::get_all($args,$where,'order');

		if($input['type']==1){
			return self::_getReport_omset($args);
		}if($input['type']==2){
			return self::_getReport_product($args);
		}else{
			return '';
		}
	}

	private static function _getReport_omset($args=array()){
		$project = array();
		foreach ($args as $key => $val) {
			$reff = $val['ID'];

			// Check Project
			if(!isset($project[$reff])){
				$project[$reff] = array();
			}

			$harga = $val['price'];
			$discount = $val['discount']<=100?$val['discount']*$harga/100:$val['discount'];
			$total = ($harga - $discount) * $val['qty'];
			$ongkir = self::_getOngkir($reff);

			$project[$reff][] = array(
				'channel'		=> $val['meta_value_type'],
				'customer'		=> $val['name_cont'],
				'order'			=> transaksi_retail::_post_title($val['title'],$val['meta_note_type'],$val['inserted']),
				'date'			=> $val['post_date'],
				'item'			=> $val['name_bara'],
				'sku'			=> $val['product_code_bara'],
				'ongkir'		=> $ongkir,
				'total'			=> $total,
			);
		}

		return $project;
	}

	private static function _getReport_product($args=array()){
		$project = array();
		foreach ($args as $key => $val) {
			$reff = $val['ID'];
			if(!isset($project[$reff])){
				$project[$reff] = array();
			}

			$project[$reff][] = array(
				'channel'		=> $val['meta_value_type'],
				'customer'		=> $val['name_cont'],
				'order'			=> transaksi_retail::_post_title($val['title'],$val['meta_note_type'],$val['inserted']),
				'date'			=> $val['post_date'],
				'item'			=> $val['name_bara'],
				'sku'			=> $val['product_code_bara'],
				'ongkir'		=> '',
				'qty'			=> $val['qty'],
			);
		}

		return $project;
	}

	private static function _getOngkir($id=0){
		$data = sobad_post::get_id($id,array('ID','_shipping_price'),'','order');
		$check = array_filter($data);
		if(!empty($check)){
			return isset($data[0]['_shipping_price'])?$data[0]['_shipping_price']:0;
		}

		return 0;
	}
}