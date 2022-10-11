<?php

class item_retail extends _page{
	protected static $object = 'item_retail';

	protected static $table = 'sobad_item';

	// ----------------------------------------------------------
	// Layout ---------------------------------------------------
	// ----------------------------------------------------------

	protected static function _array(){
		$args = array(
			'ID',
			'name',
			'product_code',
			'category',
			'var',
			'price',
			'stock',
			'picture',
			'_note',
			'_warning_stock',
			'_detail'
		);

		return $args;
	}

	protected static function table(){
		$data = array();
		$args = self::_array();

		$start = intval(self::$page);
		$nLimit = intval(self::$limit);
		
		$_type = str_replace('item_', '', self::$type);
		$kata = '';$where = "AND `". base ."item`.type='61' AND `". base ."item`.var='$_type'";
		if(self::$search){
			$src = self::like_search($args,$where);
			$cari = $src[0];
			$where = $src[0];
			$kata = $src[1];
		}else{
			$cari=$where;
		}
		
		$limit = 'LIMIT '.intval(($start - 1) * $nLimit).','.$nLimit;
		$where .= $limit;

		$_type = strtolower(self::_conv_type($_type));
		$object = self::$table;
		$args = $object::get_all($args,$where,$_type);
		$sum_data = $object::count("1=1 ".$cari);

		$data['data'] = array('data' => $kata, 'type' => self::$type);
		$data['search'] = array('Semua','nama','SKU');
		$data['class'] = '';
		$data['table'] = array();
		$data['page'] = array(
			'func'	=> '_pagination',
			'data'	=> array(
				'start'		=> $start,
				'qty'		=> $sum_data,
				'limit'		=> $nLimit,
				'type'		=> self::$type
			)
		);

		$no = ($start-1) * $nLimit;
		foreach($args as $key => $val){
			$no += 1;
			$picture = $object::get_image($val['picture']);

			$edit = array(
				'ID'	=> 'edit_'.$val['ID'],
				'func'	=> '_edit',
				'color'	=> 'blue',
				'icon'	=> 'fa fa-edit',
				'label'	=> 'edit',
				'type'	=> self::$type
			);
			
			$data['table'][$key]['tr'] = array('');
			$data['table'][$key]['td'] = array(
				'Image'	=> array(
					'left',
					'10%',
					'<img src="asset/img/upload/'.$picture[0].'" style="width:100%;height:auto;">',
					true
				),
				'Name'		=> array(
					'left',
					'auto',
					$val['name'],
					true
				),
				'Kategori'		=> array(
					'left',
					'10%',
					$val['meta_value_cate'],
					true
				),
				'SKU'		=> array(
					'left',
					'10%',
					$val['product_code'],
					true
				),
				'Stock'		=> array(
					'left',
					'10%',
					$val['stock'],
					true
				),
				'Warning'		=> array(
					'left',
					'10%',
					$val['_warning_stock'],
					true
				),
				'Price'		=> array(
					'left',
					'10%',
					'Rp '.format_nominal($val['price']),
					true
				),
				'Edit'			=> array(
					'center',
					'10%',
					edit_button($edit),
					false
				)
				
			);
		}
		
		return $data;
	}

	private static function head_title(){
		$args = array(
			'title'	=> 'Barang <small>data barang</small>',
			'link'	=> array(
				0	=> array(
					'func'	=> self::$object,
					'label'	=> 'barang'
				)
			),
			'date'	=> false,
			'modal'	=> 3
		); 
		
		return $args;
	}

	protected static function get_box(){
		$data = self::table();
		
		$type = str_replace("item_", "", self::$type);
		$label = self::_conv_type($type);
		$box = array(
			'label'		=> 'Data '.$label.' Malika',
			'tool'		=> '',
			'action'	=> '',
			'func'		=> 'sobad_table',
			'data'		=> $data
		);

		return $box;
	}

	protected static function layout(){
		self::$type = 'item_4';
		$box = self::get_box();

		$object = self::$table;
		$tabs = array();
		for($i=4;$i>=4;$i--){
			$tabs[$i-1] = array(
				'key'	=> 'item_'.$i,
				'label'	=> self::_conv_type($i),
				'qty'	=> $object::count("type='61' AND var='$i'")
			);
		}
		
		$tabs = array(
			'tab'	=> $tabs,
			'func'	=> '_portlet',
			'data'	=> $box
		);

		$opt = array(
			'title'		=> self::head_title(),
			'style'		=> array(),
			'script'	=> array('')
		);
		
		return tabs_admin($opt,$tabs);
	}

	public static function _conv_type($id=0){
		$args = array(
			1 => 'Part','Std. Part','Assembly','Product'
		);

		return isset($args[$id])?$args[$id]:'Undefined';
	}

	// ----------------------------------------------------------
	// Form data category -----------------------------------
	// ----------------------------------------------------------

	protected static function edit_form($vals=array()){
		$check = array_filter($vals);
		if(empty($check)){
			return '';
		}
		$picture = json_decode($vals['picture'],true);
		$picture = $picture['image'];
		
		$type = self::$type;
		$label = self::_conv_type($type);
		$args = array(
			'title'		=> 'Edit data '.$label,
			'button'	=> '_btn_modal_save',
			'status'	=> array(
				'link'		=> '_update_db',
				'load'		=> 'sobad_portlet',
				'type'		=> $type
			)
		);
	
		return self::_data_form($args,$vals);
	}

