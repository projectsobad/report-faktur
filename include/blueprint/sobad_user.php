<?php

class kmi_user
{
	private static $url = 'https://s.soloabadi.com/system-absen/include/curl.php';

	private static function send_curl($args = array())
	{
		$url = self::$url;

		$data = sobad_curl::get_data(self::$url, $args);
		$data = json_decode($data, true);

		//	if($data['status']=='error'){
		//		$url = 'http://192.168.1.2:8080/system-absen/include/curl.php';

		//		$data = sobad_curl::get_data($url,$args);
		//		$data = json_decode($data,true);

		if ($data['status'] == 'error') {
			die(_error::_alert_db($data['msg']));
		}
		//	}

		return $data['msg'];
	}

	public static function get_id($id, $args = array(), $limit = '', $type = '')
	{
		$data = array(
			'object'	=> 'sobad_user',
			'func'		=> 'get_id',
			'data'		=> array($id, $args, $limit, $type)
		);

		return self::send_curl($data);
	}

	public static function get_all($args = array(), $limit = '', $type = '')
	{
		$data = array(
			'object'	=> 'sobad_user',
			'func'		=> 'get_all',
			'data'		=> array($args, $limit, $type)
		);

		return self::send_curl($data);
	}

	public static function get_count($limit = '1=1 ', $args = array(), $type = '')
	{
		$data = array(
			'object'	=> 'sobad_user',
			'func'		=> 'count',
			'data'		=> array($limit, $args, $type)
		);

		return self::send_curl($data);
	}

	public static function check_login($user = '', $pass = '')
	{
		$data = array(
			'object'	=> 'sobad_user',
			'func'		=> 'check_login',
			'data'		=> array($user, $pass)
		);

		$data = self::send_curl($data);
		$check = array_filter($data);
		if (empty($check)) {
			return $data;
		}

		//Check module -> departement
		$return = array();
		$module = sobad_module::get_all(array('meta_name', 'detail'), "AND detail!=''");
		foreach ($module as $key => $val) {
			$detail = unserialize($val['detail']);
			$detail = $detail['access'];

			if (in_array($data[0]['ID'], $detail)) {
				$return = $data;
				$return[0]['dept'] = $val['meta_name'];

				return $return;
				break;
			}
		}

		die(_error::_alert_db('Anda tidak punya Akses !!!'));
	}

	public static function get_sales($args = array(), $limit = '')
	{
		$where = "AND divisi='8' $limit";
		return self::get_all($args, $where);
	}
}
