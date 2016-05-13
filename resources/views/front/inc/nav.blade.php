<nav class="navbar-default navbar-static-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav" id="side-menu">
			<li class="nav-header">
				<div class="dropdown profile-element">
					<span>
						{!! Html::image($_avatar, $_pam['username'], ['class' => 'img-circle', 'style'=> 'width: 50px;'])!!}
					</span>
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
						<span class="clear">
							<span class="block m-t-xs">
								<strong class="font-bold">{{$_pam['account_name']}}<b class="caret"></b></strong>
							</span>
						</span>
					</a>
					@if (check_auth($_role_id, 'user.basic || user.avatar || user.safe || user.logout'))
						<ul class="dropdown-menu animated fadeInRight m-t-xs">
							@if (check_auth($_role_id, 'user.basic'))
								<li><a href="{{route('user.basic')}}">基本信息</a></li>
							@endif
							@if (check_auth($_role_id, 'user.avatar'))
								<li><a href="{{route('user.avatar')}}">修改头像</a></li>
							@endif
							@if (check_auth($_role_id, 'user.safe'))
								<li><a href="{{route('user.safe')}}">账号安全</a></li>
							@endif
							@if (check_auth($_role_id, 'user.logout'))
								<li><a href="{{route('user.logout')}}">退出</a></li>
							@endif
						</ul>
					@endif
				</div>
				<div class="logo-element">
					LOL
				</div>
			</li>
			@if (check_auth($_role_id, 'home.cp'))
				<li @if ($_route == 'home.cp') class="active" @endif >
					<a href="{{route('home.cp')}}"><i class="fa fa-th-large"></i> <span class="nav-label">我的首页</span></a>
				</li>
			@endif
			@if (check_auth($_role_id, 'order.index'))
				<li @if ($_route == 'order.index') class="active" @endif >
					<a href="{{route('order.index')}}"><i class="fa fa-diamond"></i> <span class="nav-label">我要接单</span></a>
				</li>
			@endif
			@if (check_auth($_role_id, 'order.create'))
				<li @if (in_array($_route, ['order.create', 'order.edit'])) class="active" @endif >
					<a href="{{route('order.create')}}"><i class="fa fa-bar-chart-o"></i> <span class="nav-label">我要发单</span></a>
				</li>
			@endif
			@if (check_auth($_role_id, 'order.my_create || order.my || soldier.my || soldier.index'))
				<li @if (in_array($_route, ['order.my_create', 'order.my', 'soldier.my', 'soldier.index'])) class="active" @endif >
					<a href="{{route('order.my_create')}}"><i class="fa fa-envelope"></i> <span class="nav-label">我的代练 </span><span class="fa arrow"></span></a>
					<ul class="nav nav-second-level collapse">
						@if (check_auth($_role_id, 'order.my_create'))
							<li @if ($_route == 'order.my_create') class="active" @endif >
								<a href="{{route('order.my_create')}}">发单管理</a>
							</li>
						@endif
						@if (check_auth($_role_id, 'order.my'))
							<li @if ($_route == 'order.my') class="active" @endif >
								<a href="{{route('order.my')}}">接单管理</a>
							</li>
						@endif
						@if (check_auth($_role_id, 'soldier.my || soldier.index'))
							<li @if (in_array($_route, ['soldier.my', 'soldier.index'])) class="active" @endif >
								<a href="{{route('soldier.my')}}">我的打手</a>
							</li>
						@endif
					</ul>
				</li>
			@endif
			@if (check_auth($_role_id, 'finance.charge_list || finance.lock_list || finance.money_list || finance.cash_list || finance.charge || finance.charge_confirm || finance.charge_callback'))
				<li @if (in_array($_route, ['finance.charge_list', 'finance.lock_list', 'finance.money_list', 'finance.cash_list', 'finance.charge', 'finance.charge_confirm', 'finance.charge_callback'])) class="active" @endif >
					<a href="{{route('finance.money_list')}}"><i class="fa fa-flask"></i> <span class="nav-label">资金管理</span><span class="fa arrow"></span></a>
					<ul class="nav nav-second-level collapse">
						@if (check_auth($_role_id, 'finance.money_list'))
							<li @if ($_route == 'finance.money_list') class="active" @endif >
								<a href="{{route('finance.money_list')}}">资金流水</a>
							</li>
						@endif
						@if (check_auth($_role_id, 'finance.lock_list'))
							<li @if ($_route == 'finance.lock_list') class="active" @endif >
								<a href="{{route('finance.lock_list')}}">资金冻结</a>
							</li>
						@endif
						@if (check_auth($_role_id, 'finance.charge_list'))
							<li @if ($_route == 'finance.charge_list') class="active" @endif >
								<a href="{{route('finance.charge_list')}}">充值记录</a>
							</li>
						@endif
						@if (check_auth($_role_id, 'finance.cash_list'))
							<li @if ($_route == 'finance.cash_list') class="active" @endif >
								<a href="{{route('finance.cash_list')}}">提现记录</a>
							</li>
						@endif
						@if (check_auth($_role_id, 'finance.charge'))
							<li @if ( in_array($_route,  ['finance.charge', 'finance.charge_confirm', 'finance.charge_callback' ])) class="active" @endif >
								<a href="{{route('finance.charge')}}">充值</a>
							</li>
						@endif
						@if (check_auth($_role_id, 'finance.cash'))
							<li @if ( in_array($_route,  ['finance.cash', 'finance.charge_confirm', 'finance.charge_callback' ])) class="active" @endif >
								<a href="{{route('finance.cash')}}">提现</a>
							</li>
						@endif
					</ul>
				</li>
			@endif
			@if (check_auth($_role_id, 'user.basic || user.safe || user.validate_truename || subuser.index'))
				<li @if (in_array($_route, ['user.basic', 'user.safe', 'user.validate_truename', 'subuser.index'])) class="active" @endif >
					<a href="{{route('user.basic')}}"><i class="fa fa-edit"></i> <span class="nav-label">个人设置</span><span class="fa arrow"></span></a>
					<ul class="nav nav-second-level collapse">
						@if (check_auth($_role_id, 'user.basic'))
							<li @if (in_array($_route, ['user.basic'])) class="active" @endif >
								<a href="{{route('user.basic')}}">基本信息</a>
							</li>
						@endif
						@if (check_auth($_role_id, 'user.safe'))
							<li @if (in_array($_route, ['user.safe'])) class="active" @endif >
								<a href="{{route('user.safe')}}">账号安全</a>
							</li>
						@endif
						@if (check_auth($_role_id, 'user.validate_truename'))
							<li @if (in_array($_route, ['user.validate_truename'])) class="active" @endif >
								<a href="{{route('user.validate_truename')}}">实名认证</a>
							</li>
						@endif
						@if (check_auth($_role_id, 'subuser.index'))
							<li @if (in_array($_route, ['subuser.index'])) class="active" @endif >
								<a href="{{route('subuser.index')}}">子账号</a>
							</li>
						@endif
					</ul>
				</li>
			@endif
			@if (check_auth($_role_id, 'help.feedback || help.index || help.show'))
				<li @if (in_array($_route, ['help.feedback', 'help.index', 'help.show'])) class="active" @endif >
					<a href="{{route('help.feedback')}}"><i class="fa fa-desktop"></i> <span class="nav-label">帮助中心</span><span class="fa arrow"></span></a>
					<ul class="nav nav-second-level collapse">
						@if (check_auth($_role_id, 'help.feedback'))
							<li @if (in_array($_route, ['help.feedback'])) class="active" @endif >
								<a href="{{route('help.feedback')}}">我要吐槽</a>
							</li>
						@endif
						@if (check_auth($_role_id, 'help.index || help.show'))
							<li @if (in_array($_route, ['help.index', 'help.show'])  && isset($cat_id) && $cat_id == 2) class="active" @endif >
								<a href="{{route('help.index', ['cat_id'=>2])}}">常见问题</a>
							</li>
							<li @if (in_array($_route, ['help.index', 'help.show']) && isset($cat_id) && $cat_id == 1) class="active" @endif >
								<a href="{{route('help.index', ['cat_id'=>1])}}">网站公告</a>
							</li>
						@endif
					</ul>
				</li>
			@endif
			@if (check_auth($_role_id, 'user.invite'))
				<li @if (in_array($_route, ['user.invite'])) class="active" @endif >
					<a href="{{route('user.invite')}}"><i class="fa fa-bullseye"></i> <span class="nav-label">推广中心</span></a>
				</li>
			@endif
		</ul>
	</div>
</nav>