	private static function _data_form($args=array(),$vals=array()){
		$check = array_filter($args);
		if(empty($check)){
			return '';
		}

		$data = array(
			'cols' => array(2,8),
			0 => array(
				'func'			=> 'opt_hidden',
				'type'			=> 'hidden',
				'key'			=> 'ID',
				'value'			=> $vals['ID']
			),
			array(
				'func'			=> 'opt_input',
				'type'			=> 'text',
				'key'			=> 'product_code',
				'label'			=> 'Kode Produk',
				'class'			=> 'input-circle',
				'value'			=> $vals['product_code'],
				'data'			=> 'placeholder="Kode Produk" disabled'
			),
			array(
				'func'			=> 'opt_input',
				'type'			=> 'text',
				'key'			=> 'name',
				'label'			=> 'Nama',
				'class'			=> 'input-circle',
				'value'			=> $vals['name'],
				'data'			=> 'placeholder="Nama Barang" disabled'
			),
			array(
				'func'			=> 'opt_input',
				'type'			=> 'text',
				'key'			=> 'name_category',
				'label'			=> 'Kategori',
				'class'			=> 'input-circle',
				'value'			=> $vals['meta_value_cate'],
				'data'			=> 'placeholder="kategori" disabled'
			),
			array(
				'func'			=> 'opt_input',
				'type'			=> 'price',
				'key'			=> 'price',
				'label'			=> 'Harga',
				'class'			=> 'input-circle',
				'value'			=> format_nominal($vals['price']),
				'data'			=> 'placeholder="Harga"'
			),
			array(
				'func'			=> 'opt_textarea',
				'key'			=> '_note',
				'label'			=> 'Deskripsi',
				'class'			=> 'input-circle',
				'value'			=> $vals['_note'],
				'data'			=> 'placeholder="Deskripsi"',
				'rows'			=> 4
			)
		);

		$_data = array(
			'reff'		=> $vals['ID'],
			'image' 	=> $vals['picture'],
			'detail'	=> $vals['_detail'],
			'data'		=> $data
		);
		
		$args['func'] = array('_layout_form');
		$args['data'] = array($_data);
		
		return modal_admin($args);
	}

	public static function _layout_form($_args=array()){
		$_index = $_args['reff'];
		$detail = $_args['detail'];
		$picture = $_args['image'];
		$args = $_args['data'];

		$image = 'no-image.png';
		if($picture!=0){
			if(!empty($picture)){
				$image = sobad_post::get_id($picture,array('notes'));
				$image = $image[0]['notes'];
			}
		}

		$paket = '';$status = false;
		

		$_modal = isset($_args['modal'])?$_args['modal']:'myModal2';
		$_load = isset($_args['load'])?$_args['load']:'here_modal2';

		?>
			<style type="text/css">
				.box-image-show{
					cursor:default;
					padding-left: 50px;
				}

				.box-image-show>img {
				    border-radius: 20px !important;
				}
			</style>

			<div class="row" style="padding-right: 20px;">
				<div class="col-md-3 box-image-show">
					<img src="asset/img/upload/<?php print($image) ;?>" style="width:100%" id="profile-product">
				</div>
				<div class="col-md-9">
					<?php theme_layout('sobad_form',$args) ;?>
				</div>
			</div>
			<hr>			
		<?php
		$table = self::_table_detail($detail);

		$box = array(
			'label'		=> 'Detail Part',
			'tool'		=> '',
			'action'	=> '',
			'func'		=> 'sobad_table',
			'data'		=> $table
		);

		theme_layout('_portlet',$box);
	}

	public static function _table_detail($detail=''){
		$args = array();
		if(!empty($detail)){
			$assy = json_decode($detail,true);
			if(isset($assy['detail'])){
				$args = $assy['detail']['content'];
			}
		}

		$data['class'] = '';
		$data['table'] = array();

		$no = 0;
		foreach($args as $key => $item){
			$no += 1;
			$id = $item['ID'];

			$val = sobad_item::get_id($id,array('name','picture','product_code','price'));
			$val = $val[0];

			$picture = json_decode($val['picture'],true);
			$picture = $picture['image'];

			$image = 'no-image.png';
			if($picture!=0){
				if(!empty($picture)){
					$image = sobad_post::get_id($picture,array('notes'));
					$image = $image[0]['notes'];
				}
			}

			$image = '<img src="asset/img/upload/'.$image.'" style="width:100%">';
			
			$data['table'][$no-1]['tr'] = array('');
			$data['table'][$no-1]['td'] = array(
				'No'		=> array(
					'center',
					'5%',
					$no,
					true
				),
				'Image'		=> array(
					'center',
					'10%',
					$image,
					true
				),
				'Name'		=> array(
					'left',
					'auto',
					$val['name'],
					true
				),
				'Part No.'	=> array(
					'left',
					'15%',
					$val['product_code'],
					true
				),
				'Jumlah'	=> array(
					'left',
					'10%',
					format_nominal($item['qty']) . ' pcs',
					true
				),
				'Harga'	=> array(
					'right',
					'15%',
					'Rp. '.format_nominal($val['price']),
					true
				)
			);
		}
		
		return $data;
	}

	// ----------------------------------------------------------
	// Function to database -------------------------------------
	// ----------------------------------------------------------

	public static function _callback($args=array(),$_args=array()){

		$packet = array();
		$_args = sobad_asset::ajax_conv_array_json($_args);
		if(isset($_args['id_item'])){
			foreach ($_args['id_item'] as $key => $val) {
				$packet[$key] = array(
					'ID'	=> $val,
					'qty'	=> $_args['qty_item'][$key]
				);
			}
		}

		$packet = json_encode(array(
			'detail' => array(
				'base'		=> 0,
				'content'	=> $packet
			)
		));

		$picture = array("image" => array($args['picture']));
		$picture = json_encode($picture);

		$args['type'] = 1;
		$args['picture'] = $picture;
		$args['_note'] = filter_var($args['description'], FILTER_SANITIZE_STRING);
		$args['_detail'] = $packet;
		return $args;
	}	
}