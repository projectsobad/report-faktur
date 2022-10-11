<?php
(!defined('THEMEPATH'))?exit:'';

class user_login{
	public static function login($func='login_system',$src=''){
		?>
			<div class="login-wrapper">
		        <div class="container-fluid h-full">
		            <div class="row">
		                <?php
		                	self::_form_image($src);
		                	self::_form_login($func);
		                ?>
		            </div>
		        </div>
		    </div>	
		<?php
	}

	public static function _form_image($src=''){
		$loc = SITE .'://' . HOSTNAME . '/' . URL;
		$src = empty($src)? $loc . '/theme/'._theme_folder.'/asset/img/login-sasi.png':$src;

		?>
			<div class="col-md-5 login-col secondary-grey">
                <div class="content-login text-center">
                    <img class="login-image" src="<?php echo $src ;?>" alt="">
                </div>
            </div>
		<?php
	}

	public static function _form_login($func=''){
		$loc = SITE .'://' . HOSTNAME . '/' . URL;
		?>
			<div class="col-md-7 login-col light">
                <div class="col-login-logo">
                    <img class="login-logo" src="<?php echo $loc ;?>/theme/<?php echo _theme_folder ;?>/asset/img/sasi-logo-login.png" alt="">
                </div>
                <div class="form-login">
                    <div class="login_inner">
                        <div class="col-lg-12">
                            <h1 class="font-weight-600">Login to SASI</h1>
                            <p>Make it Super Simple</p>
                        </div>
                        <div class="col-md-12 pt-xl">
                        	<?php echo self::_form_input($func) ;?>
                        </div>
                    </div>
                </div>
            </div>
		<?php
	}

	public static function _form_input($func=''){
		?>
			<!-- BEGIN LOGIN FORM -->
			<form class="login-form" data-sobad="<?php print($func) ;?>" action="javascript:void(0)" method="post">
				<div class="form-group">
					<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
					<label class="control-label visible-ie8 visible-ie9"><?php print(__e('username')) ;?></label>
					<div class="input-icon">
						<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="<?php print(__e('username')) ;?>" name="username"/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label visible-ie8 visible-ie9"><?php print(__e('password')) ;?></label>
					<div class="input-icon">
						<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="<?php print(__e('password')) ;?>" name="password"/>
					</div>
				</div>
				<div class="checkbox pb-sm">
                    <label class="checkbox">
					<input type="checkbox" name="remember" value="1"/> <?php print(__e('remember me')) ;?> </label>
                </div>
                <button id="btn_login_submit" data-sobad="<?php print($func) ;?>" type="submit" class="btn btn-login purple color-light">
                	<?php print(__e('login_button')) ;?>
                </button>
			</form>
			<!-- END LOGIN FORM -->
		<?php
	}
}