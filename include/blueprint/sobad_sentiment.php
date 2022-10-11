<?php

class sobad_sentiment extends _class{
	
	public static $table = ''. base .'sentiment';

	public static function blueprint(){
		$args = array(
			'type'	=> 'sentiment',
			'table'	=> self::$table,
			'detail'	=> array(
				'id_integration'	=> array(
					'key'				=> 'ID',
					'table'				=> ''. base .'integration',
					'column'			=> array('name')
				),
			),
		);

		return $args;
	}

	public static function analytic($name=''){
		if(empty($name))return false;

		$analisis = array();
		$names = explode(' ', strtolower($name));

		$sample = self::get_all(array('name','value'));
		$jml = count($sample); // Jumlah data

		$analisis['variable'] = array(
			'laplace'	=> 1,
			'total'		=> $jml,
		);

		// Get data nama
		foreach ($names as $key => $val) {
			$analisis['variable'][$val.'_0'] = 0;
			$analisis['variable'][$val.'_1'] = 0;
		}

		// Get variabel sample
		foreach ($sample as $key => $val) {
			$nilai = $val['value'];
			if(!isset($analisis['variable']['N_'.$nilai])){
				$analisis['variable']['N_'.$nilai] = 0;
				$analisis['variable']['K_'.$nilai] = 0;
			}

			$kata = explode(' ', strtolower($val['name']));
			$analisis['variable']['N_'.$nilai] += 1;
			$analisis['variable']['K_'.$nilai] += count($kata);

			foreach ($names as $ky => $vl) {
				if(in_array($vl, $kata)){
					$analisis['variable'][$vl.'_'.$nilai] += 1;
				}
			}
		}

		// Set Value
		// Calculate
		$analisis['value'] = array();
		$analisis['value']['P_0'] = round($analisis['variable']['N_0'] / $analisis['variable']['total'],3);
		$analisis['value']['P_1'] = round($analisis['variable']['N_1'] / $analisis['variable']['total'],3);

		$val0 = $analisis['value']['P_0'];
		$val1 = $analisis['value']['P_1'];

		foreach ($names as $key => $val) {
			$laplace = $analisis['variable']['laplace'];
			for($i=0;$i<=1;$i++){

				$nName = $analisis['variable'][$val.'_'.$i];
				$nData = $analisis['variable']['N_'.$i];
				$nKata = $analisis['variable']['K_'.$i];

				$analisis['value'][$val.'_'.$i] = round(($nName + $laplace) / ($nData + $nKata),3);
				if($i==0){
					$val0 *= $analisis['value'][$val.'_'.$i];
				}else{
					$val1 *= $analisis['value'][$val.'_'.$i];
				}
			}
		}

		// Calculate
		$analisis['calculate']['val_0'] = round($val0,6);
		$analisis['calculate']['val_1'] = round($val1,6);

		// Perbandingan
		if($analisis['calculate']['val_1']>$analisis['calculate']['val_0']){
			$analisis['hasil'] = 1;
		}else{
			$analisis['hasil'] = 0;
		}

		return $analisis;
	}
}