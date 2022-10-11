<?php

class sasi_header
{
	protected static $menu = array();

	public static function _create()
	{
?>

		<!-- BEGIN HEADER INNER -->
		<?php
		self::_contain_menu();
		self::menu_user();
		?>
		<!-- END HEADER INNER -->
	<?php

		return self::$menu;
	}

	public static function _logo()
	{
		$loc = SITE .'://' . HOSTNAME . '/' . URL;
	?>
		<!-- BEGIN LOGO -->
		<div class="navbar-header">
			<a class="navbar-brand" href="#">
				<img src="<?php echo $loc ;?>/theme/<?php echo _theme_folder; ?>/asset/img/sasi-logo.png" width="50" height="50">
			</a>
		</div>
		<!-- END LOGO -->
	<?php
	}

	public static function _menu_toggle()
	{
	?>
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<button type="button" class="navbar-toggle topbar-content collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<!-- END RESPONSIVE MENU TOGGLER -->
	<?php
	}

	public static function _sasi_menu()
	{
		global $reg_sidebar;

	?>
		<!-- BEGIN TOP NAVIGATION MENU -->
		<div class="navbar-collapse topnav collapse" id="bs-example-navbar-collapse-1" aria-expanded="false" style="height: 1px;">
			<ul class="nav navbar-nav navbar-item pl-sm">
				<?php
				self::_sidemenu_horizontal($reg_sidebar);
				?>
			</ul>
		</div>
		<!-- END TOP NAVIGATION MENU -->
	<?php
	}

	public static function _sidemenu_horizontal($args = array())
	{
		$check = array_filter($args);
		if (!empty($check)) {
			foreach ($args as $key => $val) {
				// Check active Sidemenu
				if ($val['status'] == 'active') {
					$status = 'active';
				} else {
					$status = '';
				}

				$disable = '';
				if (empty($val['func'])) {
					$disable = 'disable-target';
				}

				echo '<li>';

				echo '<a id="sobad_' . $val['func'] . '" class="sasi_sidemenu ' . $status . ' ' . $disable . '" href="javascript:" data-uri="' . $key . '">
					<h4 class="font-weight-200 pb-sm">' . $val['label'] . '</h4></a>';

				echo '</li>';
			}
		}
	}

	public static function _contain_menu()
	{
	?>

		<div class="container-fluid">
			<div class="navbar-header">
				<?php
				self::_logo();
				self::_menu_toggle();
				?>
			</div>
			<?php
			self::_sasi_menu();
			?>
		</div>

	<?php
	}


	public static function menu_user()
	{
		$loc = SITE .'://' . HOSTNAME . '/' . URL;
		$name = get_name_user();
		$id = get_id_user();

		$user = get_picture_user();
		$dept = get_divisi_user();
		$image = empty($user) ? $loc . '/asset/img/user/no-profile.jpg' : $user;

	?>
		<div class="navbar-menu">
			<div class="navbar-section">
				<div class="notify">
					<div id="myDropDown" class="dropdown">
						<button class="dropdown-toggle transparent border-none btn-circle" type="button" id="notify" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							<div class="navbar-content color-light p-0 m-0">
								<h2 class="m-0"><i class="fa fa-bell-o" aria-hidden="true"></i></h2>
							</div>
						</button>
						<ul id="myTabs" class="dropdown-menu navdrop" role="menu" aria-labelledby="dLabel">
							<ul class="nav nav-line">
								<li class="active"><a href="#home" data-toggle="tab">
										<h4 class="color-dark-black font-weight-600">Notification</h4>
										<div class="number-notify red"><small>1</small></div>
									</a>
								</li>
								<li><a href=" #profile" data-toggle="tab">
										<h4 class="color-dark-black font-weight-600">Team Activity</h4>
										<div class="number-notify red"><small>90</small></div>
									</a>
								</li>
							</ul>
							<!-- Tab panes -->
							<div class="tab-content">
								<div class="tab-pane active" id="home">
									<li><small class="color-dark-grey mt-md">Today</small></li>
									<li class="content-notify mt-sm ">
										<img class="img-notify mr-sm" src="<?php print($image); ?>">
										<span class="color-dark-black font-weight-600"><small>Gading
												Rengga</small>
										</span>
										<span class="color-dark-grey"><small> send you a new
												task</small>
										</span>
										<span class="color-dark-black font-weight-600"><small>
												Project Team</small>
										</span>
										<span class="color-dark-grey ml-lg align-right"><small>6
												min</small>
										</span>
									</li>
								</div>
								<div class="tab-pane" id="profile">
									<li><small class="color-dark-grey mt-md">Yesterday</small></li>
									<li class="content-notify mt-sm ">
										<img class="img-notify mr-sm" src="../assets/sasi-ui/img/gading-shape.png">
										<span class="color-dark-black font-weight-600"><small>Ana
												Trisni</small>
										</span>
										<span class="color-dark-grey"><small> send you a new
												task</small>
										</span>
										<span class="color-dark-black font-weight-600"><small>
												Project Team</small>
										</span>
										<span class="color-dark-grey ml-lg align-right"><small>1
												days ago</small>
										</span>
									</li>
								</div>
							</div>
						</ul>
					</div>
				</div>
				<div class="lang">
					<div class="dropdown">
						<button class="dropdown-toggle transparent border-none" type="button" id="lang" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							<div class="navbar-content border-light content-lang">
								ID
							</div>
						</button>
						<ul class="langdrop dropdown-menu" aria-labelledby="lang">
							<li><a href="#">Indonesia</a></li>
							<li><a href="#">English</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="icon-user">
			<div class="row m-0">
				<div class="col-xs-8 pr-md pt-sm m-0 pl-0 text-right">
					<div class="icon-user-item">
						<h4 class="font-weight-700 color-light"><?php print($name); ?></h4>
						<h6 class="color-light"><?php print($dept); ?></h6>
					</div>
				</div>
				<div class="col-xs-1  m-0 pt-md pl-0 pr-0">
					<div class="dropdown">
						<button class="btn-circle btn dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							<img src="<?php print($image); ?>">
						</button>
						<ul class="dropdown-menu icon-user-menu" aria-labelledby="dropdownMenu1">
							<li><a href="javascript:">My Profile</a></li>
							<li><a href="javascript:">My Calender</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="javascript:">Lock Screen</a></li>
							<li role="separator" class="divider"></li>
							<a href="#myModal" data-toggle="modal" class="btn btn-sm magenta color-light radius-sm mb-md sobad_logout" tabindex="-1" role="button" aria-disabled="true">Logout</a>
						</ul>
					</div>
				</div>
			</div>
		</div>
	<?php
	}

	private static function _scroll_bar()
	{
	?>
		<div class="slimScrollBar" style="background: rgb(99, 114, 131); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 147.994px;"></div>
		<div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(234, 234, 234); opacity: 0.2; z-index: 90; right: 1px;"></div>
<?php
	}
}
