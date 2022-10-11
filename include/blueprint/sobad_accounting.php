<?php

class sobad_accounting{
	// No refferensi
	public static function refferensi_akun($key=''){
		$args = array(
			'asset'			=> array(
				'Kas'								=> 10,
				'Kas Bank'							=> 20,
				'Piutang Usaha'						=> 30,
				'Piutang Usaha belum Ditagihkan'	=> 31,
				'Piutang Usaha Karyawan'			=> 32,
				'Piutang Lain'						=> 33,
				'Piutang Perusahaan'				=> 34,
				'Persediaan Bahan Baku'				=> 40,
				'Persediaan Perlengkapan Produksi'	=> 41,
				'Persediaan Barang dalam Proses'	=> 42,
				'Persediaan Bahan Jadi'				=> 43,
				'Bayar dimuka'						=> 50,
//				'Pajak'								=> 60,
				'Harta Tetap'						=> 70,
				'Mesin'								=> 71,
				'Perlengkapan Kantor'				=> 72,
				'Peralatan Kantor'					=> 73,
				'Penyusutan Harta tetap'			=> 80
			),
			'liability'		=> array(
				'Utang Usaha'				=> 10,
				'Utang Usaha belum Ditagih'	=> 20,
				'Utang Gaji dan Upah'		=> 30,
				'Utang Komisi Penjualan'	=> 40,
				'Utang Muka Penjualan'		=> 50,
				'Utang Pajak'				=> 60,
				'Utang Perusahaan'			=> 70,
				'Utang Karyawan'			=> 80
			),
			'equility'		=> array(
				'Modal Disetor'			=> 10,
				'Modal Awal'			=> 11,
				'Laba Ditahan'			=> 20,
				'Laba tahun Berjalan'	=> 30,
				'Historical Balancing'	=> 40,
				'prive'					=> 50
			),
			'pendapatan'	=> array(
				'Penjualan Produk'					=> 10,
				'Retur Penjualan'					=> 11,
				'Cashback Penjualan'				=> 12,
				'Biaya Kirim'						=> 13,
				'Pajak Keluaran'					=> 14,
				'Laba/Rugi Selisih Kurs'			=> 20,
				'Laba/Rugi Selisih Emas'			=> 30,
				'Laba/Rugi Penjualan Harta Tetap'	=> 40,
				'Potongan Penjualan'				=> 50,
				'Bunga Bank'						=> 60,
				'Lain - Lain'						=> 70
			),
			'beban'			=> array(
				'Beban Atas Pendapatan'		=> 10,
				'Beban Operasional'			=> 20,
				'Beban Air'					=> 21,
				'Beban Listrik'				=> 22,
				'Beban Perawatan'			=> 23,
				'Beban Promosi'				=> 24,
				'Beban Sewa'				=> 25,
				'Beban Makan Kantor'		=> 26,
				'Biaya Kirim'				=> 27,
				'Beban Non Operasional'		=> 30,
				'Beban Lain'				=> 40,
				'Beban Asuransi'			=> 41,
				'Beban Kebersihan'			=> 42,
				'Biaya Admin bank'			=> 50,
				'Pajak Bunga'				=> 60,
				'Lain - Lain'				=> 70,
				'Potongan Pembelian'		=> 80,
				'Pajak Masukan'				=> 81,
				'Pembelian'					=> 82,
				'Retur Pembelian'			=> 83
			),
			'lain-lain'		=> array(
				'Beban Gaji'				=> 10,
				'Ikhtisar laba rugi'		=> 20,
			),
		);

		return isset($args[$key]) ? $args[$key] : $args;
	}

	public static function refferensi_type($key=0){
		$args = array(
		// Asset
			1	=> array(
				'name' 	=> 'Asset',
				'type'	=> 1,
				'reff'	=> 70,
			),
			2 	=> array(
				'name'	=>'Barang Dagang',
				'type'	=> 5,
				'reff'	=> 82,
			),
			3 	=> array(
				'name'	=> 'Perlengkapan',
				'type'	=> 1,
				'reff'	=> 72,
			),
			4 	=> array(
				'name'	=> 'Akrual',
				'type'	=> 0,
				'reff'	=> 0,
			),
			5 	=> array(
				'name'	=> 'Kredit',
				'type'	=> 0,
				'reff'	=> 0,
			),
			6 	=> array(
				'name'	=> 'Peralatan',
				'type'	=> 1,
				'reff'	=> 73,
			),
			7 	=> array(
				'name' => 'Prive',
				'type'	=> 3,
				'reff'	=> 50,
			),
			8 	=> array(
				'name'	=> 'Mesin',
				'type'	=> 1,
				'reff'	=> 71,
			),
			9 	=> array(
				'name'	=> 'Perlengkapan Produksi',
				'type'	=> 1,
				'reff'	=> 41,
			),

		// Beban	
			21 	=> array(
				'name'	=> 'Operasional',
				'type'	=> 5,
				'reff'	=> 20,
			),
			22	=> array(
				'name'	=> 'Penjualan',
				'type'	=> 5,
				'reff'	=> 24,
			),
			23	=> array(
				'name'	=> 'KMI',
				'type'	=> 0,
				'reff'	=> 0,
			),
			24	=> array(
				'name'	=> 'Kebutuhan Lain',
				'type'	=> 5,
				'reff'	=> 40,
			),
			25	=> array(
				'name'	=> 'Pajak',
				'type'	=> 5,
				'reff'	=> 81,
			),
			26	=> array(
				'name'	=> 'Sewa',
				'type'	=> 5,
				'reff'	=> 25,
			),
			27	=> array(
				'name'	=> 'Perawatan',
				'type'	=> 5,
				'reff'	=> 23,
			),
			28	=> array(
				'name'	=> 'Perizinan',
				'type'	=> 5,
				'reff'	=> 40,
			),
			29	=> array(
				'name'	=> 'Asuransi',
				'type'	=> 5,
				'reff'	=> 41,
			),
			30	=> array(
				'name'	=> 'Medis',
				'type'	=> 5,
				'reff'	=> 40,
			),
			31	=> array(
				'name'	=> 'Makan Kantor',
				'type'	=> 5,
				'reff'	=> 26,
			),
			32	=> array(
				'name'	=> 'Riset',
				'type'	=> 5,
				'reff'	=> 40,
			),
			33	=> array(
				'name'	=> 'Kebersihan',
				'type'	=> 5,
				'reff'	=> 42,
			),
			34	=> array(
				'name'	=> 'Jamuan Tamu',
				'type'	=> 5,
				'reff'	=> 40,
			),
			35	=> array(
				'name'	=> 'Air',
				'type'	=> 5,
				'reff'	=> 21,
			),
			36	=> array(
				'name'	=> 'Listrik',
				'type'	=> 5,
				'reff'	=> 22,
			),

		// Sosial	
			51 	=> array(
				'name'	=> 'Sosial',
				'type'	=> 5,
				'reff'	=> 70,
			),
			52	=> array(
				'name'	=> 'Iuran',
				'type'	=> 5,
				'reff'	=> 70,
			)
		);

		return isset($args[$key]) ? $args[$key] : $args;
	}

	// Harta transaksi
	public static function class_asset(){
		$asset = array();
		$akun = self::refferensi_akun('asset');
		foreach ($akun as $key => $val) {
			$asset[$key] = array(
				'kode'	=> $val,
				'data'	=> array()
			);
		}

		return $asset;
	}

	// Tutup Buku
	public static function _close_books($date=''){
		self::_close_jurnal($date);
		self::_close_bigBook($date);
		self::_close_saldoNeraca($date);
		self::_close_laporan($date);
	}

