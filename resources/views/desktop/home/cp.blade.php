@extends('lemon.template.desktop')
@section('body-start')
<body style="margin: 0;overflow: hidden;" scroll="no">
@endsection
	@section('desktop-main')
		<table style="width: 100%;height: 100%;" cellpadding="0" cellspacing="0">
			<tbody>
			<tr>
				<td colspan="2" height="90" class="mainhd">
					<div class="layout-header">
						<!-- Title/Logo - can use text instead of image -->
						<div class="logo"><a href="{{url('dsk_cp')}}"></a></div>
						<!-- Top navigation -->
						<div id="topnav" class="top-nav">
							<ul>
								<li class="adminid">{{trans('desktop.cp.hello')}}&nbsp;:&nbsp;
									<strong>
										@if ($_admin['realname'])
											{{$_admin['realname']}}
										@else
											{{$_pam['account_name']}}
										@endif
										(<span data-roleId="{{$_role['role_id']}}">{{$_role['role_name']}}</span>)
									</strong>
								</li>
								<li><a href="{{route('dsk_home.password')}}" target="workspace" title="{{trans('desktop.edit_password')}}"><span>{{trans('desktop.edit_password')}}</span></a></li>
								<li><a href="{{route('dsk_home.logout')}}" target="workspace" title="{{trans('desktop.cp.logout')}}"><span>{{trans('desktop.cp.logout')}}</span></a></li>
							</ul>
						</div>
						<!-- End of Top navigation -->
						<!-- Main navigation -->
						<nav id="J_nav" class="main-nav">
							<ul>
								@foreach($menus as $nav_key => $nav)
									@if ($nav['link_count'])
									<li>
										<a class="link" id="J_nav_{{$nav_key}}" href="javascript:void(0);" data-route="{{ route($nav['route'])}}" data-rel="{{$nav_key}}" data-param="{{$nav['param']}}">
											<span>{{$nav['title']}}</span>
										</a>
									</li>
									@endif
								@endforeach
							</ul>
						</nav>
						<div class="location"><strong>{{trans('desktop.cp.location')}}:</strong>
							<div id="J_crumbs" class="crumbs"><span>{{trans('desktop.cp.welcome')}}</span><span class="arrow">&nbsp;</span><span>{{trans('desktop.cp.welcome')}}</span></div>
						</div>
						<div class="toolbar">
							<div class="sitemap"><a id="J_sitemap" href="javascript:void(0);"><span>管理地图</span></a></div>
							<div class="toolmenu"><span id="J_quickAction" class="bar-btn"></span>
								<ul class="bar-list">
									<li><a href="javascript:void(0);" id="J_iframeRefresh">刷新管理中心</a></li>
									<li><a href="javascript:void(0);" id="J_addBookmark" data-label="管理中心" data-linkurl="#">收藏管理中心</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div></div>
				</td>
			</tr>
			<tr>
				<td class="menutd" width="161">
					<div id="J_mainMenu" class="main-menu">
						@foreach($menus as $nav_key => $nav)
							<ul id="J_menu_{{$nav_key}}">
								<li>
									<dl>
										<dd>
											<ol>
												@foreach($nav['group'] as $nav_group)
												<?php $group_link = $nav['menu_link'][$nav_group]; ?>
												@if (isset($group_link['sub_group']) && !empty($group_link['sub_group'])) <!-- has children-->
													<li class="J_sideGroup" data-rel="{{$nav_group}}">
														<dt>{{$group_link['title']}}</dt>
													</li>
													@foreach($group_link['sub_group'] as $sub)
														<li class="group" data-group="{!! $nav_group !!}">
															<a href="javascript:void(0);" data-route="{{ route($sub['route'])}}" data-param="{{$sub['param']}}" data-rel="{{$nav_key}}">{{$sub['title']}}</a>
														</li>
													@endforeach
												@endif
												@if (isset($group_link['direct']) && !empty($group_link['direct']))
													@foreach($group_link['direct'] as $sub)
														<li>
															<a href="javascript:void(0);" data-route="{{ route($sub['route'])}}" data-param="{{$sub['param']}}" data-rel="{{$nav_key}}">{{$sub['title']}}</a>
														</li>
													@endforeach
												@endif
												@endforeach
											</ol>
										</dd>
									</dl>
								</li>
							</ul>
						@endforeach
					</div>
					<div class="copyright">{{ site('copyright')}}</div>
				</td>
				<td style="vertical-align: top;width:100%;">
					<iframe src="" id="workspace" name="workspace" style="overflow: visible;" frameborder="0" width="100%" height="100%" scrolling="yes" onload="window.parent"></iframe>
				</td>
			</tr>
			</tbody>
		</table>
	@endsection

	@section('script-cp')
		<script>require(['lemon/desktop_frame']);</script>
@overwrite