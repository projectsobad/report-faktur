<?php

abstract class custom_script{
	public static function _custom_script($args=array()){
		$script = array();

		foreach ($args as $key => $val) {
			$func = isset($val['func'])?$val['func']:'';
			$data = isset($val['data'])?$val['data']:array();

			if(is_callable(array(new sasi_layout,$func))){
				$script[] = self::$func($data);
			}
		}
		?>
			<script>
				jQuery(document).ready(function() {
					<?php echo implode(' ', $script) ;?>
				});
			</script>
		<?php
	}

	public static function _init_login($data=''){
		$data = !empty($args['data'])?$data:'login_system';
		return 'Login.init("'.$data.'");';
	}
}