	public static function _filter_akun_purchase($idx=0,$bayar=0){
		$data = $potongan = array();

		$purchase = sobad_post::get_id($idx,array('ID','company','contact','post_date','_shipping_price','_total','_discount','_ppn','_mode_ppn'),'','purchase');
		if(!isset($purchase[0])){
			return 0;
		}

		$purchase = $purchase[0];

		// Insert Pajak
		if(!empty($purchase['_ppn'])){
			$pajak = array(
				'post_id'		=> $purchase['ID'],
				'type_akun'		=> 5,
				'reff_akun'		=> 81,
				'type_report'	=> 5,
				'debit'			=> intval($purchase['_ppn']),
				'type_jurnal'	=> 6
			);

			self::_insert_dataJurnal($pajak, $purchase['post_date']);
		}

		$trans = sobad_post::get_transactions($idx,array('barang','qty','price','discount','extends'));
		foreach ($trans as $key => $val) {
			$ext = empty($val['extends']) ? $val['var_bara'] : $val['extends'];
			
			$subtotal = $val['price'];
			$discount = $val['discount'];

			$subtotal -= $discount;
			if($purchase['_mode_ppn']==1){
				$total = intval($purchase['_total']);
				$ppn = intval($purchase['_ppn']);

				$bagi = $subtotal / $total;
				$subtotal += round($bagi * $ppn,0);
			}

			$reff_type = self::refferensi_type($ext);
			$rft = isset($reff_type['type']) ? $reff_type['type'] : 0;
			$rff = isset($reff_type['reff']) ? $reff_type['reff'] : 0;

			$ext = $rft . $rff;

			if($rft==2 && $bayar==0){
				$rft = 2;
				$rff = 10;
			}

			if(!isset($data[$ext])){
				$data[$ext] = array(
					'post_id'		=> $purchase['ID'],
					'type_akun'		=> $rft,
					'reff_akun'		=> $rff,
					'id_akun'		=> empty($purchase['company']) ? $purchase['contact'] : $purchase['company'],
					'type_report'	=> $bayar == 0 ? 0 : 4,
					'currency'		=> 'IDR',
					'debit'			=> 0,
					'kredit'		=> 0,
					'type_jurnal'	=> $bayar == 0 ? 2 : 3,
					'reff_jurnal'	=> 0
				);

				$potongan[$ext] = $data[$ext];
			}

			if($bayar>0){
				$potongan[$ext]['kredit'] += $discount;
				$data[$ext]['debit'] += $subtotal;
			}else{
				$data[$ext]['kredit'] += $subtotal;
			}
		} 

		$no = 1;
		$ship = intval($purchase['_shipping_price']);

		foreach ($data as $key => $val) {
			$q = self::_insert_dataJurnal($val,$purchase['post_date']);

			if($no == 1 && $ship > 0){
				$ongkir = $val;
				$ongkir['reff_jurnal'] = $q;

				if($bayar > 0){
					$ongkir['type_akun'] = 5;
					$ongkir['reff_akun'] = 27;

					$ongkir['debit'] = $ship;
				}else{
					$ongkir['kredit'] = $ship;
				}
			}

			if($potongan[$key]['kredit'] > 0 && $bayar > 0){
				$pot = $potongan[$key];

				$pot['type_akun'] = 5;
				$pot['reff_akun'] = 80;
				$pot['reff_jurnal'] = $q;

				$p = self::_insert_dataJurnal($pot,$purchase['post_date']);
			}

			$no += 1;
		}

	}

	protected static function _insert_dataJurnal($val=array(), $post_date=''){
		$check = sobad_jurnal::_check($val['post_id'], $val['type_akun'], $val['reff_akun'], $val['type_jurnal'], $post_date);
		
		if(!empty($check)){
			// Update Data
			$q = sobad_db::_update_single($check, base .'jurnal', array(
				'ID'			=> $check,
				'post_id'		=> $val['post_id'],
				'debit'			=> $val['debit'],
				'kredit'		=> $val['kredit']
			));
		}else{
			// Insert Data
			$val['close_date'] = $post_date;
			$val['insert_jurnal'] = date('Y-m-d H:i:s');
			$q = sobad_db::_insert_table(base . 'jurnal', $val);
		}

		return $q;
	}

	protected static function _insert_dataBukuBesar($val=array(), $post_date=''){
		$check = sobad_jurnal::_check($val['post_id'], $val['type_akun'], $val['reff_akun'], $val['type_jurnal'], $post_date,$val['id_akun']);
		
		if(!empty($check)){
			// Update Data
			$q = sobad_db::_update_single($check, base .'jurnal', array(
				'ID'			=> $check,
				'post_id'		=> $val['post_id'],
				'debit'			=> $val['debit'],
				'kredit'		=> $val['kredit']
			));
		}else{
			// Insert Data
			$val['close_date'] = $post_date;
			$val['insert_jurnal'] = date('Y-m-d H:i:s');
			$q = sobad_db::_insert_table(base . 'jurnal', $val);
		}

		return $q;
	}

