<?php
require 'Retail/include.php';

class dash_retail{
	private function head_title(){
		$args = array(
			'title'	=> 'Dashboard <small>reports & statistics</small>',
			'link'	=> array(
				0	=> array(
					'func'	=> 'dash_marketing',
					'label'	=> 'dashboard'
				)
			),
			'date'	=> false
		);
		
		return $args;
	}

	public function _sidemenu(){
		$label = array();
		$data = array();
		
		$data[] = array(
			'style'		=> array(),
			'script'	=> array(),
			'func'		=> 'sobad_dashboard',
			'data'		=> self::_data()
		);
		
		$title = self::head_title();
		
		ob_start();
		theme_layout('_head_content',$title);
		theme_layout('_panel',$data);
		return ob_get_clean();
	}

	private function _data(){
		$dash[] = array(
			'func'	=> '_block_info',
			'data'	=> array(
				'icon'		=> '',
				'color'		=> 'red-intense',
				'qty'		=> '',
				'desc'		=> '',
				'func'		=> ''
			)
		);
		
		return $dash;
	}
}