	public static function _close_jurnal($date=''){
		$date = empty($date) ? date('Y-m') : $date;
		$date = strtotime($date);

		$y = date('Y',$date); $m = date('m',$date);
		$year_month = "AND YEAR(`".base."post`.post_date)='$y' AND MONTH(`".base."post`.post_date)='$m'";

		// -----------------------------------------------------------------------------------------------------------
		// Get Sell and Purchase
		$arr_order = array('ID','type','company','contact','post_date','var','reff','_nominal');
		
		$where = "`". base ."post`.var IN ('invoice','order','purchase') AND `". base ."post`.trash='0' $year_month";
		$project = sobad_post::get_all($arr_order,$where,'invoice');

		$args = array(
			'post_id'		=> 0,
			'type_akun'		=> 0,
			'reff_akun'		=> 0,
			'id_akun'		=> 0,
			'type_report'	=> 0,
			'currency'		=> 'IDR',
			'debit'			=> 0,
			'kredit'		=> 0,
			'type_jurnal'	=> 0,
			'reff_jurnal'	=> 0
		);

		foreach ($project as $key => $val) {
			$id = $val['ID'];
			
			if($val['var']=='invoice'){
				$data = $args;

				$tagihan = invoice_marketing::_get_detail_tagihan($val);

				$data['post_id'] = $id;
				$data['type_akun'] = 1;
				$data['reff_akun'] = 30;
				$data['id_akun'] = empty($val['company']) ? $val['contact'] : $val['company'];

				$data['debit'] = $tagihan['total'];
				$data['type_jurnal'] = 1;

				$q = self::_insert_dataJurnal($data,$val['post_date']);

				// Insert data pajak
				$data['type_akun'] = 4;
				$data['reff_akun'] = 14;
				$data['type_report'] = 5;

				$data['type_jurnal'] = 6;
				$data['reff_jurnal'] = $q;

				$data['debit'] = 0;
				$data['kredit'] = $tagihan['pajak'];
				$q = self::_insert_dataJurnal($data,$val['post_date']);

			}else if($val['var']=='order'){
				// Check piutang atau tidak
				$post_date = $val['post_date'];

				$whr = "AND post_date='$post_date' AND reff='$id' AND var='paid' AND price>'0'";
				$check = sobad_post::get_all(array('ID','post_date','price'),$whr,'paid');

				if(isset($check[0])){
					continue;
				}

				$payment = debit_retail::detail_payment($val);
				$data = $args;

				$data['post_id'] = $id;
				$data['type_akun'] = 1;
				$data['reff_akun'] = 30;
				$data['id_akun'] = $val['contact'];

				$data['debit'] = $payment['total'];
				$data['type_jurnal'] = 1;

				$q = self::_insert_dataJurnal($data,$val['post_date']);

			}else if($val['var']=='purchase'){
				// Check utang atau tidak
				$post_date = $val['post_date'];

				$whr = "AND post_date='$post_date' AND reff='$id' AND var='pay' AND price>'0'";
				$check = sobad_post::get_all(array('ID','post_date','price'),$whr,'paid');

				if(isset($check[0])){
					continue;
				}

				self::_filter_akun_purchase($val['ID']);
			}		
		}

		// -----------------------------------------------------------------------------------------------------------
		// Get Cashflow
		$arr_cash = array('ID','type','company','contact','post_date','var','price','reff');

		$where = "$year_month AND `".base."transaksi`.price>'0'";
		$cash = sobad_post::get_cashflows($arr_cash,$where);

		$args = array(
			'post_id'		=> 0,
			'type_akun'		=> 0,
			'reff_akun'		=> 0,
			'id_akun'		=> 0,
			'type_report'	=> 0,
			'currency'		=> 'IDR',
			'debit'			=> 0,
			'kredit'		=> 0,
			'type_jurnal'	=> 0,
			'reff_jurnal'	=> 0
		);

		foreach ($cash as $key => $val) {
			$id = $val['ID'];

			if($val['var']=='paid'){

				if($val['var_reff']=='invoice'){
					$sts_disc = $sts_ongkir = false;

					$idj = sobad_jurnal::_get_jurnal($val['ID'],1,30,4);

					$data = $args;
					$data['post_id'] = $id;
					$data['type_akun'] = 1;
					$data['reff_akun'] = 30;
					$data['id_akun'] = empty($val['company_reff']) ? $val['contact_reff'] : $val['company_reff'];

					$data['kredit'] = $val['price'];
					$data['type_jurnal'] = 4;
					$data['reff_jurnal'] = $idj;

					$q = self::_insert_dataJurnal($data,$val['post_date']);

					// get nominal invoice
					$inv = sobad_post::get_id($val['reff'],array('ID','reff','_nominal'),"","invoice");
					$inv = $inv[0];

					$value = faktur_accounting::_get_nominalInvoice($inv['reff_reff'], $inv['reff']);
					$nominal = $value['nominal'];
					$discount = $value['discount'];
					$total = $value['total'];
					$ongkir = $value['ongkir'];
					$pajak = $value['ppn'];

					if($value['status_ppn']==2){
						$nominal -= $pajak;
					}

					// Get DP Invoice
					$dp = invoice_marketing::_get_dp($inv['_nominal'], $nominal, $val['type_reff'], $inv['reff']);

					// Get Termin Invoice
					$termin = invoice_marketing::_get_termin($dp, $inv['_nominal'], $total, $discount, $val['type_reff'], $inv['reff'],0,$val['reff']);

					// Get Lunas Invoice
					$lunas = invoice_marketing::_get_lunas($dp, $termin['nominal'], $nominal);

					// Check type invoice
					if($val['type_reff']==4){
						$tagihan = $lunas;

						$sts_disc = true;
						$sts_ongkir = true;


					}else if($val['type_reff']==2){
						$tagihan = $termin['nominal'];
						$discount = $discount - $termin['discount'];

						$sts_disc = true;
					}else{
						$tagihan = $dp;

						$sts_disc = true;
					}

					// get potongan penjualan
					if($sts_disc && $discount > 0){

						$data['type_akun'] = 4;
						$data['reff_akun'] = 50;

						$data['debit'] = $discount;
						$data['kredit'] = 0;
						$data['reff_jurnal'] = $q;

						$pq = self::_insert_dataJurnal($data,$val['post_date']);
					}

					// Insert biaya pengiriman
					if($sts_ongkir && $ongkir > 0){

						$data['type_akun'] = 4;
						$data['reff_akun'] = 13;

						$data['kredit'] = $ongkir;
						$data['debit'] = 0;
						$data['reff_jurnal'] = $q;

						$oq = self::_insert_dataJurnal($data,$val['post_date']);
					}

					// Pajak Keluaran
					if($value['status_ppn']>0){
						$result_ppn = round(($tagihan / $nominal) * $pajak,0);

						$data['type_akun'] = 4;
						$data['reff_akun'] = 14;

						$data['kredit'] = $result_ppn;
						$data['debit'] = 0;
						$data['reff_jurnal'] = $q;

						$oq = self::_insert_dataJurnal($data,$val['post_date']);
					}

				}else if($val['var_reff']=='order'){
					$order = sobad_post::get_id($val['reff'],array('ID','post_date'),'','order');
					$order = $order[0];

					$idj = 0;
					if($order['post_date'] != $val['post_date']){
						$idj = sobad_jurnal::_get_jurnal($order['ID'],1,30,4);
					}

					$payment = debit_retail::detail_payment($val);
					$data = $args;

					$data['post_id'] = $id;
					$data['type_akun'] = 1;
					$data['reff_akun'] = 30;
					$data['id_akun'] = $val['contact'];

					$data['debit'] = $payment['total'];
					$data['type_jurnal'] = 1;
					$data['reff_jurnal'] = $idj;

					$q = self::_insert_dataJurnal($data,$val['post_date']);

					// Insert data pajak
					$data['type_akun'] = 4;
					$data['reff_akun'] = 14;
					$data['type_report'] = 5;

					$data['type_jurnal'] = 6;
					$data['reff_jurnal'] = $q;

					$data['debit'] = 0;
					$data['kredit'] = $payment['ppn'];
					$q = self::_insert_dataJurnal($data,$val['post_date']);
				}

			}else if($val['var']=='pay'){

				self::_filter_akun_purchase($val['reff'],$val['price']);
			}else if($val['var']=='cash_in'){
				if($val['type']==0){
					continue;
				}

				$data = $args;
				$data['post_id'] = $id;
				$data['type_akun'] = $val['type'] == 1 ? 3 : 2;
				$data['reff_akun'] = $val['type'] == 1 ? 10 : 70;
				$data['id_akun'] = $val['company'];

				$data['debit'] = $val['price'];
				$data['type_jurnal'] = 4;

				$q = self::_insert_dataJurnal($data,$val['post_date']);
			}else if($val['var']=='interest_bank'){

				$data = $args;
				$data['post_id'] = $id;
				$data['type_akun'] = 4;
				$data['reff_akun'] = $val['type'] == 3 ? 60 : 70;
				$data['id_akun'] = $val['company'];

				$data['debit'] = $val['price'];
				$data['type_jurnal'] = 4;

				$q = self::_insert_dataJurnal($data,$val['post_date']);
			}else if($val['var']=='cash_out'){
				if($val['type']==0){
					continue;
				}
				
				$data = $args;
				$data['post_id'] = $id;
				$data['type_akun'] = 1;
				$data['reff_akun'] = 34;
				$data['id_akun'] = $val['company'];

				$data['debit'] = $val['price'];
				$data['type_jurnal'] = 3;

				$q = self::_insert_dataJurnal($data,$val['post_date']);
			}else if($val['var']=='cost_bank'){
				$types = array(0,5,5,4,4,5,4,6);
				$reffs = array(0,50,60,60,70,70,12,10);

				$tp = $val['type'];

				$data = $args;
				$data['post_id'] = $id;
				$data['type_akun'] = $types[$tp];
				$data['reff_akun'] = $reffs[$tp];
				$data['id_akun'] = $val['company'];

				$data['debit'] = $val['price'];
				$data['type_jurnal'] = 3;

				$q = self::_insert_dataJurnal($data,$val['post_date']);
			}
		}
	}

	public static function _close_bigBook($date=''){
		$args = array(
			'post_id'		=> 0,
			'type_akun'		=> 0,
			'reff_akun'		=> 0,
			'id_akun'		=> 0,
			'debit'			=> 0,
			'kredit'		=> 0,
			'type_jurnal'	=> 0,
			'close_date'	=> $date . '-01'
		);

		// get buku besar lalu
		$_now = strtotime($date);
		$now = strtotime('-1 months',$_now);
		$now = date('Y-m',$now);

		// Buku Pembantu Piutang
		$arr_bb = array();
		$bpp = sobad_jurnal::_get_jurnal_by_bpp($date);
		foreach ($bpp as $key => $val) {
			$tp = $val['type_akun'];
			$rf = $val['reff_akun'];
			$ida = $val['id_akun'];

			$ky = $tp . $rf . '.' . $ida;
			if(!isset($arr_bb[$ky])){
				$arr_bb[$ky] = $args;

				// Get Kas Masuk
				$jkm = 0;
				$jkm_s = sobad_jurnal::_get_jkm_by_bpp($tp,$rf,$ida,$date);
				foreach ($jkm_s as $_ky => $_vl) {
					$jkm += $_vl['kredit'];
				}

				$arr_bb[$ky]['type_akun'] = $tp;
				$arr_bb[$ky]['reff_akun'] = $rf;
				$arr_bb[$ky]['id_akun'] = $ida;

				$arr_bb[$ky]['type_jurnal'] = 11;
				$arr_bb[$ky]['close_date'] = $val['close_date'];

				$arr_bb[$ky]['kredit'] = $jkm;
				$arr_bb[$ky]['debit'] = 0;
			}

			$arr_bb[$ky]['debit'] += $val['debit'];
		}

		// Get NS - bulan lalu
		$arr_bby = array();
		$bppy = sobad_jurnal::_get_bukuBesar(11,$now, "AND debit>'0'");
		foreach ($bppy as $key => $val) {
			$tp = $val['type_akun'];
			$rf = $val['reff_akun'];
			$ida = $val['id_akun'];

			$reff = $tp . $rf . '.' . $ida;
			if(isset($arr_bb[$reff])){
				$arr_bb[$reff]['debit'] += $val['debit'];
			}else{
				unset($val['ID']);
				$arr_bb[$reff] = $val;
			}

			$arr_bb[$reff]['close_date'] = $date . '-01';
		}

		foreach ($arr_bb as $key => $val) {
			$val['debit'] -= $val['kredit'];
			$val['kredit'] = 0;

			$q = self::_insert_dataBukuBesar($val,$val['close_date']);
		}

		// Buku Pembantu Utang
		$arr_bb = array();
		$bpu = sobad_jurnal::_get_jurnal_by_bpu($date);
		foreach ($bpu as $key => $val) {
			$tp = 2;
			$rf = 10;
			$ida = $val['id_akun'];

			$psd = $val['post_id'];
			$typ = $val['type_akun'];
			$rff = $val['reff_akun'];

			$ky = $tp . $rf . '.' . $ida;
			if(!isset($arr_bb[$ky])){
				$arr_bb[$ky] = $args;

				// Get Kas keluar
				$jkk = 0;
				$jkk_s = sobad_jurnal::gets_jurnal(3,$date,"AND id_akun='$ida'");
				foreach ($jkk_s as $_ky => $_vl) {
					$jkk += $_vl['debit'];
				}

				$arr_bb[$ky]['type_akun'] = $tp;
				$arr_bb[$ky]['reff_akun'] = $rf;
				$arr_bb[$ky]['id_akun'] = $ida;

				$arr_bb[$ky]['type_jurnal'] = 12;
				$arr_bb[$ky]['close_date'] = $val['close_date'];

				$arr_bb[$ky]['kredit'] = 0;
				$arr_bb[$ky]['debit'] = 0;
			}

			$arr_bb[$ky]['kredit'] += $val['kredit'];
			$lmt = "AND id_akun='$ida' AND post_id='$psd' AND type_akun='$typ' AND reff_akun='$rff'";

			// Get jurnal pembelian
			$jp_s = sobad_jurnal::gets_jurnal(3,$date,$lmt);
			$arr_bb[$ky]['debit'] += isset($jp_s[0]) ? $jp_s[0]['debit'] : 0;
		}

		// Get NS - bulan lalu
		$arr_bby = array();
		$bppy = sobad_jurnal::_get_bukuBesar(12,$now, "AND debit>'0'");
		foreach ($bppy as $key => $val) {
			$tp = $val['type_akun'];
			$rf = $val['reff_akun'];
			$ida = $val['id_akun'];

			$reff = $tp . $rf . '.' . $ida;
			if(isset($arr_bb[$reff])){
				$arr_bb[$reff]['kredit'] += $val['kredit'];
			}else{
				unset($val['ID']);
				$arr_bb[$reff] = $val;
			}

			$arr_bb[$reff]['close_date'] = $date . '-01';
		}

		foreach ($arr_bb as $key => $val) {
			$val['kredit'] -= $val['debit'];
			$val['debit'] = 0;

			$q = self::_insert_dataBukuBesar($val,$val['close_date']);
		}

		// Buku Besar Utama
		$arr_bb = array();
		$bbu = sobad_jurnal::_get_jurnal_by_bbu($date);
		foreach ($bbu as $key => $val) {
			$tp = $val['type_akun'];
			$rf = $val['reff_akun'];

			$ky = $tp . $rf;
			if(!isset($arr_bb[$ky])){
				$arr_bb[$ky] = $args;

				$arr_bb[$ky]['type_akun'] = $tp;
				$arr_bb[$ky]['reff_akun'] = $rf;

				$arr_bb[$ky]['type_jurnal'] = 10;
				$arr_bb[$ky]['close_date'] = $val['close_date'];
			}
		}

		// Add Kas
		$arr_bb[110] = array(
			'post_id'		=> 0,
			'type_akun'		=> 1,
			'reff_akun'		=> 10,
			'type_jurnal'	=> 10,
			'id_akun'		=> 0,
			'close_date'	=> $date . '-01'
		);

		// Add Modal akhir
		$arr_bb[311] = array(
			'post_id'		=> 0,
			'type_akun'		=> 3,
			'reff_akun'		=> 11,
			'type_jurnal'	=> 10,
			'id_akun'		=> 0,
			'close_date'	=> $date . '-01'
		);

		// Add Utang Usaha
		$arr_bb[210] = array(
			'post_id'		=> 0,
			'type_akun'		=> 2,
			'reff_akun'		=> 10,
			'type_jurnal'	=> 10,
			'id_akun'		=> 0,
			'close_date'	=> $date . '-01'
		);

		// Add Utang Usaha
		$arr_bb[410] = array(
			'post_id'		=> 0,
			'type_akun'		=> 4,
			'reff_akun'		=> 10,
			'type_jurnal'	=> 10,
			'id_akun'		=> 0,
			'close_date'	=> $date . '-01'
		);

		foreach ($arr_bb as $key => $val) {
			$nsd = 0;

			$dt_jurnal = array();
			$kredit = $debit = 0;

			$type_reff = $val['type_akun'] . $val['reff_akun'];
			if(!in_array($type_reff,array(130,210))){
				$jurnal = sobad_jurnal::_get_jurnal_reff($val['type_akun'],$val['reff_akun'],$date);

				$limit = "AND id_akun='0'";
				$ns = sobad_jurnal::_get_bukuBesar_reff($val['type_akun'],$val['reff_akun'],$now,$limit);
				if(isset($ns[0])){
					$jurnal = array_merge($ns,$jurnal);
				}
			}else{
				$limit = $type_reff==130 ? "AND type_jurnal='11' AND debit>'0'" : $limit = "AND type_jurnal='12' AND kredit>'0'";
				$jurnal = sobad_jurnal::_get_bukuBesar_not_type($val['type_akun'],$val['reff_akun'],$date,$limit);
			}

			if($type_reff==410){
				$jual = sobad_jurnal::_get_jurnal_jkm(1,30,$date);
				if(isset($jual[0])){
					$jurnal = array_merge($jual,$jurnal);
				}
			}

			$tanggal = $date;
			foreach ($jurnal as $ky => $vl) {
				$status = true;

				$kky = $vl['type_akun'] . $vl['reff_akun'];
				if(in_array($vl['type_akun'], array(1,5))){
					$status = true;
					if(in_array($kky, array(580))){
						$status = false;
					}
				}else{
					$status = false;
					if(in_array($kky, array(350,450))){
						$status = true;
					}
				}

				if($type_reff==410){
					$status = false;
				}

				if($status){
					$saldo = empty($vl['debit']) ? -1 * $vl['kredit'] : $vl['debit'];

					$kredit = 0;
					$debit += $saldo;
				}else{
					$saldo = empty($vl['kredit']) ? -1 * $vl['debit'] : $vl['kredit'];

					$kredit += $saldo;
					$debit = 0;
				}
			}

			$val['kredit'] = $kredit;
			$val['debit'] = $debit;
			$val['type_report'] = in_array($val['type_jurnal'], array(3,4)) ? 4 : 0;
			$q = self::_insert_dataBukuBesar($val,$val['close_date']);
		}
	}

	public static function _close_saldoNeraca($date=''){
		$ns = array();

		// Neraca Saldo
		$bgk = sobad_jurnal::_gets_bigBook($date);
		foreach ($bgk as $key => $val) {
			$ky = $val['type_akun'] . $val['reff_akun'];

			if(!isset($ns[$ky])){
				$ns[$ky] = $val;
			}else{
				$ns[$ky]['debit'] += $val['debit'];
				$ns[$ky]['kredit'] += $val['kredit'];
			}
		}

		foreach ($ns as $key => $val) {
			$report = 2;
			if(in_array($val['type_akun'], array(1,2))){
				$report = 3;
			}else if(in_array($val['type_akun'], array(4,5))){
				$report = 1;
			}

			unset($val['ID']);

			$val['type_jurnal'] = 15;
			$val['type_report'] = $report;
			$q = self::_insert_dataJurnal($val,$val['close_date']);
		}
	}

	public static function _close_laporan($date=''){
		$ns = array();
		$debit = $kredit = 0;

		// Laba Rugi
		$bgk = sobad_jurnal::_get_neracaSaldo($date,1);
		foreach ($bgk as $key => $val) {
			$kredit += $val['kredit'];
			$debit += $val['debit'];
		}

		$selisih_laba = $kredit - $debit;
		if($selisih_laba<0){
			$kredit = $selisih_laba * -1;
			$debit = 0;
		}else{
			$debit = $selisih_laba;
			$kredit = 0;
		}

		$data = array(
			'post_id'		=> 0,
			'type_akun'		=> 0,
			'reff_akun'		=> 0,
			'type_report'	=> 1,
			'type_jurnal'	=> 16,
			'debit'			=> $debit,
			'kredit'		=> $kredit,
			'close_date'	=> $date . '-01'
		);

		$q = self::_insert_dataJurnal($data,$date . '-01');

		// Perubahan Modal
		$now = strtotime($date);
		$now = strtotime('-1 months',$now);
		$now = date('Y-m',$now);

		$bgk = sobad_jurnal::_get_neracaSaldo($date,2);
		foreach ($bgk as $key => $val) {
			if(empty($val['debit'])){
				$selisih_laba += $val['debit'];
			}else{
				$selisih_laba += $val['kredit'];
			}
		}

		$modal = sobad_jurnal::_get_changeModal($now);
		if(isset($modal[0])){
			if(empty($modal[0]['kredit'])){
					$selisih_laba -= $modal[0]['debit'];
				}else{
					$selisih_laba += $modal[0]['kredit'];
				}
		}

		if($selisih_laba<0){
			$kredit = 0;
			$debit = $selisih_laba * -1;
		}else{
			$debit = 0;
			$kredit = $selisih_laba;
		}

		$data = array(
			'post_id'		=> 0,
			'type_akun'		=> 0,
			'reff_akun'		=> 0,
			'type_report'	=> 2,
			'type_jurnal'	=> 17,
			'debit'			=> $debit,
			'kredit'		=> $kredit,
			'close_date'	=> $date . '-01'
		);

		$q = self::_insert_dataJurnal($data,$date . '-01');

		$big = array(
			'post_id'		=> 0,
			'type_akun'		=> 3,
			'reff_akun'		=> 11,
			'type_jurnal'	=> 10,
			'id_akun'		=> 0,
			'debit'			=> $debit,
			'kredit'		=> $kredit,
			'close_date'	=> $date . '-01'
		);
		$q = self::_insert_dataBukuBesar($big,$date. '-01');

		// Arus Kas
		$kas = sobad_jurnal::_get_bukuBesar_reff(1,10,$date);
		$jkm = sobad_jurnal::gets_jurnal(4,$date);
		$jkk = sobad_jurnal::gets_jurnal(3,$date);

		$kas = isset($kas[0]) ? $kas[0]['kredit'] : 0;
		foreach ($jkm as $key => $val) {
			$kas += $val['kredit'];
			$kas -= $val['debit'];
		}

		foreach ($jkm as $key => $val) {
			$kas -= $val['debit'];
			$kas += $val['kredit'];
		}

		$data = array(
			'post_id'		=> 0,
			'type_akun'		=> 0,
			'reff_akun'		=> 0,
			'type_report'	=> 2,
			'type_jurnal'	=> 19,
			'debit'			=> 0,
			'kredit'		=> $kas,
			'close_date'	=> $date . '-01'
		);

		$q = self::_insert_dataJurnal($data,$date . '-01');

		$big = array(
			'post_id'		=> 0,
			'type_akun'		=> 1,
			'reff_akun'		=> 10,
			'type_jurnal'	=> 10,
			'id_akun'		=> 0,
			'debit'			=> 0,
			'kredit'		=> $kas,
			'close_date'	=> $date . '-01'
		);
		$q = self::_insert_dataBukuBesar($big,$date. '-01');
	}

	// -------------------------------------------------------------------------------------------------
	// Jurnal ------------------------------------------------------------------------------------------
	// -------------------------------------------------------------------------------------------------

	public static function _conv_akun($type=0,$reff=0){
		$args = array(1 => 'asset', 2 => 'liability', 3 => 'equility', 4 => 'pendapatan', 5 => 'beban', 6 => 'lain-lain');

		if(isset($args[$type])){
			$data = self::refferensi_akun($args[$type]);
			foreach ($data as $key => $val) {
				if($val==$reff){
					return $key;
				}
			}

			return $args[$type];
		}

		return 'Undefined';
	}

	public static function _conv_jurnal($type=0){
		$args = array('JU','JJ','JB','JKK','JKM','JS',10 => 'BBU', 11 => 'BPP', 12 => 'BPU');
		return isset($args[$type]) ? $args[$type] : '-';
	}

	// Jurnal Umum
	public static function jurnal_umum($date=''){
		$data = array();
		$jurnal = sobad_jurnal::gets_jurnal(0,$date);

		$date = empty($post_date) ? date('Y-m') : $post_date;
		$date = strtotime($date);
		$date = date('Y m',$date);

		$jurnal = sobad_jurnal::gets_jurnal(0,$date);
		foreach ($jurnal as $key => $val) {
			$days = strtotime($val['close_date']);
			$days = date('d',$days);

			$day = $days==$day ? '' : $days;

			$data[] = array(
				'date'		=> $date,
				'day'		=> $day,
				'akun'		=> self::_conv_akun($val['type_akun'],$val['reff_akun']),
				'reff'		=> $val['type_akun'] . $val['reff_akun'],
				'debit'		=> $val['debit'],
				'kredit'	=> $val['kredit']
			);
		}

		return array('data' => $data);
	}

	// Jurnal Penjualan Kredit
	public static function jurnal_penjualan($date=''){
		$data = array();
		$jurnal = sobad_jurnal::gets_jurnal(1,$date);

		$date = empty($date) ? date('Y-m') : $date;
		$date = strtotime($date);
		$date = date('Y m',$date);

		$day = '01';$total = 0;
		foreach ($jurnal as $key => $val) {
			$days = strtotime($val['close_date']);
			$days = date('d',$days);

			if($day!=$days){
				$day = $days;
			}

			// Data penjualan
			$sell = sobad_post::get_id($val['post_id'],array('ID','title','type','contact','inserted','reff','var'),'','order');
			$sell = $sell[0];

			if($sell['var']=='order'){
				$faktur = '';

				$title = transaksi_retail::_post_title($sell['title'], $sell['meta_note_type'], $sell['inserted']);
				$note = $sell['name_cont'] . '<br><i style="font-size:12px;">' . $title . '</i>';
			}else{
				$quo = sobad_post::get_id($sell['reff'],array('ID','title','company','contact','inserted','_no_faktur'),'','quotation');
				
				if(isset($quo[0])){
					$quo = $quo[0];
					$faktur = $quo['_no_faktur'];

					$comp = empty($quo['name_comp']) ? $quo['name_cont'] : $quo['name_comp'];
				}else{
					$faktur = '';
					$comp = '-';
				}

				$title = invoice_marketing::_post_title($sell['title'], $sell['inserted']);
				$note = $comp . '<br><i style="font-size:12px;">' . $title . '</i>';
			}

			$reff = $val['type_akun'] . $val['reff_akun'] . '.' . $val['id_akun'];
			$data[] = array(
				'date'		=> $date,
				'day'		=> $day,
				'faktur'	=> $faktur,
				'note'		=> $note,
				'reff'		=> $reff,
				'syarat'	=> '',
				'nominal'	=> format_nominal($val['debit'])
			);

			$total += $val['debit'];
			$date = '';
		}

		return array(
			'data'	=> $data,
			'total'	=> format_nominal($total)
		);
	}

	// Jurnal Pembelian Kredit
	public static function jurnal_pembelian($date=''){
		$data = array();
		$jurnal = sobad_jurnal::gets_jurnal(2,$date);

		$date = empty($date) ? date('Y-m') : $date;
		$date = strtotime($date);
		$date = date('Y m',$date);

		$day = '01';
		$tot_purc = $tot_val = $tot_kredit = 0;
		
		foreach ($jurnal as $key => $val) {
			$days = strtotime($val['close_date']);
			$days = date('d',$days);

			if($day!=$days){
				$day = $days;
			}

			// Data pembelian
			$purc = sobad_post::get_id($val['post_id'],array('ID','title','type','company','contact','inserted','reff','var','_no_faktur'),'','purchase');
			$purc = $purc[0];

			$comp = empty($purc['name_comp']) ? $purc['name_cont'] : $purc['name_comp'];
			$title = transaction_purchase::_post_title($purc['title'], $purc['inserted']);
			$note = $comp . '<br><i style="font-size:12px;">' . $title . '</i>';

			$_reff = $val['type_akun'] . $val['reff_akun'];
			$reff = $_reff . '.' . $val['id_akun'];

			$kredit = $val['kredit'];
			$_purc = $_reff == 210 || $_reff == 582 ? $kredit : 0;
			$_val = $_reff != 210 && $_reff != 582 ? $kredit : 0;

			$data[] = array(
				'date'			=> $date,
				'day'			=> $day,
				'faktur'		=> $purc['_no_faktur'],
				'note'			=> $note,
				'reff'			=> $_reff==210 ? $reff : '',
				'syarat'		=> '',
				'purchase'		=> format_nominal($_purc),
				'akun_serba'	=> $_reff!=210 && $_reff!=582 ? self::_conv_akun($val['type_akun'],$val['reff_akun']) : '',
				'reff_serba'	=> $_reff!=210 && $_reff!=582 ? $_reff : '',
				'value_serba'	=> format_nominal($_val),
				'kredit'		=> format_nominal($kredit)
			);

			$tot_purc += $_purc;
			$tot_val += $_val;
			$tot_kredit += $kredit;

			$date = '';
		}

		return array(
			'data'				=> $data,
			'total_purchase'	=> format_nominal($tot_purc),
			'total_value'		=> format_nominal($tot_val),
			'total_kredit'		=> format_nominal($tot_kredit)
		);
	}

	// Jurnal Penjualan Kredit
	public static function jurnal_kas_masuk($date=''){
		$data = array();
		$jurnal = sobad_jurnal::gets_jurnal(4,$date,"AND reff_jurnal='0'");

		$date = empty($date) ? date('Y-m') : $date;
		$date = strtotime($date);
		$date = date('Y m',$date);

		$day = '01';
		$tot_kas = $tot_val = $tot_debit = $tot_pot = $tot_sell = 0;
		
		foreach ($jurnal as $key => $val) {
			$days = strtotime($val['close_date']);
			$days = date('d',$days);

			if($day!=$days){
				$day = $days;
			}

			// Data penjualan
			$paid = sobad_post::get_id($val['post_id'],array('ID','reff'));
			$paid = $paid[0];

			$order = sobad_post::get_id($paid['reff'],array('ID','company','contact','title','inserted','var','type'),'','order');

			if(isset($order[0])){
				$order = $order[0];

				if($order['var']=='invoice'){
					$title = invoice_marketing::_post_title($order['title'], $order['inserted']);
				}else{
					$title = transaksi_retail::_post_title($order['title'], $order['meta_note_type'],$order['inserted']);
				}

				$comp = empty($order['name_comp']) ? $order['name_cont'] : $order['name_comp'];
			}else{
				$comp = sobad_company::get_id($paid['company'],array('name'));
				$comp = isset($comp[0]) ? $comp[0]['name'] : '-';

				$title = '';
			}

			$note = $comp . '<br><i style="font-size:12px;">' . $title . '</i>';

			$_reff = $val['type_akun'] . $val['reff_akun'];
			$reff = $_reff . '.' . $val['id_akun'];

			$_kas = $val['debit'];
			$_sell = $_reff==410 ? $_kas : 0;

			if($_reff==130){
				$debit = $val['kredit'];
				$_kas = $debit;
			}

			// Get referensi penjualan
			$_pot = $_val = 0;
			$_akun = $_serba = $_akun = '';

			$rf_jurnal = sobad_jurnal::get_all(array('type_akun','reff_akun','debit','kredit'),"AND reff_jurnal='".$val['ID']."'");
			foreach ($rf_jurnal as $ky => $vl) {
				$_rf_akun = $vl['type_akun'] . $vl['reff_akun'];
				if($_rf_akun==450){
					$_pot = $vl['debit'];
					$_kas -= $_pot;
				}else if($_rf_akun==413){
					$_akun = self::_conv_akun(4,13);
					$_serba = 413;
					$_val = $vl['kredit'];

					$_kas += $_val;
				}
			}

			if($_reff==270){
				$_akun = self::_conv_akun(2,70);
				$_val = $_kas;
				$_serba = 270;
			}

			$data[] = array(
				'date'			=> $date,
				'day'			=> $day,
				'note'			=> $note,
				'reff'			=> $_reff==410 ? $reff : '',
				'akun'			=> format_nominal($_kas),
				'pot_sell'		=> format_nominal($_pot),
				'sell'			=> format_nominal($_sell),
				'akun_serba'	=> $_akun,
				'reff_serba'	=> $_serba,
				'value_serba'	=> format_nominal($_val),
				'debit'			=> format_nominal($debit)
			);

			$tot_debit += $debit;
			$tot_kas += $_kas;
			$tot_pot += $_pot;
			$tot_sell += $_sell;
			$tot_val += $_val;

			$date = '';
		}

		return array(
			'data'				=> $data,
			'total_kas'			=> format_nominal($tot_kas),
			'total_pot_sell'	=> format_nominal($tot_pot),
			'total_debit'		=> format_nominal($tot_debit),
			'total_sell'		=> format_nominal($tot_sell),
			'total_value'		=> format_nominal($tot_val)
		);
	}

	// Jurnal Kas Keluar
	public static function jurnal_kas_keluar($date=''){
		$data = array();
		$jurnal = sobad_jurnal::gets_jurnal(3,$date,"AND reff_jurnal='0'");

		$date = empty($date) ? date('Y-m') : $date;
		$date = strtotime($date);
		$date = date('Y m',$date);

		$day = '01';
		$tot_kas = $tot_val = $tot_kredit = $tot_pot = $tot_purc = 0;
		
		foreach ($jurnal as $key => $val) {
			$days = strtotime($val['close_date']);
			$days = date('d',$days);

			if($day!=$days){
				$day = $days;
			}

			// Data pengadaan
			$paid = sobad_post::get_id($val['post_id'],array('ID','company','reff'));
			$paid = $paid[0];

			$order = sobad_post::get_id($paid['reff'],array('ID','company','contact','title','inserted','var','type'),'','purchase');

			if(isset($order[0])){
				$order = $order[0];

				$title = transaction_purchase::_post_title($order['title'], $order['inserted']);
				$comp = empty($order['name_comp']) ? $order['name_cont'] : $order['name_comp'];
			}else{
				$comp = sobad_company::get_id($paid['company'],array('name'));
				$comp = isset($comp[0]) ? $comp[0]['name'] : '-';

				$title = '';
			}

			$note = $comp . '<br><i style="font-size:12px;">' . $title . '</i>';

			$_reff = $val['type_akun'] . $val['reff_akun'];
			$reff = $_reff . '.' . $val['id_akun'];

			$_kas = $val['debit'];
			$_purc = $_reff==140 || $_reff==582 ? $_kas : 0;
			$kredit = $_reff==240 ? $_kas : 0;

			// Get referensi penjualan
			$_pot = $_val = 0;
			$_akun = $_serba = '';

			$rf_jurnal = sobad_jurnal::get_all(array('type_akun','reff_akun','debit','kredit'),"AND reff_jurnal='".$val['ID']."'");
			foreach ($rf_jurnal as $ky => $vl) {
				$_rf_akun = $vl['type_akun'] . $vl['reff_akun'];
				if($_rf_akun==580){
					$_pot = $vl['kredit'];
					$_kas -= $_pot;
				}else if($_rf_akun==527){
					$_akun = self::_conv_akun(5,27);
					$_serba = 527;
					$_val = $vl['debit'];

					$_kas += $_val;
				}
			}

			if(!in_array($_reff, array(140,582,240))){
				$_akun = self::_conv_akun($val['type_akun'],$val['reff_akun']);
				$_val = $val['debit'];
				$_serba = $_reff;
			}

			$data[] = array(
				'date'			=> $date,
				'day'			=> $day,
				'note'			=> $note,
				'reff'			=> $_reff==410 ? $reff : '',
				'akun'			=> format_nominal($_kas),
				'pot_purchase'	=> format_nominal($_pot),
				'purchase'		=> format_nominal($_purc),
				'akun_serba'	=> $_akun,
				'reff_serba'	=> $_serba,
				'value_serba'	=> format_nominal($_val),
				'kredit'		=> format_nominal($kredit)
			);

			$tot_kredit += $kredit;
			$tot_kas += $_kas;
			$tot_pot += $_pot;
			$tot_purc += $_purc;
			$tot_val += $_val;

			$date = '';
		}

		return array(
			'data'				=> $data,
			'total_kas'			=> format_nominal($tot_kas),
			'total_pot_purchase'=> format_nominal($tot_pot),
			'total_kredit'		=> format_nominal($tot_kredit),
			'total_purchase'	=> format_nominal($tot_purc),
			'total_value'		=> format_nominal($tot_val)
		);
	}

	// Jurnal Kas Penyesuaian
	public static function jurnal_penyesuaian($date=''){
		
	}

	// Jurnal Kas Arus Kas
	public static function jurnal_arus_kas($date=''){
		
	}


	// -------------------------------------------------------------------------------------------------
	// Buku Besar --------------------------------------------------------------------------------------
	// -------------------------------------------------------------------------------------------------

	// Buku Besar Utama
	public static function bigBook_utama($date=''){
		$data = array();

		$books = sobad_jurnal::_get_bukuBesar(10,$date);
		foreach ($books as $key => $val) {
			$day = '01';
			$data[$key] = array();

			$dt_jurnal = array();
			$kredit = $debit = 0;

			$type_reff = $val['type_akun'] . $val['reff_akun'];
			if(!in_array($type_reff,array(130,210))){
				$jurnal = sobad_jurnal::_get_jurnal_reff($val['type_akun'],$val['reff_akun'],$date);

				// get buku besar lalu
				$now = strtotime($date);
				$now = strtotime('-1 months',$now);
				$now = date('Y-m',$now);

				$limit = "AND id_akun='0'";
				$ns = sobad_jurnal::_get_bukuBesar_reff($val['type_akun'],$val['reff_akun'],$now,$limit);
				if(isset($ns[0])){
					$jurnal = array_merge($ns,$jurnal);
				}
			}else{
				$limit = $type_reff==130 ? "AND type_jurnal='11' AND debit>'0'" : $limit = "AND type_jurnal='12' AND kredit>'0'";
				$jurnal = sobad_jurnal::_get_bukuBesar_not_type($val['type_akun'],$val['reff_akun'],$date,$limit);
			}

			if($type_reff==410){
				$jual = sobad_jurnal::_get_jurnal_jkm(1,30,$date);
				if(isset($jual[0])){
					$jurnal = array_merge($jual,$jurnal);
				}
			}

			$tanggal = $date;
			foreach ($jurnal as $ky => $vl) {
				$days = strtotime($vl['close_date']);
				$days = date('d',$days);

				if($day!=$days){
					$day = $days;
				}

				$status = true;
				$kky = $vl['type_akun'] . $vl['reff_akun'];
				if(in_array($vl['type_akun'], array(1,5))){
					$status = true;
					if(in_array($kky, array(580))){
						$status = false;
					}
				}else{
					$status = false;
					if(in_array($kky, array(350,450))){
						$status = true;
					}
				}

				if($type_reff==410){
					$status = false;
				}

				if($status){
					$saldo = empty($vl['debit']) ? -1 * $vl['kredit'] : $vl['debit'];

					$kredit = 0;
					$debit += $saldo;
				}else{
					$saldo = empty($vl['kredit']) ? -1 * $vl['debit'] : $vl['kredit'];

					$kredit += $saldo;
					$debit = 0;
				}

				$reff = $vl['type_jurnal']==10 ? 'NS' : self::_conv_jurnal($vl['type_jurnal']);

				// get Jurnal
				$dt_jurnal[] = array(
					'date'			=> $tanggal,
					'day'			=> $day,
					'note'			=> 'Posting',
					'reff'			=> $reff,
					'debit'			=> format_nominal($vl['debit']),
					'kredit'		=> format_nominal($vl['kredit']),
					'saldo_debit'	=> empty($debit) ? '-' : format_nominal($debit),
					'saldo_kredit'	=> empty($kredit) ? '-' : format_nominal($kredit)
				);

				$tanggal = '';
			}

			$data[$key]['nama_akun'] = self::_conv_akun($val['type_akun'],$val['reff_akun']);
			$data[$key]['reff_akun'] = $val['type_akun'].$val['reff_akun'];
			$data[$key]['data'] = $dt_jurnal;
		}

		return array('data' => $data);
	}

	// Buku Pembantu Piutang
	public static function bigBook_piutang($date=''){
		$data = array();

		$books = sobad_jurnal::_get_bukuBesar(11,$date);
		foreach ($books as $key => $val) {
			$day = '01';
			$data[$key] = array();

			$kredit = $debit = 0;

			$dt_jurnal = array();
			$limit = "AND id_akun='".$val['id_akun']."'";
			$jurnal = sobad_jurnal::_get_piutang_reff($val['type_akun'],$val['reff_akun'],$val['id_akun'],$date);

			// get buku besar lalu
			$now = strtotime($date);
			$now = strtotime('-1 months',$now);
			$now = date('Y-m',$now);

			$ns = sobad_jurnal::_get_bukuBesar_reff($val['type_akun'],$val['reff_akun'],$now,$limit);
			if(isset($ns[0])){
				$jurnal = array_merge($ns,$jurnal);
			}

			$tanggal = $date;
			foreach ($jurnal as $ky => $vl) {
				$days = strtotime($vl['close_date']);
				$days = date('d',$days);

				if($day!=$days){
					$day = $days;
				}

				$note = self::_conv_akun($vl['type_akun'],$vl['reff_akun']);

				$saldo = $vl['debit']==0 ? -1 * $vl['kredit'] : $vl['debit'];

				$kredit = 0;
				$debit += $saldo;

				$reff = $vl['type_jurnal']==11 ? 'NS' : self::_conv_jurnal($vl['type_jurnal']);

				// get Jurnal
				$dt_jurnal[] = array(
					'date'			=> $tanggal,
					'day'			=> $day,
					'note'			=> $note,
					'reff'			=> $reff,
					'debit'			=> format_nominal($vl['debit']),
					'kredit'		=> format_nominal($vl['kredit']),
					'saldo_debit'	=> format_nominal($debit),
					'saldo_kredit'	=> '-'
				);

				$tanggal = '';
			}

			$comp = sobad_company::get_id($val['id_akun'],array('name'));
			$comp = isset($comp[0]) ? $comp[0]['name'] : '-';

			$data[$key]['nama_akun'] = $comp;
			$data[$key]['reff_akun'] = $val['type_akun'].$val['reff_akun'].'.'.$val['id_akun'];
			$data[$key]['data'] = $dt_jurnal;
		}

		return array('data' => $data);
	}

	// Buku Pembantu Utang
	public static function bigBook_utang($date=''){
		$data = array();

		$books = sobad_jurnal::_get_bukuBesar(12,$date);
		foreach ($books as $key => $val) {
			$day = '01';
			$data[$key] = array();

			$kredit = $debit = 0;

			$dt_jurnal = array();
			$limit = "AND id_akun='".$val['id_akun']."'";
			$jurnal = sobad_jurnal::_get_utang_reff($val['id_akun'],$date);

			// get buku besar lalu
			$now = strtotime($date);
			$now = strtotime('-1 months',$now);
			$now = date('Y-m',$now);

			$ns = sobad_jurnal::_get_bukuBesar_reff($val['type_akun'],$val['reff_akun'],$now,$limit);
			if(isset($ns[0])){
				$jurnal = array_merge($ns,$jurnal);
			}

			$tanggal = $date;
			foreach ($jurnal as $ky => $vl) {
				$days = strtotime($vl['close_date']);
				$days = date('d',$days);

				if($day!=$days){
					$day = $days;
				}

				$note = self::_conv_akun($vl['type_akun'],$vl['reff_akun']);

				if($vl['kredit']==0){
					$kredit -= intval($vl['debit']);
				}else{
					$kredit += intval($vl['kredit']);
				}

				$debit = 0;

				$reff = $vl['type_jurnal']==12 ? 'NS' : self::_conv_jurnal($vl['type_jurnal']);

				// get Jurnal
				$dt_jurnal[] = array(
					'date'			=> $tanggal,
					'day'			=> $day,
					'note'			=> $note,
					'reff'			=> $reff,
					'debit'			=> format_nominal($vl['debit']),
					'kredit'		=> format_nominal($vl['kredit']),
					'saldo_debit'	=> '-',
					'saldo_kredit'	=> format_nominal($kredit)
				);

				$tanggal = '';
			}

			$comp = sobad_company::get_id($val['id_akun'],array('name'));
			$comp = isset($comp[0]) ? $comp[0]['name'] : '-';

			$data[$key]['nama_akun'] = $comp;
			$data[$key]['reff_akun'] = $val['type_akun'].$val['reff_akun'].'.'.$val['id_akun'];
			$data[$key]['data'] = $dt_jurnal;
		}

		return array('data' => $data);
	}

	// -------------------------------------------------------------------------------------------------
	// Lembar Kerja 10 Kolom ---------------------------------------------------------------------------
	// -------------------------------------------------------------------------------------------------	

	public static function neraca_lajur($date=''){
		$data = array();

		$tot_debit = $tot_kredit = $tot_js_debit = $tot_js_kredit = 0;
		$tot_nsd_debit = $tot_nsd_kredit = $tot_lr_debit = $tot_lr_kredit = $tot_nr_debit = $tot_nr_kredit = 0;

		$neraca = sobad_jurnal::_get_neracaSaldo($date);
		foreach ($neraca as $key => $val) {
			$nsd_debit = $val['debit'] + 0;
			$nsd_kredit = $val['kredit'] + 0;

			if($val['type_report']==1){
				$lr_debit = $nsd_debit;
				$lr_kredit = $nsd_kredit;
				$nr_debit = 0;
				$nr_kredit = 0;
			}else{
				$nr_debit = $nsd_debit;
				$nr_kredit = $nsd_kredit;
				$lr_debit = 0;
				$lr_kredit = 0;
			}

			$data[] = array(
				'reff'					=> $val['type_akun'] . $val['reff_akun'],
				'akun'					=> self::_conv_akun($val['type_akun'],$val['reff_akun']),
				'd_neraca_saldo'		=> format_nominal($val['debit']),
				'k_neraca_saldo'		=> format_nominal($val['kredit']),
				'd_jurnal_penyesuaian'	=> 0,
				'k_jurnal_penyesuaian'	=> 0,
				'd_nsp'					=> format_nominal($nsd_debit),
				'k_nsp'					=> format_nominal($nsd_kredit),
				'd_laba_rugi'			=> format_nominal($lr_debit),
				'k_laba_rugi'			=> format_nominal($lr_kredit),
				'd_neraca'				=> format_nominal($nr_debit),
				'k_neraca'				=> format_nominal($nr_kredit)
			);

			$tot_debit += $val['debit'];
			$tot_kredit += $val['kredit'];
			$tot_js_debit += 0;
			$tot_js_kredit += 0;
			$tot_nsd_debit += $nsd_debit;
			$tot_nsd_kredit += $nsd_kredit;
			$tot_lr_debit += $lr_debit;
			$tot_lr_kredit += $lr_kredit;
			$tot_nr_debit += $nr_debit;
			$tot_nr_kredit += $nr_kredit;

		}

		$selisih_laba = $tot_lr_debit - $tot_lr_kredit;
		if($selisih_laba<0){
			$selisih_d_laba = $selisih_laba * -1;
			$selisih_k_laba = 0;

			$total_laba = $tot_lr_kredit;
		}else{
			$selisih_k_laba = $selisih_laba;
			$selisih_d_laba = 0;

			$total_laba = $tot_lr_debit;
		}

		$selisih_neraca = $tot_nr_debit - $tot_nr_kredit;
		if($selisih_neraca<0){
			$selisih_d_neraca = $selisih_neraca * -1;
			$selisih_k_neraca = 0;

			$total_neraca = $tot_nr_kredit;
		}else{
			$selisih_k_neraca = $selisih_neraca;
			$selisih_d_neraca = 0;

			$total_neraca = $tot_nr_debit;
		}

		$work = array(
			'data'							=> $data,
			'total_d_neraca_saldo'			=> format_nominal($tot_debit),
			'total_k_neraca_saldo'			=> format_nominal($tot_kredit),
			'total_d_jurnal_penyesuaian'	=> format_nominal($tot_js_debit),
			'total_k_jurnal_penyesuaian'	=> format_nominal($tot_js_kredit),
			'total_d_nsp'					=> format_nominal($tot_nsd_debit),
			'total_k_nsp'					=> format_nominal($tot_nsd_kredit),
			'total_d_laba_rugi'				=> format_nominal($tot_lr_debit),
			'total_k_laba_rugi'				=> format_nominal($tot_lr_kredit),
			'total_d_neraca'				=> format_nominal($tot_nr_debit),
			'total_k_neraca'				=> format_nominal($tot_nr_kredit),
			'selisih_d_laba_rugi'			=> format_nominal($selisih_d_laba),
			'selisih_k_laba_rugi'			=> format_nominal($selisih_k_laba),
			'selisih_d_neraca'				=> format_nominal($selisih_d_neraca),
			'selisih_k_neraca'				=> format_nominal($selisih_k_neraca),
			'total_laba_rugi'				=> format_nominal($total_laba),
			'total_neraca'					=> format_nominal($total_neraca)
		);

		return $work;
	}

	// -------------------------------------------------------------------------------------------------
	// Laporan Perusahaan ------------------------------------------------------------------------------
	// -------------------------------------------------------------------------------------------------

	public static function laporan_laba_rugi($date=''){
		$data = array();

		$tgl = strtotime($date);
		$y = date('Y',$tgl); $m = conv_month_id(date('m',$tgl));
		$periode = $m . ' ' . $y;

		$data['date'] = $periode;
		$data['penjualan'] = array();
		$data['pot_penjualan'] = array();
		$data['pendapatan'] = array();

		$data['pembelian'] = array();
		$data['pot_pembelian'] = array();
		$data['beban'] = array();

		$data['barang_dagang_awal'] = $dagang_awal = 0;
		$data['barang_dagang_akhir'] = $dagang_akhir = 0;

		$sell_kredit = $sell_debit = $purc_kredit = $purc_debit = 0;
		$sell_bersih = $purc_bersih = $dapat = $beban = 0;

		$neraca = sobad_jurnal::_get_neracaSaldo($date,1);
		foreach ($neraca as $key => $val) {
			$ky = $val['type_akun'] . $val['reff_akun'];
			$akun = self::_conv_akun($val['type_akun'], $val['reff_akun']);

		// Penjualan	
			if(in_array($ky, array(410,413))){
				$sell_kredit += $val['kredit'];
				$sell_bersih += $val['kredit'];
				
				$data['penjualan'][$akun] = format_nominal($val['kredit']);
			}else if(in_array($ky, array(411,412,450,414))){
				$sell_debit += $val['debit'];
				$sell_bersih -= $val['debit'];

				$data['pot_penjualan'][$akun] = format_nominal($val['debit']);
			}else{
				if($val['type_akun']==4){
					$dapat += $val['kredit'];

					$data['pendapatan'][$akun] = format_nominal($val['kredit']);
				}
			}

		// Pembelian	
			if(in_array($ky, array(582,527))){
				$purc_debit += $val['debit'];
				$purc_bersih += $val['debit'];

				$data['pembelian'][$akun] = format_nominal($val['debit']);
			}else if(in_array($ky, array(580,583,581))){
				$purc_kredit += $val['kredit'];
				$purc_bersih -= $val['kredit'];

				$data['retur_pembelian'][$akun] = format_nominal($val['kredit']);
			}else{
				if($val['type_akun']==5){
					$beban += $val['debit'];

					$data['beban'][$akun] = format_nominal($val['debit']);
				}
			}
		}

		$barang_jual = ($dagang_awal + $purc_bersih);
		$hpp = ($barang_jual - $dagang_akhir);
		$laba_kotor = $sell_bersih - $hpp;
		$laba_bersih = $laba_kotor + $dapat - $beban;

		$data['total_penjualan'] = format_nominal($sell_kredit);
		$data['total_pot_penjualan'] = format_nominal($sell_debit);

		$data['total_pembelian'] = format_nominal($purc_debit);
		$data['total_pot_pembelian'] = format_nominal($purc_kredit);

		$data['penjualan_bersih'] = format_nominal($sell_bersih);
		$data['pembelian_bersih'] = format_nominal($purc_bersih);
		$data['barang_jual'] = format_nominal($barang_jual);
		$data['HPP'] = format_nominal($hpp);
		$data['laba_kotor'] = format_nominal($laba_kotor);
		$data['laba_bersih'] = format_nominal($laba_bersih);

		return $data;
	}

	public static function laporan_modal($date=''){
		$data = $_kredit = $_debit = array();
		$_jk = $_jd = 0;

		$tgl = strtotime($date);
		$y = date('Y',$tgl); $m = conv_month_id(date('m',$tgl));
		$periode = $m . ' ' . $y;

		$modal = sobad_jurnal::_get_neracaSaldo($date,2);
		foreach ($modal as $key => $val) {
			$_debit = array();
			$_kredit = array();

			if(!empty($val['kredit'])){
				$_jk += $val['kredit'];
				$_kredit[] = array(
					'akun'		=> self::_conv_akun($val['type_akun'], $val['reff_akun']),
					'nominal'	=> format_nominal($val['kredit'])
				);

			}else{
				$_jd += $val['debit'];
				$_debit[] = array(
					'akun'		=> self::_conv_akun($val['type_akun'], $val['reff_akun']),
					'nominal'	=> format_nominal($val['debit'])
				);

			}
		}

		$total = $_jk - $_jd;
		$data = array(
			'date'			=> $periode,
			'kredit'		=> $_kredit,
			'total_kredit'	=> format_nominal($_jk),
			'debit'			=> $_debit,
			'total_debit'	=> format_nominal($_jd),
			'modal_akhir'	=> format_nominal($total)
		);

		return $data;
	}

	public static function laporan_neraca($date=''){
		$data = array();
		$activa = $pasiva = array();
		$t_activa = $t_pasiva = 0;

		$tgl = strtotime($date);
		$y = date('Y',$tgl); $m = conv_month_id(date('m',$tgl));
		$periode = $m . ' ' . $y;

		$neraca = sobad_jurnal::_get_neracaSaldo($date,3);
		foreach ($neraca as $key => $val) {
			if($val['type_akun']==1){
				$nominal = empty($val['debit']) ? -1 * $val['kredit'] : $val['debit'];

				$activa[] = array(
					'reff'		=> $val['type_akun'] . $val['reff_akun'],
					'akun'		=> self::_conv_akun($val['type_akun'], $val['reff_akun']),
					'nominal'	=> format_nominal($nominal)
				);

				$t_activa += $nominal;
			}else{
				$nominal = empty($val['kredit']) ? -1 * $val['debit'] : $val['kredit'];

				$pasiva[] = array(
					'reff'		=> $val['type_akun'] . $val['reff_akun'],
					'akun'		=> self::_conv_akun($val['type_akun'], $val['reff_akun']),
					'nominal'	=> format_nominal($nominal)
				);

				$t_pasiva += $nominal;
			}
		}

		$data = array(
			'date'			=> $periode,
			'activa'		=> $activa,
			'total_activa'	=> format_nominal($t_activa),
			'pasiva'		=> $pasiva,
			'total_pasiva'	=> format_nominal($t_pasiva)
		);

		return $data;
	}

	public static function laporan_arus_kas($date=''){
		$data = array();
		
		$tgl = strtotime($date);
		$y = date('Y',$tgl); $m = conv_month_id(date('m',$tgl));
		$periode = $m . ' ' . $y;

		$transaksi = $investasi = $dana = array();

		// get transaksi JKM dan JKK

		$data[0] = array(
			'title'		=> 'Arus Kas dari Aktivitas Operasi',
			'data'		=> $transaksi
		);

		$data[1] = array(
			'title'		=> 'Arus Kas dari Aktivitas Investasi',
			'data'		=> $investasi
		);

		$data[2] = array(
			'title'		=> 'Arus Kas dari Aktivitas Pendanaan',
			'data'		=> $dana
		);

		return array(
			'date'		=> $periode,
			'data'		=> $data
		);
	}
}